<?php
/*
  +------------------------------------------------------------------------+
  | OAuth Module for PhalconEye CMS                                        |
  +------------------------------------------------------------------------+
  | Copyright (c) 2014 Piotr Gasiorowski (p.gasiorowski@vipserv.org)       |
  | License: MIT                                                           |
  +------------------------------------------------------------------------+
  | Author: Piotr Gasiorowski <p.gasiorowski@vipserv.org>                  |
  +------------------------------------------------------------------------+
*/

namespace Modoauth\Lib;

class Scope implements \IteratorAggregate
{
    const
        USER_ID = 'USER_ID';

    /**
     * @var array List of available scopes
     */
    protected $_scopes = [
        'USER_ID'
    ];

    /**
     * @var array List of selected scopes
     */
    protected $_selection = [];

    /**
     * Construct and select some scopes
     *
     * @param array $scopes List of scopes
     */
    public function __construct(array $scopes = [])
    {
        foreach($scopes as $name) {
            $this->selectScope($name);
        }
    }

    /**
     * Select scope
     *
     * @param string $name Scope ID
     */
    public function selectScope($name)
    {
        if (isset($this->_scopes[$name])) {
            $this->_selection[] = $name;
        }
    }

    /**
     * Get list of available scopes
     *
     * @return array
     */
    public function getAvailableScopes()
    {
        return $this->_scopes;
    }

    /**
     * Implements IteratorAggregate
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_selection);
    }

    /**
     * Get service scopes
     *
     * @param string $service Service's fully-qualified class name
     *
     * @return array
     */
    public function dumpFromService($service)
    {
        $reflector = new \ReflectionClass($service);
        $constants = $reflector->getConstants();

        $authorizeScopes = [];

        foreach ($this->_scopes as $scope) {
            if (!isset($constants['OAUTH_SCOPE_'. $scope])) {
                throw new \Exception("Scope $scope does not exist");
            }
            $authorizeScopes[] = $constants['OAUTH_SCOPE_'. $scope];
        }

        return $authorizeScopes;
    }
}
