<?php namespace Smartdown\Utils;

use Exception;

class ServiceLocator
{
    protected static $instance;
    protected $callables = [];
    protected $variables = [];

    /**
     * Instantiate.
     *
     * @return self
     */
    public static function getInstance()
    {
        if (! isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * Stashes a 'callable' for future use.
     *
     * @param string   $id A UID which identifies the callable.
     * @param callable $callable
     */
    protected function stashCallable($id, Callable $callable)
    {
        $this->callables[$id] = $callable;
    }

    /**
     * Stashes a variable for future use.
     *
     * @param string $id       A UID which identifies the variable.
     * @param object $variable The variable.
     */
    protected function stashVariable($id, $variable)
    {
        $this->variables[$id] = $variable;
    }

    /**
     * Stashes a resource for future use.
     *
     * @param string $id       A UID which identifies the resource.
     * @param object $resource The resource.
     */
    public function stash($id, $resource)
    {
        is_callable($resource)
            ? $this->stashCallable($id, $resource)
            : $this->stashVariable($id, $resource);
    }

    /**
     * Magic method to call a 'callable' resource.
     *
     * @param string $id        The UID of the resource.
     * @param array  $arguments Arguments to pass to the resource.
     *
     * @return mixed
     * @throws Exception
     */
    public function __call($id, array $arguments)
    {
        if (! array_key_exists($id, $this->callables)) {
            throw new Exception("The resource '{$id}' is missing.");
        }

        return call_user_func_array($this->callables[$id], $arguments);
    }

    /**
     * Magic method to retrieve a 'variable' resource.
     *
     * @param string $id The UID of the resource.
     *
     * @return mixed
     * @throws Exception
     */
    public function __get($id)
    {
        if (array_key_exists($id, $this->variables)) {
            return $this->variables[$id];
        }

        if (array_key_exists($id, $this->callables)) {
            return $this->callables[$id]();
        }

        throw new Exception("The resource '{$id}' is missing.");
    }
}
