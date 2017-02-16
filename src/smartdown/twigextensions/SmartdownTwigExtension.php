<?php namespace Craft;

use Twig_Extension;
use Twig_Markup;
use Twig_SimpleFilter;

class SmartdownTwigExtension extends Twig_Extension
{
    /**
     * Returns the Twig extension name.
     *
     * @return string
     */
    public function getName()
    {
        return 'Smartdown';
    }

    /**
     * Returns an associative array of Twig filters provided by the extension.
     *
     * @return array
     */
    public function getFilters()
    {
        $options = ['markup' => true, 'typography' => true];

        return [
            'smartdown' => new Twig_SimpleFilter(
                'smartdown', [$this, 'smartdownFilter'], $options)
        ];
    }

    /**
     * Runs the given string through the filter, and returns the
     * results.
     *
     * Usage:
     * {{ 'Gives us "pretty quotes" and an ellipsis'|smartdown }}
     *
     * @param string $source      The string to parse.
     * @param bool   $markup      Run the string through the markup filter?
     * @param bool   $typography  Run the string through the typography filter?
     *
     * @return string
     */
    public function smartdownFilter($source, $markup = true, $typography = true)
    {
        $markup = ($markup !== false);
        $typography = ($typography !== false);

        return new Twig_Markup(
            $this->parseInput($source, $markup, $typography),
            $this->getTwigCharset()
        );
    }

    /**
     * Runs the given input string through the specified parsers.
     *
     * @param string $source
     * @param bool   $markup
     * @param bool   $typography
     *
     * @return string
     */
    protected function parseInput($source, $markup, $typography)
    {
        $result = $source;

        if ($markup) {
            $result = craft()->smartdown->parseMarkup($result);
        }

        if ($typography) {
            $result = craft()->smartdown->parseTypography($result);
        }

        return $result;
    }

    /**
     * Returns the Twig character set.
     *
     * @return string
     */
    protected function getTwigCharset()
    {
        return craft()->templates->getTwig()->getCharset();
    }
}
