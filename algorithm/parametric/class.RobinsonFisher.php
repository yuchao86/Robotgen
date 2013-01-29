<?php

/**
 * FileName.php
 *
 * @author Yu Chao <yuchao86@gmail.com>
 * @package Robotgen/algorithm/parametric/
 * @version v1.0
 * @license  GPL     
 *
 * @reference
 * 	-Algorithm Reference
 * @see
 * 	-web Links
 * 	-
 *
 */
/**
 *  Algorithm Description
 * =================================================================
 */
// namespace Robotgen;
/**
 * RobinsonFisher.php
 *  - Spam Detection <http://radio-weblogs.com/0101454/stories/2002/09/16/spamDetection.html>
 *  - A Statistical Approach to the Spam Problem <http://www.linuxjournal.com/article/6467>
 *  - Handling Redundancy in Email Token Probabilities (Gray Robinson, 2004)
 *  - Inverse chi-square algorithm <http://garyrob.blogs.com/chi2p.py>
 */
require_once GREE_SERVICE_CLASSIFIER_CLASS_ROOT . '/Bayes/Classifier.php5';
require_once GREE_SERVICE_CLASSIFIER_CLASS_ROOT . '/Bayes/Tokenizer/Mecab.php5';
require_once GREE_SERVICE_CLASSIFIER_CLASS_ROOT . '/Bayes/Corpus.php5';
require_once GREE_SERVICE_CLASSIFIER_CLASS_ROOT . '/Bayes/Util.php5';

/**
 * Gree_Service_Classifier_Bayes_Classifier_RobinsonFisher
 *
 * Robinson-Fisher
 *
 * @package GREE
 * @access  public
 */
class Gree_Service_Classifier_Bayes_Classifier_RobinsonFisher extends Gree_Service_Classifier_Bayes_Classifier {

    private $_initial_prob = 0.4;
    private $_initial_prob_weight = 1.0;
    private $_max_token_num = 20;
    private $_exclusion_radius = 0.2;

    /**
     *  construct function
     */
    public function __construct($tokenizer, $channel, $options = null) {
        parent::__construct($tokenizer, $channel, $options);

        if (isset($options['initial_prob'])) {
            $this->_initial_prob = $options['initial_prob'];
        }
        if (isset($options['initial_prob_weight'])) {
            $this->_initial_prob_weight = $options['initial_prob_weight'];
        }
        if (isset($options['max_token_num'])) {
            $this->_max_token_num = $options['max_token_num'];
        }
        if (isset($options['exclusion_radius'])) {
            $this->_exclusion_radius = $options['exclusion_radius'];
        }
    }

    /**
     *  
     *
     *  ham, spam (Fisher Method) 
     *  I = (1 + H - S) / 2
     *  
     *  @access  public
     *  @param   string   $content  
     *  @param $with_context 
     *  @return  float/array 
     */
    public function getProbability($content, $with_context = false) {
        $tokens = $this->tokenize($content);
        $spam_probs = $this->getSpamProbStream($tokens);
        $ham_probs = array_map(create_function('$a', 'return 1.0 - $a;'), $spam_probs);
        $S = $this->_combineProbs($spam_probs);
        $H = $this->_combineProbs($ham_probs);
        $score = (1 + $S - $H) / 2.0;
        if ($with_context) {
            return array($score,
                array('spam_probs' => $spam_probs,
                    'ham_probs' => $ham_probs,
                    'score_s' => $S,
                    'score_h' => $H,
                    'score_i' => $score
                    ));
        }
        return $score;
    }

    /**
     *  (Robinson-Fisher )
     *
     *  p = C-1( -2.ln(prod(p)), 2*n )
     */
    private function _combineProbs($probs) {
        $n = count($probs);
        $mul = create_function('$a, $b', 'return $a * $b;');
        $p_combined = Gree_Service_Classifier_Bayes_Util::chi2p(-2 * log(array_reduce($probs, $mul, 1.0)), 2 * $n);
        return $p_combined;
    }

    /**
     *  (Gray Robinson)
     *
     *  @access  protected
     *  @return  float
     */
    public function getSpamProb($word, $use_word_cache = true) {
        $statistics = $this->corpus->getState($this->channel, true);
        $nham = (float) $statistics['nham'] == 0 ? 1 : $statistics['nham'];
        $nspam = (float) $statistics['nspam'] == 0 ? 1 : $statistics['nspam'];

        $word_info = $this->corpus->getWordInfo($this->channel, $word, $use_word_cache);

        if ($word_info['is_culculated']) {
            return $word_info['score'];
        }

        $cache_key = sprintf("%d:%d", $word_info['nham'], $word_info['nspam']);
        if (isset($this->cached_probs[$cache_key])) {
            return $this->cached_probs[$cache_key];
        }

        // initial prob
        $n = $word_info['nham'] + $word_info['nspam'];
        if ($n < 5) {
            $this->cached_probs[$cache_key] = $this->_initial_prob;
            return $this->cached_probs[$cache_key];
        }

        $ham_ratio = $word_info['nham'] / $nham;
        $spam_ratio = $word_info['nspam'] / $nspam;

        $p = $spam_ratio / ($ham_ratio + $spam_ratio);

        // $s: (default: 0.4)
        // $x: (default: 1.0)
        //
        //       $s * $x + $n * $p
        // $f = -------------------
        //           $x + $n
        $s = $this->_initial_prob;
        $x = $this->_initial_prob_weight;
        $f = ($s * $x + $n * $p) / ($x + $n);
        $this->cached_probs[$cache_key] = $f;
        return $f;
    }

    /**
     *
     *  @access  protected
     *  @return  array $tokens
     */
    public function getSpamProbStream($tokens) {
        $probs = array();
        foreach ($tokens as $tok) {
            $f = $this->getSpamProb($tok);
            // 0.5
            if (abs($f - 0.5) >= $this->_exclusion_radius) {
                $probs[$tok] = max(0.0001, min(0.9999, $f));
            }
        }
        // 0.5 
        $cmp = create_function('$a, $b', '$aa = abs(0.5-$a); $ab = abs(0.5-$b);
                                if ($aa==$ab) {return 0;} return ($aa > $ab) ? -1 : 1;');
        uasort($probs, $cmp);
        return array_slice($probs, 0, $this->_max_token_num, true);
    }

}
