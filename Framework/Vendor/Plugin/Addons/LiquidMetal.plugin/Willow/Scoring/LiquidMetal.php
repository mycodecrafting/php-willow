<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


/**
 * PHP port of LiquidMetal JS: A mimetic poly-alloy of Quicksilver's scoring algorithm
 *
 * ported from http://github.com/rmm5t/liquidmetal
 *
 * Licensed under the MIT:
 * http://www.opensource.org/licenses/mit-license.php
 *
 * PHP port by Josh Dechant <jdechant@shapeup.com>
 */
class Willow_Scoring_LiquidMetal
{

    const SCORE_NO_MATCH                = 0.0;
    const SCORE_MATCH                   = 1.0;
    const SCORE_TRAILING                = 0.8;
    const SCORE_TRAILING_BUT_STARTED    = 0.9;
    const SCORE_BUFFER                  = 0.85;

    /**
     * return a matching score for $needle in $haystack
     */
    public function score($haystack, $needle)
    {
        if ($needle == '')
        {
            return self::SCORE_TRAILING;
        }

        if (strlen($needle) > strlen($haystack))
        {
            return self::SCORE_NO_MATCH;
        }

        $scores = $this->_buildScoreArray($haystack, $needle);
        return (array_sum($scores) / count($scores));
    }

    protected function _buildScoreArray($haystack, $needle)
    {
        $scoresLength = strlen($haystack);
        $scores = array_fill(0, $scoresLength, null);
        $lower = strtolower($haystack);
        $chars = str_split(strtolower($needle));

        $lastIndex = -1;
        $started = false;
        foreach ($chars as $c)
        {
            if (($index = strpos($lower, $c, $lastIndex + 1)) === false)
            {
                return array_fill(0, $scoresLength, self::SCORE_NO_MATCH);
            }

            if ($index === 0)
            {
                $started = true;
            }

            if ($this->_isNewWord($haystack, $index))
            {
                $scores = array_replace($scores, array_fill($lastIndex + 1, $index - 2, self::SCORE_BUFFER));
                $scores[$index - 1] = self::SCORE_MATCH;
                $scores[$index] = self::SCORE_MATCH;
            }
            else if ($this->_isUpperCase($haystack, $index))
            {
                $scores = array_replace($scores, array_fill($lastIndex + 1, $index - 1, self::SCORE_BUFFER));
                $scores[$index] = self::SCORE_MATCH;
            }
            else
            {
                if (($index - 1) > 0)
                {
                    $scores = array_replace($scores, array_fill($lastIndex + 1, $index - 1, self::SCORE_NO_MATCH));
                }

                $scores[$index] = self::SCORE_BUFFER;
            }

            $lastIndex = $index;
        }

        $trailingScore = $started ? self::SCORE_TRAILING_BUT_STARTED : self::SCORE_TRAILING;
        $scores = array_replace($scores, array_fill($lastIndex + 1, $scoresLength, $trailingScore));
        return $scores;
    }

    protected function _isUpperCase($string, $atIndex)
    {
        return (strtoupper($string[$atIndex]) === $string[$atIndex]);
    }

    protected function _isNewWord($string, $atIndex)
    {
        return (isset($string[$atIndex - 1]) && (($string[$atIndex - 1] === ' ') || ($string[$atIndex - 1] === "\t")));
    }

}
