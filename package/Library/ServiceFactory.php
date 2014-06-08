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

namespace Modoauth\Library;

use Phalcon\Session\Bag;
use Engine\Behaviour\DIBehaviour;
use OAuth\Common\Http\Client\StreamClient;
use OAuth\Common\Consumer\Credentials;
use Modoauth\Library\Service\ServiceInterface;

class ServiceFactory
{
    const
        /**
         * Session bag namespace for tokens
         */
        STORAGE_BAG_TOKEN_NS = 'oauth_token',

        /**
         * Session bag namespace for authorization states
         */
        STORAGE_BAG_AUTH_NS = 'oauth_token';

    use DIBehaviour {
        DIBehaviour::__construct as protected __DIConstruct;
    }

    /**
     * Initialize OAuth service
     *
     * @param string $serviceName Service Name
     * @param Scope  $scopes      Instance
     *
     * @return ServiceInterface|null
     */
    public function factory($serviceName, Scope $scopes)
    {
        /**
         * @var \Phalcon\Http\Request $request
         * @var \Phalcon\Mvc\Url $url
         */
        $di = $this->getDI();
        $url = $di->get('url');
        $request = $di->get('app')->request;
        $registry = $di->get('registry');
        $absoluteUrl = $request->getScheme() .'://'. $request->getHttpHost();

        if (!isset($registry->oauth[$serviceName])) {
            return null;
        }

        // OAuth request credentials
        $credentials = new Credentials(
            $registry->oauth[$serviceName]['key'],
            $registry->oauth[$serviceName]['secret'],
            $absoluteUrl . $url->get(['for' => 'oauth-callback', 'name' => $serviceName])
        );

        // Session bags for tokens and authorization states
        $tokenStorageBag = new Bag(self::STORAGE_BAG_TOKEN_NS);
        $tokenAuthBag = new Bag(self::STORAGE_BAG_AUTH_NS);

        $className = $this->getFullyQualifiedServiceClass($serviceName);

        return new $className(
            $credentials,
            new StreamClient,
            new TokenStorage($tokenStorageBag, $tokenAuthBag),
            $scopes->dumpFromService($className)
        );
    }

    /**
     * Get fully qualified class for given service
     *
     * @param string $service Service name
     *
     * @return string
     */
    protected function getFullyQualifiedServiceClass($service)
    {
        return __NAMESPACE__ . '\\Service\\' . ucfirst($service);
    }
}
