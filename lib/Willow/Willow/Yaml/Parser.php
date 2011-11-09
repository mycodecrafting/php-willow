<?php
/* $Id$ */
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */


class Willow_Yaml_Parser
{

 	protected $nodes = array();
	protected $currentParent = null;
	protected $currentLevel = 0;

	protected static $COMMENT_INDICATOR = '#';
	protected static $BOOL_TRUE_SCALARS = array('true', 'yes', 'y', 'on');
	protected static $BOOL_FALSE_SCALARS = array('false', 'no', 'n', 'off');
	protected static $NULL_SCALARS = array('null', '~');

	public function __construct(Willow_Yaml_Node $root)
	{
		$this->nodes = array(0 => $root);
	}

	public function assimilateData($yaml)
	{
		$file = $this->getDataLines($yaml);

		$currentNode = $this->nodes[0];

		foreach ($file as $line)
		{
			if ($this->isComment($line) || $this->isEmpty($line))
			{
				continue;
			}

			if ($this->isChildNode($line))
			{
				$this->assimilateChildNode($line);
				continue;
			}

			if ($this->isNestedSeries($line))
			{
				$this->assimiateNestedSeries($line);
				continue;
			}

			if ($this->isBase64Binary($line))
			{
				$this->assimiateBase64Binary($line);
				continue;
			}

			if ($this->isKeyValuePair($line))
			{
				$this->assimiateKeyValuePair($line);
				continue;
			}

			// multi line flow scalar
			$this->nodes[$this->currentLevel]->appendKeyValuePair($this->currentParent, trim($line));
		}
	}


	protected function assimilateChildNode($line)
	{
		$name = substr(trim($line), 0, -1);
		$level = $this->getLevel($line);
		$this->currentParent = $name;
		$this->currentLevel = $level;
		$this->nodes[$level + 1] = $this->nodes[$level]->addChildNode($name);
	}


	protected function assimiateNestedSeries($line)
	{
		preg_match('/^-\s+(.+)$/', trim($line), $matches);

		$value = $this->autoType(trim($matches[1]));

		$level = $this->getLevel($line);
		$this->nodes[$level - 1]->addSeriesValue($this->currentParent, $value);
	}


	protected function assimiateKeyValuePair($line)
	{
		preg_match('/^([A-z]{1}[^\s]*):(.+)$/', trim($line), $matches);
		$key = $matches[1];
		$value = $this->autoType(trim($matches[2]));

		$level = $this->getLevel($line);
		$this->nodes[$level]->addKeyValuePair($key, $value);
	}


	protected function assimiateBase64Binary($line)
	{
		$this->assimiateKeyValuePair(str_replace('!!binary', '', $line));
	}


	protected function getLevel($line)
	{
		if (preg_match('/^\s+/', $line, $matches))
		{
			return substr_count($matches[0], '  ');
		}

		return 0;
	}


	/**
	 * must start with A-z and end with ":" (no spaces)
	 */
	protected function isChildNode($line)
	{
		$line = trim($line);
		return (preg_match('/^[A-z]{1}[^\s]*:$/', $line) === 1);
	}


	protected function isNestedSeries($line)
	{
		return (strpos(trim($line), '-') === 0);
	}


	protected function isKeyValuePair($line)
	{
		$line = trim($line);
		return (preg_match('/^[A-z]{1}[^\s]*:.+$/', $line) === 1);
	}


	protected function isBase64Binary($line)
	{
		$line = trim($line);
		return (preg_match('/^[A-z]{1}[^\s]*:\s+!!binary\s+.+$/', $line) === 1);
	}


	protected function isComment($line)
	{
		return (strpos(trim($line), self::$COMMENT_INDICATOR) === 0);
	}


	protected function isEmpty($line)
	{
		return (trim($line) === '');
	}


	protected function getDataLines($yaml)
	{
		if (strpos($yaml, "\n") === false)
		{
			return file($yaml);
		}

		return explode("\n", $yaml);
	}


	protected function autoType($scalar)
	{
		$lower_value = strtolower($scalar);

		switch (true)
		{
			// $scalar is either integer or float
			case (is_numeric($scalar) ||
				is_numeric(str_replace('_', '', $scalar)) ||
				is_numeric(str_replace(',', '', $scalar)) ||
				is_numeric(str_replace('+', '', $scalar))
			):
				switch (true)
				{
					// $scalar is a decimal integer
					case ($scalar === (string)(int)$scalar):
						return (int)$scalar;
						break;

					// $scalar is a float
					case ($scalar === (string)(float)$scalar):
						return (float)$scalar;
						break;

					// $scalar is a hexadecimal integer
					case (strpos($lower_value, '0x') === 0):
						return hexdec($lower_value);
						break;

					// $scalar is an octal integer
					case (strpos($scalar, '0') === 0):
						return octdec($scalar);
						break;

					// $scalar is a float or integer with "," in it
					case (strpos($scalar, ',') !== false):
						return $this->autoType(str_replace(',', '', $scalar));
						break;

					// $scalar is a float or integer with "+" prefixed to it
					case (strpos($scalar, '+') === 0):
						return $this->autoType(substr($scalar, 1));
						break;

					// $scalar is a float or integer with "_" in it
					case (strpos($scalar, '_') !== false):
						return $this->autoType(str_replace('_', '', $scalar));
						break;

					// $scalar is an exponential float
					case (strpos($lower_value, 'e+') !== false):
						return (float)$scalar;
						break;
				}
				break;

			// $scalar is a boolean true equivalent
			case (in_array($lower_value, self::$BOOL_TRUE_SCALARS, true)):
				return true;
				break;

			// $scalar is a boolean false equivalent
			case (in_array($lower_value, self::$BOOL_FALSE_SCALARS, true)):
				return false;
				break;

			// $scalar is a null equivalent
			case (in_array($lower_value, self::$NULL_SCALARS, true)):
				return null;
				break;

			// $scalar is an inline series
			case ((strpos($scalar, '[') === 0) && (strrpos($scalar, ']') === (strlen($scalar) - 1))):
				return $this->getSeries(substr($scalar, 1, -1));
				break;

			// $scalar is a string
			default:
				return $this->stripQuotes($scalar);
				break;
		}
	}


	protected function stripQuotes($string)
	{
		if ((strpos($string, "'") === 0) &&
			(strrpos($string, "'") === (strlen($string) - 1))
		   )
		{
			return $this->getUnescapedString(substr($string, 1, -1));
		}

		// eval double quotes to parse escape sequences (should find better way)
		if ((strpos($string, '"') === 0) &&
			(strrpos($string, '"') === (strlen($string) - 1))
		   )
		{
			eval("\$string = $string;");
		}

		return $string;
	}


	protected function getSeries($string)
	{
		$items = array();

		foreach (explode(', ', trim($string)) as $scalar)
		{
			$items[] = $this->autoType($scalar);
 		}

		return $items;
	}


	protected function getUnescapedString($string)
	{
		return str_replace("''", "'", $string);
	}

}
