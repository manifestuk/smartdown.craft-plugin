<?php
namespace Craft;

/**
 * SmartDown Craft Twig filter.
 *
 * @author  Stephen Lewis <https://github.com/experience>
 * @package SmartDown
 * @version 0.1.0
 */

class SmartDownPlugin extends BasePlugin
{
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
        return '0.1.0';
    }


    /**
     * Returns the name of the plugin developer.
     *
     * @return string
     */
    public function getDeveloper()
    {
        return 'Stephen Lewis';
    }


    /**
     * Returns the preferred URL of the plugin developer.
     *
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'https://github.com/experience';
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

