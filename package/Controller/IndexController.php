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

use Core\Controller\AbstractController;
use Modoauth\Lib\Scope;
use Modoauth\Lib\ServiceFactory;
use Phalcon\Db\Column;
use Phalcon\Mvc\Dispatcher\Exception;
use User\Form\Auth\Login as LoginForm;
use User\Model\Role;
use User\Model\User;

/**
 * Index controller.
 *
 * @category PhalconEye\Module
 * @package  Controller
 */
class IndexController extends AbstractController
{
    /**
     * Module index action.
     *
     * @Route("/login", methods={"GET"}, name="login")
     */
    public function indexAction()
    {
        if (User::getViewer()->id) {
            return $this->response->redirect();
        }

        // Render header and footer.
        $this->renderParts();

        $form = $this->view->form = new LoginForm;
        $fieldset = $form->addContentFieldSet()
            ->setAttribute('id', 'form_oauth')
            ->setAttribute('class', 'form_footer');

        foreach ($this->registry->oauth as $name => $service) {
            // todo: add custom buttons or partial
            $fieldset->addButtonLink(
                $name,
                // todo: translate
                'Login with '. $name,
                ['for' => 'oauth-redirect','name' => $name]
            );
        }
        return null;
    }

    /**
     * Redirect to appropriate service
     *
     * @return \Phalcon\Http\ResponseInterface
     * @throw Exception if not authorized service is reqested
     *
     * @Route("/oauth/redirect/{name:[a-zA-Z0-9_-]+}", methods={"GET"}, name="oauth-redirect")
     */
    public function redirectAction($name)
    {
        if (User::getViewer()->id) {
            return $this->response->redirect();
        }

        $scope = new Scope([Scope::USER_ID]);
        $factory = new ServiceFactory;
        $service = $factory->factory($name, $scope);

        if ($service == null) {
            throw new Exception;
        }

        return $this->response->redirect($service->getAuthorizationUri(), true);
    }

    /**
     * Handle OAuth callback
     *
     * @return \Phalcon\Http\ResponseInterface
     * @throw Exception if not authorized service is called
     *
     * @Route("/oauth/callback/{name:[a-zA-Z0-9_-]+}", methods={"GET"}, name="oauth-callback")
     */
    public function callbackAction($name)
    {
        if (User::getViewer()->id) {
            return $this->response->redirect();
        }

        $scope = new Scope([Scope::USER_ID]);
        $factory = new ServiceFactory;

        // Service not authorized
        if (!$service = $factory->factory($name, $scope)) {
            throw new Exception;
        }

        if (!$code = $this->request->get('code')) {
            $this->flashSession->error('Missing authorization code');
        } else {
            try {

                // A callback request from server to get the token
                $service->requestAccessToken($code);

                if (!$userEmail = $service->getUserEmail()) {
                    throw new \Exception('OAuth did not give us your details!');
                }

                if ($this->authenticateEmail($userEmail)) {
                    return $this->response->redirect();
                }

            } catch (\Exception $e) {
               $this->flashSession->error($e->getMessage());
            }
        }

        return $this->response->redirect(['for' => 'login']);
    }


    /**
     * Login or register a new account by email
     *
     * @param string $email Email address returned by OAuth Provider
     *
     * @return bool
     */
    private function authenticateEmail($email)
    {
        $user = User::findFirst(
            [
                "email = ?0 AND username = ?0",
                "bind" => [$email],
                "bindTypes" => [Column::BIND_PARAM_STR]
            ]
        );

        if (!$user) {

            // Predict failed validation
            $userCount = User::count(
                [
                    "email = ?0 OR username = ?0",
                    "bind" => [$email],
                    "bindTypes" => [Column::BIND_PARAM_STR]
                ]
            );

            if ($userCount) {
                $this->flashSession->error("Another account with email $email is already registered");
            }

            $user = new User;
            $user->role_id = Role::getDefaultRole()->id;
            $user->username = $email;
            // Only way to login with password would be to reset password
            $user->password = $this->security->getSaltBytes();
            $user->email = $email;

            if (!$user->save()) {
                foreach ($user->getMessages() as $message) {
                    $this->flashSession->error($message);
                }
                return false;
            }
        }

        return $this->core->auth()->authenticate($user->id);
    }
}
