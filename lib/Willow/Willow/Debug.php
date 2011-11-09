<?php

class Willow_Debug
{

    public static function getCodeTrace(Exception $e)
    {
        $trace = '';

        foreach ($e->getTrace() as $step)
        {
            if (!isset($step['file']))
            {
                continue;
            }

            $lines = highlight_file($step['file'], $return = true);
            $lines = explode('<br />', $lines);

            $line = $step['line'] - 1;

            $start = $line - 10;
            if ($start < 0)
            {
                $start = 0;
            }

            $end = $line + 10;
            if (!isset($lines[$end]))
            {
                $end = count($lines) - 1;
            }


            $trace .= '<strong>' . $step['file'] . '</strong> <em>(line ' . $step['line'] . ')</em>:<br/>' .
                '<div style="border: 1px solid #666; background: #E5E5E5; padding: 5px; margin: 5px 0px 25px 0px;"><code>';

            for ($i=$start; $i<$end; ++$i)
            {
                if ($i == $line)
                {
                    $trace .= '<div style="background: #FEFFA9; padding: 2px 0px;">' . $lines[$i] . '</div>';
                }
                else
                {
                    $trace .= $lines[$i] . '<br />';
                }
            }
            
            $trace .= '</code></div>';
        }

        return $trace;
    }

}
