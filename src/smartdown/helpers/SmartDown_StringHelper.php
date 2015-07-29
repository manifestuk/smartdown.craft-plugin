<?php
namespace Craft;

/**
 * SmartDown string helper.
 *
 * @author  Stephen Lewis <https://github.com/experience>
 * @package SmartDown
 */

class SmartDown_StringHelper
{
    // Oh Brandon, Y U NO PSR?

    /**
     * Parses a string with Markdown Extra.
     *
     * @param string $str
     *
     * @return string
     */
    public static function parseMarkdown($str)
    {
        if ( ! class_exists('\MarkdownExtra_Parser', false)) {
            require_once craft()->path->getFrameworkPath()
                .'vendors/markdown/markdown.php';
        }

        $parser = new \MarkdownExtra_Parser();
        return $parser->transform($str);
    }


    /**
     * Parses a string with SmartyPants.
     *
     * @param string $str
     *
     * @return string
     */
    public static function parseSmartyPants($str)
    {
        if ( ! class_exists('\SmartyPants_Parser', false)) {
            require_once craft()->path->getPluginsPath()
                .'smartdown/vendor/smartypants/smartypants.php';
        }

        $parser = new \SmartyPants_Parser();
        return $parser->transform($str);
    }
}

