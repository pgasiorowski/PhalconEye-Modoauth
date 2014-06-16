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

use OAuth\OAuth2\Service\GitHub as Service;

class GitHub extends Service implements ServiceInterface
{
    const
        OAUTH_SCOPE_USER_ID = parent::SCOPE_USER_EMAIL;

    /**
     * {@inheritdoc}
     */
    public function getUserEmail()
    {
        $data = json_decode($this->request('user/emails'), true);

        return $data? $data[0] : null;
    }
}
