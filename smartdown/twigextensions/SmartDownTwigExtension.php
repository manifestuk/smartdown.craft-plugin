<?php
namespace Craft;

/**
 * SmartDown Twig extension.
 *
 * @author  Stephen Lewis <https://github.com/experience>
 * @package SmartDown
 */

class SmartDownTwigExtension extends \Twig_Extension
{
    /**
     * Constructor.
     *
     * @return void
     */
    public function __construct()
    {
        if ( ! class_exists('\Craft\SmartDown_StringHelper', false)) {
            require_once craft()->path->getPluginsPath()
                .'smartdown/helpers/SmartDown_StringHelper.php';
        }
    }


    /**
     * Returns the Twig extension name.
     *
     * @return string
     */
    public function getName()
    {
        return 'SmartDown';
    }


    /**
     * Returns an associative array of Twig filters provided by the extension.
     *
     * @return array
     */
    public function getFilters()
    {
        $options = array(
            'markdown'    => true,
            'smartypants' => true,
        );

        return array(
            'smartdown' => new \Twig_Filter_Method(
                $this, 'smartdownFilter', $options),
        );
    }


    /**
     * Runs the given string through the filter, and returns the 
     * results.
     *
     * Usage:
     * {{ 'Gives us "pretty quotes" and an ellipsis' | smartdown }}
     *
     * @param string $str The string to parse.
     *
     * @return string
     */
    public function smartdownFilter($str, $markdown = true, $smartypants = true)
    {
        $html = $str;

        if ($markdown !== false) {
            $html = SmartDown_StringHelper::parseMarkdown($html);
        }

        if ($smartypants !== false) {
            $html = SmartDown_StringHelper::parseSmartyPants($html);
        }

        $charset = craft()->templates->getTwig()->getCharset();
        return new \Twig_Markup($html, $charset);
    }
}

