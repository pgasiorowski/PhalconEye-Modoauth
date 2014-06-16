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

use Phalcon\Session\BagInterface as SessionBag;
use OAuth\Common\Token\TokenInterface;
use OAuth\Common\Storage\TokenStorageInterface;
use OAuth\Common\Storage\Exception\TokenNotFoundException;
use OAuth\Common\Storage\Exception\AuthorizationStateNotFoundException;

/**
 * Stores a token in a PHP session.
 */
class TokenStorage implements TokenStorageInterface
{
    /**
     * @var SessionBag For tokens
     */
    protected $_tokenBag = null;

    /**
     * @var SessionBag For authorization states
     */
    protected $_authBag = null;

    /**
     * Constructor
     *
     * @param SessionBag $bag Session adapter
     */
    public function __construct(SessionBag $tokenBag, SessionBag $authBag)
    {
        $this->_tokenBag = $tokenBag;
        $this->_authBag = $authBag;
    }

    /**
     * {@inheritDoc}
     */
    public function retrieveAccessToken($service)
    {
        if ($this->hasAccessToken($service)) {
            return unserialize($this->_tokenBag->get($service));
        }

        throw new TokenNotFoundException('OAuth Token not found in session');
    }

    /**
     * {@inheritDoc}
     */
    public function storeAccessToken($service, TokenInterface $token)
    {
        $this->_tokenBag->set($service, serialize($token));

        // allow chaining
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hasAccessToken($service)
    {
        return $this->_tokenBag->has($service);
    }

    /**
     * {@inheritDoc}
     */
    public function clearToken($service)
    {
        $this->_tokenBag->remove($service);

        // allow chaining
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function clearAllTokens()
    {
        $this->_tokenBag->destroy();

        // allow chaining
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function storeAuthorizationState($service, $state)
    {
        $this->_authBag->set($service, serialize($state));

        // allow chaining
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function hasAuthorizationState($service)
    {
        return $this->_authBag->has($service);
    }

    /**
     * {@inheritDoc}
     */
    public function retrieveAuthorizationState($service)
    {
        if ($this->hasAuthorizationState($service)) {
            return $this->_authBag->get($service);
        }

        throw new AuthorizationStateNotFoundException('OAuth state not found in session');
    }

    /**
     * {@inheritDoc}
     */
    public function clearAuthorizationState($service)
    {
        $this->_authBag->remove($service);

        // allow chaining
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function clearAllAuthorizationStates()
    {
        $this->_authBag->destroy();

        // allow chaining
        return $this;
    }
}
