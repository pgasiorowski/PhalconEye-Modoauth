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

namespace Modoauth\Form;

use Core\Form\CoreForm;

/**
 * Config form.
 */
class ConfigForm extends CoreForm
{
    /**
     * Initialize the config form
     *
     * @return void
     */
    public function initialize()
    {
        $this->setTitle('OAuth Services');

        $this->addContentFieldSet('GitHub')
            ->addText('github_key', 'Client Key')
            ->addText('github_secret', 'Client Secret');

        $this->addContentFieldSet('Facebook')
            ->addText('facebook_key', 'Client Key')
            ->addText('facebook_secret', 'Client Secret');

        $this->addContentFieldSet('Google')
            ->addText('google_key', 'Client Key')
            ->addText('google_secret', 'Client Secret');

        $this->addFooterFieldSet()
            ->addButton('save');
    }
}
