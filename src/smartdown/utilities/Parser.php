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
     * Runs the given string through the "markup" parser (MarkdownExtra).
     *
     * @param string $source
     *
     * @return string
     */
    public function parseMarkup($source)
    {
        return MarkdownExtra::defaultTransform($source);
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
        return SmartyPants::defaultTransform($source);
    }
}