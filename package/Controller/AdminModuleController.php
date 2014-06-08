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

namespace Modoauth\Controller;

use Core\Controller\AbstractAdminController;
use Core\Model\Settings;
use Modoauth\Form\ConfigForm;

/**
 * Admin Config controller.
 *
 * @RoutePrefix("/admin/module/modoauth")
 */
class AdminModuleController extends AbstractAdminController
{

    /**
     * Index action.
     *
     * @param string $module Module name
     *
     * @return mixed
     *
     * @Route("/", methods={"GET", "POST"})
     */
    public function indexAction()
    {
        $this->view->form = $form = new ConfigForm;

        if (!$this->request->isPost()) {
            $form->setValues(Settings::getValue('modoauth'));
            return;
        }

        if (!$form->isValid()) {
            return;
        }

        foreach ($form->getValues() as $key => $value) {
            Settings::setValue('modoauth', $key, $value);
        }

        $this->flash->success('Settings saved!');
    }
}
