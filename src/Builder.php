<?php

namespace Obullo\Router;

use Obullo\Router\Route;
use Obullo\Router\RouteCollection;
use Obullo\Router\Exception\BadRouteException;
use Obullo\Router\Exception\UndefinedRouteException;

/**
 * Build route data from configuration file
 *
 * @copyright Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class Builder
{
    protected $collection;
    
    /**
     * Constructor
     *
     * @param RouteCollection $collection collection
     */
    public function __construct(RouteCollection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Build routes
     *
     * @param  array  $routes rotues
     * @return RouteCollection object
     */
    public function build(array $routes) : RouteCollection
    {
        foreach ($routes as $path => $route) {
            if (! is_array($route)) {
                throw new UndefinedRouteException('There is no rule defined in the route configuration file.');
            }
            Self::ValidateRoute($path, $route);
            $handler = $route['handler'];
            $method  = isset($route['method']) ? $route['method'] : 'GET';
            $host = isset($route['host']) ? $route['host'] : null;
            $scheme = isset($route['scheme']) ? $route['scheme'] : array();
            $middleware = isset($route['middleware']) ? $route['middleware'] : array();

            $this->collection->add(new Route($method, $path, $handler))
                ->addHost($host)
                ->addScheme($scheme)
                ->addMiddleware($middleware);
        }
        return $this->collection;
    }

    /**
     * Validate route
     *
     * @param  array  $route route
     * @return void
     */
    protected static function validateRoute(string $path, array $route)
    {
        if (empty($route['handler'])) {
            throw new BadRouteException(
                sprintf(
                    'Route handler is undefined for "%s" path.',
                    htmlspecialchars($path)
                )
            );
        }
    }
}
