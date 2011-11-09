<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * ...
 */
class Willow_Search_Scoring_LiquidMetal extends Willow_Search_Scoring_Abstract
{

    /**
     * ...
     */
    const SCORE_NO_MATCH                = 0.0;
    const SCORE_MATCH                   = 1.0;
    const SCORE_TRAILING                = 0.8;
    const SCORE_TRAILING_BUT_STARTED    = 0.9;
    const SCORE_BUFFER                  = 0.85;

    /**
     * ...
     */
    public function sort($a, $b)
    {
        if ($a['score'] === $b['score'])
        {
            return 0;
        }

        return ($a['score'] > $b['score']) ? -1 : 1;
    }

    /**
     * ...
     */
    public function score($string)
    {
        /**
         * Short circuits
         */
        if (strlen($this->_keywords) === 0)
        {
            return self::SCORE_TRAILING;
        }

        if (strlen($this->_keywords) > strlen($string))
        {
            return self::SCORE_NO_MATCH;
        }

        $scoreSum = 0.0;

        foreach (($scores = $this->_buildScoreArray($string)) as $score)
        {
            $scoreSum += $score;
        }

        return ($scoreSum / count($scores));
    }

    protected $_weightedString;

    /**
     * ...
     */
    public function getWeightedString()
    {
        return $this->_weightedString;
    }

    protected function _buildScoreArray($string)
    {
        $scores = array();
        $weightChars = str_split($string);
        $lower = strtolower($string);
        $chars = str_split(strtolower($this->_keywords));

        $lastIndex = -1;
        $started = false;

        foreach ($chars as $c)
        {
            if (($index = strpos($lower, $c, ($lastIndex + 1))) === false)
            {
                $this->_weightedString = $string;
                return array(self::SCORE_NO_MATCH);
            }

            if ($index === 0)
            {
                $started = true;
            }

            switch (true)
            {

                case $this->_isNewWord($string, $index):
                    $scores[$index - 1] = 1;
                    $this->_fillArray($scores, self::SCORE_BUFFER, $lastIndex + 1, $index - 1);
                    break;

                case $this->_isUpperCase($string, $index):
                    $this->_fillArray($scores, self::SCORE_BUFFER, $lastIndex + 1, $index);
                    break;

                default:
                    $this->_fillArray($scores, self::SCORE_NO_MATCH, $lastIndex + 1, $index);
                    break;
            }

            $scores[$index] = self::SCORE_MATCH;
            $lastIndex = $index;
            $weightChars[$index] = sprintf('<strong>%s</strong>', substr($string, $index, 1));
        }

        $trailingScore = $started ? self::SCORE_TRAILING_BUT_STARTED : self::SCORE_TRAILING;
        $this->_fillArray($scores, $trailingScore, $lastIndex + 1, count($chars));

        $this->_weightedString = implode('', $weightChars);

        return $scores;
    }

    /**
     * ...
     */
    protected function _isNewWord($string, $index)
    {
        $c = substr($string, ($index - 1), 1);
        return ($c === '' || $c === "\t");
    }

    /**
     * ...
     */
    protected function _isUpperCase($string, $index)
    {
        $c = substr($string, $index, 1);
        return ($c >= 'A' && $c <= 'Z');
    }

    /**
     * ...
     */
    protected function _fillArray(&$scores, $value, $from = 0, $to = 0)
    {
        $from = max($from, 0);

        for ($i = $from; $i < $to; ++$i)
        {
            $scores[$i] = $value;
        }
    }

}
