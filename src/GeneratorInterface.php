<?php

namespace Obullo\Router;

/**
 * Generator interface
 *
 * @copyright Obullo
 * @license   https://opensource.org/licenses/BSD-3-Clause
 */
interface GeneratorInterface
{
    /**
     * Generate url
     *
     * @param  string $name   path
     * @param  array  $args   arguments
     * @param  string $locale locale
     *
     * @return string|throw exception
     */
    public function generate(string $name, $args = array(), $locale = null);
}
