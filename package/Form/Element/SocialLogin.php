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

namespace Modoauth\Form\Element;

use Engine\Form\AbstractElement;
use Engine\Behaviour\TranslationBehaviour;
use Engine\Form\ElementInterface;

/**
 * Form element - Social Login Button
 */
class SocialLogin extends AbstractElement implements ElementInterface
{
    use TranslationBehaviour;

    /**
     * {@inheritdoc}
     */
    public function useDefaultLayout()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isIgnored()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedOptions()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultAttributes()
    {
        $url = $this->getDI()->get('url');

        return [
            'href' => $url->get(['for' => 'oauth-redirect','name' => $this->getName()]),
            'class' => 'form-control form-login-with form-login-with-'. $this->getName(),
        ];
    }

    /**
     * Get element html template.
     *
     * @return string
     */
    public function getHtmlTemplate()
    {
        return '<a' . $this->_renderAttributes() . '>%s %s %s</a>';
    }

    /**
     * Get element html template values
     *
     * @return array
     */
    public function getHtmlTemplateValues()
    {
        // todo: translate
        return ['Login with', $this->getName(), $this->getValue()];
    }

    /**
     * Render element.
     *
     * @return string
     * @deprecated
     */
    public function render()
    {
        return vsprintf(
            $this->getHtmlTemplate(),
            $this->getHtmlTemplateValues()
        );
    }
}
