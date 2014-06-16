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

use OAuth\OAuth2\Service\Google as Service;

class Google extends Service implements ServiceInterface
{
    const
        OAUTH_SCOPE_USER_ID = parent::SCOPE_USERINFO_EMAIL;

    /**
     * {@inheritdoc}
     */
    public function getUserEmail()
    {
        $data = json_decode($this->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);

        if (isset($data['verified_email']) && $data['verified_email'] == false) {
            throw new \Exception('Your account is not verified');
        }

        return isset($data['email'])? $data['email'] : null;
    }
}
