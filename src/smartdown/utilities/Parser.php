<?php namespace Experience\SmartDown\Utilities;

use Craft\LogLevel;
use Craft\SmartDownPlugin;
use Michelf\MarkdownExtra;
use Michelf\SmartyPants;

class Parser
{
    /**
     * Runs the given string through all of the available parsers.
     *
     * @param string $source
     *
     * @return string
     */
    public function parseAll($source)
    {
        if (! is_string($source)) {
            SmartDownPlugin::log(
                Craft::t('SmartDown can only parse strings, fool'),
                LogLevel::Warning
            );

            return '';
        }

        return $this->parseTypography($this->parseMarkup($source));
    }

    /**
     * Runs the given string through "typography" parser (SmartyPants).
     *
     * @param string $source
     *
     * @return string
     */
    public function parseTypography($source)
    {
        $source = $this->callHook('modifySmartDownTypographyInput', $source);
        $source = SmartyPants::defaultTransform($source);
        $source = $this->callHook('modifySmartDownTypographyOutput', $source);

        return $source;
    }

    /**
     * Runs the given string through the "markup" parser (MarkdownExtra).
     *
     * @param string $source
     *
     * @return string
     */
    public function parseMarkup($source)
    {
        $source = $this->callHook('modifySmartDownMarkupInput', $source);
        $source = MarkdownExtra::defaultTransform($source);
        $source = $this->callHook('modifySmartDownMarkupOutput', $source);

        return $source;
    }

    /**
     * Calls the specified hook, with the specified "source" text. Differs to
     * the standard Craft PluginsService::call method, in that the results are
     * cumulative. That is, FirstPlugin receives the source string,
     * SecondPlugin receives the source string after it has been parsed by
     * FirstPlugin, and so forth.
     *
     * There are clearly some potential downsides to this, as hook handlers
     * could find themselves competing to parse the string. However, that would
     * still be the case with the standard PluginsService::call method, it's
     * just the results would be a lot more difficult to process.
     *
     * @param $hook
     * @param $source
     *
     * @return mixed
     */
    private function callHook($hook, $source)
    {
        $result = $source;

        foreach (\Craft\craft()->plugins->getPlugins() as $plugin) {
            if (method_exists($plugin, $hook)) {
                $result = $plugin->$hook($source);
            }
        }

        return $result;
    }
}