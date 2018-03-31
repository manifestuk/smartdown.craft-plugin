<?php

namespace experience\smartdown;

use Craft;
use craft\base\Plugin;
use experience\smartdown\web\twig\Extension;

class Smartdown extends Plugin
{
    /**
     * Initialise the plugin.
     */
    public function init()
    {
        parent::init();

        if (Craft::$app->request->getIsSiteRequest()) {
            Craft::$app->view->registerTwigExtension(new Extension);
        }
    }
}
