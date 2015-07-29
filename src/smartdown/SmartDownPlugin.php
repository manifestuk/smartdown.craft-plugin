<?php namespace Craft;

class SmartDownPlugin extends BasePlugin
{
    /**
     * Initialises the plugin.
     */
    public function init()
    {
        $this->initializeAutoloader();
    }

    /**
     * Requires the Composer-generated autoloader.
     */
    private function initializeAutoloader()
    {
        require_once __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Returns the plugin name.
     *
     * @return string
     */
    public function getName()
    {
        return 'SmartDown';
    }

    /**
     * Returns the plugin version.
     *
     * @return string
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * Returns the name of the plugin developer.
     *
     * @return string
     */
    public function getDeveloper()
    {
        return 'Experience';
    }

    /**
     * Returns the preferred URL of the plugin developer.
     *
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'https://experiencehq.net';
    }

    /**
     * Registers the Twig extension.
     *
     * @return SmartDownTwigExtension
     */
    public function addTwigExtension()
    {
        Craft::import('plugins.smartdown.twigextensions.SmartDownTwigExtension');

        return new SmartDownTwigExtension();
    }
}
