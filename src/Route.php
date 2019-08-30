<?php
/**
 * @copyright LOOP.
 * @author Manfred John <manfred.john@agentur-loop.com>
 */

namespace ZZGo;


/**
 * Class Migration
 *
 * @package ZZGo\Migration
 */
class Route
{
    /**
     * @var String
     */
    protected $httpMethod;

    /**
     * @var String
     */
    protected $path;

    /**
     * @var String
     */
    protected $controller;

    /**
     * @var String
     */
    protected $controllerMethod;


    /**
     * Route constructor.
     *
     * @param String $httpMethod
     * @param String $path
     * @param String $controller
     * @param String $controllerMethod
     */
    public function __construct(String $httpMethod,
                                String $path,
                                String $controller,
                                String $controllerMethod)
    {
        $this->setHttpMethod($httpMethod);
        $this->setPath($path);
        $this->setController($controller);
        $this->setControllerMethod($controllerMethod);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "   Route::{$this->httpMethod}('{$this->path}', [\n"
            . "   'uses' => '{$this->controller}@{$this->controllerMethod}',\n"
            . "   ]);\n";
    }

    /**
     * @param String $httpMethod
     */
    public function setHttpMethod(String $httpMethod): void
    {
        $this->httpMethod = $httpMethod;
    }

    /**
     * @param String $path
     */
    public function setPath(String $path): void
    {
        $this->path = $path;
    }

    /**
     * @param String $controller
     */
    public function setController(String $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @param String $controllerMethod
     */
    public function setControllerMethod(String $controllerMethod): void
    {
        $this->controllerMethod = $controllerMethod;
    }
}