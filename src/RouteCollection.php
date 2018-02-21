<?php

namespace Obullo\Router;

use Obullo\Router\{
	Exception\BadRouteException,
	Exception\UndefinedTypeException
};
use ArrayAccess;
use IteratorAggregate;
use Countable;

/**
 * Route collection
 *
 * @copyright Obullo
 * @license   http://opensource.org/licenses/MIT MIT license
 */
class RouteCollection implements IteratorAggregate, Countable
{
    protected $rules = array();
    protected $types = array();
    protected $pipes = array();
    protected $routes = array();

	/**
	 * Constructor
	 * 
	 * @param ArrayAccess $config config
	 */
	public function __construct(ArrayAccess $config)
	{
		foreach ($config['types'] as $object) {
            $type = $object->getType();
            $tag  = $object->getTag();
            $this->rules[$type] = $object->convert()->getValue();
            $this->types[$tag]  = $object;
        }
	}

	/**
	 * Add route to collection
	 * 
	 * @param mixed $nameOrPipe route name or PipeInterface
	 * @param RouteInterface $route 
	 */
    public function add($nameOrPipe, $route = null)
    {
    	if ($nameOrPipe instanceof PipeInterface) {
            $pipe = $nameOrPipe;
            foreach ($pipe->getRoutes() as $name => $route) {
                $this->add($name, $route);
            }
    		$this->pipes[] = $pipe;
    		return;
    	}
    	if (! $route instanceof RouteInterface) {
    		throw new BadRouteException('Route parameter must be object of RouteInterface.');	
    	}
        $name = $nameOrPipe;
		$unformatted = $route->getPattern();
        $this->validateUnformattedPattern($unformatted);
        $formatted = $this->formatPattern($unformatted);
        $route->setName($name);
        $route->setPattern($formatted);
        $this->routes[$name] = $route;
    }

    /**
     * Returns to number of pipes
     * 
     * @return int
     */
    public function pipeCount() : int
    {
    	return count($this->pipes);
    }

    /**
     * Returns to all pipes
     * 
     * @return array
     */
    public function pipeAll() : array
    {
    	return $this->pipes;
    }

    /**
     * Returns to number of routes
     * 
     * @return int
     */
    public function count() : int
    {
    	return count($this->routes);
    }

    /**
     * Returns to all toutes
     * 
     * @return array
     */
    public function all() : array
    {
    	return $this->routes;
    }

    /**
     * Gets the current RouteCollection as an Iterator that includes all routes.
     *
     * It implements IteratorAggregate.
     *
     * @return ArrayIterator object
     */
    public function getIterator()
    {
        return new ArrayIterator($this->routes);
    }

    /**
     * Returns types
     * 
     * @return array
     */
    public function getTypes()
    {
        return $this->types;
    }

    /**
     * Returns to selected route
     * 
     * @param  string $name name
     * @return boolean
     */
    public function get(string $name)
    {
    	return isset($this->routes[$name]) ? $this->routes[$name] : false;
    }

    /**
     * Remove route
     * 
     * @param  string $name name
     * @return void
     */
    public function remove(string $name)
    {
    	unset($this->routes[$name]);
    }

    /**
     * Format pattern
     * 
     * @param  string $unformatted string
     * @return string
     */
    protected function formatPattern(string $unformatted)
    {
    	return str_replace(
            array_keys($this->rules),
            array_values($this->rules),
            $unformatted
        );
    }

    /**
     * Validate route types
     * 
     * @param  string $pattern types
     * @return void
     */
    protected function validateUnformattedPattern(string $pattern)
    {
        foreach (explode('/', $pattern) as $value) {
            if ((substr($value, 0, 1) == '<' && substr($value, -1) == '>') && ! array_key_exists($value, $this->rules))  {
                throw new UndefinedTypeException(
                    sprintf(
                        'The route type %s you used is undefined.',
                        $value
                    )
                );
            }
        }
    }

}