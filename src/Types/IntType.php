<?php

namespace Obullo\Router\Types;

use Obullo\Router\Type;

/**
 * Integer <int:name>
 *
 * @copyright Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class IntType extends Type
{
	protected $regex = '(?<%s>\d+)';

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
		return sprintf('%d', $value);
	}
}