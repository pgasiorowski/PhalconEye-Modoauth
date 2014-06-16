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

namespace Modoauth\Library\Service;

use OAuth\OAuth2\Service\Facebook as Service;

class Facebook extends Service implements ServiceInterface
{
    const
        OAUTH_SCOPE_USER_ID = parent::SCOPE_EMAIL;

    /**
     * {@inheritdoc}
     */
    public function getUserEmail()
    {
        $data = json_decode($this->request('/me'), true);

        return isset($data['email'])? $data['email'] : null;
    }
}
