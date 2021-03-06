<?php

namespace Obullo\Router\Matcher;

use Obullo\Router\RouteInterface;

/**
 * Route match
 *
 * @copyright Obullo
 * @license   https://opensource.org/licenses/BSD-3-Clause
 */
class RouteMatcher extends AbstractMatcher
{
    protected $route;
    protected $arguments = array();

    /**
     * Constructor
     *
     * @param RouteInterface $route route
     */
    public function __construct(RouteInterface $route)
    {
        $this->route = $route;
    }

    /**
     * Returns to true if route matched with path otherwise false
     *
     * @param  string $path path
     * @return boolean
     */
    public function matchPath(string $path) : bool
    {
        $pattern = $this->route->getPath();

        $arguments = array();
        if ($path == $pattern or preg_match('#^'.$pattern.'$#', $path, $arguments)) {
            array_shift($arguments);
            $this->arguments = $arguments;
            return true;
        }
        return false;
    }

    /**
     * Returns to matched route arguments
     *
     * @return array
     */
    public function getArguments() : array
    {
        return $this->arguments;
    }
}
