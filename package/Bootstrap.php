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

namespace Modoauth;

use Engine\Bootstrap as EngineBootstrap;
use Core\Model\Settings;

/**
 * Bootstrap for ModOAuth.
 *
 * @category PhalconEye\Module
 * @package  Module
 */
class Bootstrap extends EngineBootstrap
{
    protected $_moduleName = "ModOAuth";

    /**
     * Register the services.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        /* @var \Phalcon\Loader $loader */
        $di = $this->getDI();
        $loader = $di->get('loader');
        $registry = $di->get('registry');
        $serviceSettings = $registry->oauth = [];

        // Aggregate saved Settings
        foreach (Settings::getValue('modoauth') as $key => $value) {
            list($service, $type) = explode('_', $key, 2);
            if (!empty($value)) {
                $serviceSettings[$service][$type] = $value;
            }
        }

        // Register services
        foreach ($serviceSettings as $serviceName => $params) {
            if (isset($params['key'], $params['secret'])) {
                $registry->oauth[$serviceName] = $params;
            }
        }

        // Register OAuth Namespace
        $loader->registerNamespaces(['OAuth' => $registry->directories->libraries .'Phpoauthlib/OAuth'], true);
    }
}
