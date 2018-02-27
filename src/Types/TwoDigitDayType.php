<?php

namespace Obullo\Router\Types;

use Obullo\Router\Type;

/**
 * Day <dd:day>
 *
 * @copyright Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class TwoDigitDayType extends Type
{
	/**
	 * Regex
	 *
	 * <dd:day>   // before convertion
	 * %s = day //  group name
	 * (?<day>[0-9]{2}) // after convertion
	 * 
	 * @var string
	 */
	protected $regex = '(?<%s>[0-9]{2})';

	/**
	 * Php format
	 * 
	 * @param  number $value 
	 * @return int
	 */
	public function toPhp($value)
	{
		return (int)$value;
	}

	/**
	 * Url format
	 * 
	 * @param mixed $value
	 * @return string
	 */
	public function toUrl($value)
	{
		return sprintf('%02d', $value);
	}
}
