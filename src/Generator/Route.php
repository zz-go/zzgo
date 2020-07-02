<?php
/**
 * @author Manfred John <zzgo@mave.at>
 */

namespace ZZGo\Generator;


/**
 * Class Route
 *
 * @package ZZGo\Generator
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
     * @var String
     */
    protected $routeName;

    /**
     * Route constructor.
     *
     * @param String $httpMethod
     * @param String $path
     * @param String $controller
     * @param String $controllerMethod
     * @param String $routeName
     */
    public function __construct(String $httpMethod,
                                String $path,
                                String $controller,
                                String $controllerMethod,
                                String $routeName)
    {
        $this->setHttpMethod($httpMethod);
        $this->setPath($path);
        $this->setController($controller);
        $this->setControllerMethod($controllerMethod);
        $this->setRouteName($routeName);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "   Route::{$this->httpMethod}('{$this->path}', [\n"
            . "   'uses' => '{$this->controller}@{$this->controllerMethod}',\n"
            . "   ])->name('{$this->routeName}');\n";
    }

    /**
     * @param String $routeName
     */
    public function setRouteName(string $routeName): void
    {
        $this->routeName = $routeName;
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
