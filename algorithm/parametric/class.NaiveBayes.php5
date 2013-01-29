
<?php
/**
 * NaiveBayes.php
 *
 * @author Yu Chao <chao.yu@gree.co.jp>
 * @package Robotgen/algorithm/parametric/
 * @version $Id$
 *
 * @reference
 *	-Naive Bayes for Text Classification
 *	-http://nlp.stanford.edu/IR-book/html/htmledition/naive-bayes-text-classification-1.html
 *	-http://courses.ischool.berkeley.edu/i290-dm/s11/SECURE/Optimality_of_Naive_Bayes.pdf
 *	-
 *
 */
/**
 *  Algorithm Description
 *=================================================================
 *	The Bayesian Rule is:
 *		  P(x|c)P(x)
 *	P(c|x) = ------------
 *	            P(x)
 *	where we could say that:
 *	              	Likelihood x Prior
 *	Posterior = ----------------------
 *			Evidence
 *	if we apply this to a Spam Filter, then P(C) would be the probability that the message is Spam,
 *	and P(X|C) is the probability that the given word (input) is Spam, given that the message is Spam.
 *	P(X) is just the probability of a word appearing in a message using the given training data.
 *	For the Bayesian Rule above, we have to extend it so that we have:'
 *		            P(x1,x2,...,xn|c)P(c)
 *	P(c|x1,x2,...,xn) = ----------------------
 *				P(x1,x2,...,xn)
 *	Where, if we continued to use the spam filter idea, X1, . Xn would be the input,
 *	or the words from the training data.Naive Bayes is called so because it makes the assumption that
 *	all the input attributes are independent, such as one word doesnt affect the other in deciding
 *	whether or not a message is spam.
 */

require_once GREE_SERVICE_CLASSIFIER_CLASS_ROOT . '/Bayes/Classifier.php5';
require_once GREE_SERVICE_CLASSIFIER_CLASS_ROOT . '/Bayes/Tokenizer/Mecab.php5';
require_once GREE_SERVICE_CLASSIFIER_CLASS_ROOT . '/Bayes/Corpus.php5';
require_once GREE_SERVICE_CLASSIFIER_CLASS_ROOT . '/Bayes/Util.php5';

/**
 * Gree_Service_Classifier_Bayes_Classifier_NaiveBayes
 *
 * NaiveBayes Classification
 *
 * @package GREE
 * @access  public
 */

class Gree_Service_Classifier_Bayes_Classifier_NaiveBayes extends Gree_Service_Classifier_Bayes_Classifier {

    const NAIVEBAYES_HASH_FUNC  = "crc32";
    /**
     * crc32 is the fastest built in hash function.
     */
    private $NB_Stop_Words  = array("the", "a", "an");
    //private $NB_Stop_Words = array("的", "是", "了","得");

    private $_conditional_A  = '';
    private $_conditional_B  = '';

    /**
     * construct function call the parent construct function
     */
    public function __construct($tokenizer, $channel, $options =null) {
        parent::__construct($tokenizer, $channel, $options);

        if (isset($options['NB_Stop_Words'])) {
            $this -> NB_Stop_Words = $options[ 'NB_Stop_Word' ];
        }
        if (isset($options[ '_conditional_A' ])) {
            $this -> _conditional_A = $options[ 'conditional_A' ];
        }
        if (isset($options[ '_conditional_B' ])) {
            $this -> _exclusion_radius = $options[ 'conditional_B'];
        }
    }


    /**
     *
     *
     *  @access  public
     *  @param   string   $content
     *  @param $with_context
     *  @return  float/array
     */
    public function getProbability($content, $with_context=false) {
        $tokens = $this -> tokenize($content);
        $spam_probs = $this -> getSpamProb($tokens);
        $ham_probs = array_map(create_function('$a', 'return 1.0 - $a;'), $spam_probs);

        $S = $this -> _combineProbs($spam_probs);
        $H = $this -> _combineProbs($ham_probs);
        $score = (1 + $S - $H) / 2.0;
        if ($with_context) {
            return array($score,
                    array( 'spam_probs' => $spam_probs ,
                               'ham_probs' => $ham_probs ,
                               'score_s' => $S ,
                               'score_h' => $H ,
                               'score_i' => $score ));
        }
        return $score;
    }

    /**
     *
     *
     * @access protected
     * @return float
     */
    public function getSpamProb($word, $use_word_cache =true) {
        $statistics = $this -> corpus -> getState($this -> channel, true);
        $nham = (float) $statistics[ 'nham' ]==0 ? 1 : $statistics[ 'nham' ];
        $nspam = (float) $statistics[ 'nspam' ]==0 ? 1 : $statistics[ 'nspam' ];

        $word_info = $this -> corpus -> getWordInfo($this -> channel, $word, $use_word_cache);

        if ($word_info[ 'is_culculated' ]) {
            return $word_info[ 'score' ];
        }

        $cache_key = sprintf("%d:%d", $word_info[ 'nham' ], $word_info[ 'nspam' ]);
        if (isset($this -> cached_probs[$cache_key])) {
            return $this -> cached_probs[$cache_key];
        }

        //initial prob
        $n = $word_info[ 'nham' ] + $word_info[ 'nspam' ];
        if ($n < 5) {
            $this -> cached_probs[$cache_key] = $this -> _initial_prob;
            return $this -> cached_probs[$cache_key];
        }

        $ham_ratio = $word_info[ 'nham' ] / $nham;
        $spam_ratio = $word_info[ 'nspam' ] / $nspam;

        $conditional_A = $this-> _conditional_A;
        $conditional_B = $this-> _conditional_B;

        $f = $this-> naiveBayes($conditional_A,$conditional_B,$word);


        $this -> cached_probs[$cache_key] = $f;
        return $f;
    }

    ///////////////////////////////////////////////////////////////

    /**
     *  $xs is a bunch of "strings" and ys are their labels.
     *  @param $xs conditional xs
     *  @param $ys conditional ys
     *  @param $testStrings
     *  @return array
     */

    public function naiveBayes($xs, $ys, $testStrings) {
        $topicWords = array();
        foreach($xs as $i => $x) {
	    if (isset($topicWords[$ys[$i]])) {
                $topicWords[$ys[$i]] .= $x;
            } else {
                $topicWords[$ys[$i]] = $x;
            }
        }
        $topicWords = multiWordCounts($topicWords);   // get the number of each word, by topic.

        $probWordsGivenTopic = array();   // probability of each word in a given topic.
        $countTopics = array();

        foreach($topicWords as $topicIndex => $xWordCounts) {
		        $totalWordsTopic = array_sum($xWordCounts);
            $countTopics[$topicIndex] = $total_wordsTopic;

            foreach($xCount as $hash => $count) {
		            $probWordsGivenTopic[$topicIndex][$hash] = ($count / $totalWordsTopic);
            }
        }

        $probTopics = array();
        // probability of a given topic (number of words / total words),
        // i.e., relative frequency of topics in terms of words
        foreach($countTopics as $i => $topicCount) {
		        $probTopics[$i] = ($topicCount / $totalWords);
        }

        if (!is_array($testStrings)) {
            $testStrings = array($testStrings);
        }

        // process the input testStrings array
        $return = array();
        foreach($testStrings as $i => $string) {
		        $testStringWords = singleWordCount($string);
            $topicsPosterior = array();

            foreach($probTopics as $key => $probTopic) {
		            $p = $probTopic;

                foreach($testStringWords as $hash => $count) {
		                if (isset($probWordsGivenTopic[$key][$hash])) {
                        $p *= $probWordsGivenTopic[$key][$hash] * $count;
                    }
                }
                $topicsPosterior[$key] = $p;
            }
            sort($topicsPosterior);
            $return[$i] = $topicsPosterior;
        }
        return $return;
    }

    /**
     * Returns conditional probability of $A given $B and $SamplePairs .
     * @param  $A conditional a
     * @param  $B conditional b
     * @param  $SamplePairs array pairs
     * @return float
     */
    public function getConditionalProbabilty($A, $B, $SamplePairs) {
        $NumAB = 0;
        $NumB = 0;
        $SampleNum = count($SamplePairs);
        for ($i = 0; $i < $SampleNum; $i++) {
            if (in_array($B, $SamplePairs[$i])) {
                $NumB++;
                if (in_array($A, $SamplePairs[$i])) {
                    $NumAB++;
                }
            }
        }
        return $NumAB / $NumB;
    }
    /**
     * multi word count function
     * @param $content
     * @return array
     */

    public function multiWordCounts($contents) {
        $wordCounts = array();
        foreach($contents as $content ) {
          $wordCounts[] = singleWordCount($content);
        }
        return $wordCounts;
    }
    /**
     * signle word count function
     * @param $content
     * @return array
     */

    public function singleWordCount($content) {
        $content = trim($content);
        $tokens = $this -> tokenize($content);
        natcasesort($tokens);
        $hash = self ::NAIVEBAYES_HASH_FUNC;

        $words = array();
        $count = count($tokens);

        for ($i = 0; $i < $count; $i++) {
            $word = trim($tokens[$i]);
            if (preg_match('/^[\?-\?]+$/', $word))
	       continue;

            $hash = (string) $hash($word);
            if (!isset($words[$hash])) {
                $words[$hash] = 1;
            } else {
                $words[$hash]++;
            }
        }
        return $words;
    }
}
