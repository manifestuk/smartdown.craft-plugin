<?php namespace Craft;

use Twig_Extension;
use Twig_Markup;
use Twig_SimpleFilter;

class SmartDownTwigExtension extends Twig_Extension
{
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
        $options = [
            'markup'      => true,
            'typography'  => true,
            'markdown'    => true,      // Deprecated.
            'smartypants' => true,      // Deprecated.
        ];

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
     * {{ 'Gives us "pretty quotes" and an ellipsis' | smartdown }}
     *
     * @param string $source      The string to parse.
     * @param bool   $markup      Run the string through the markup filter?
     * @param bool   $typography  Run the string through the typography filter?
     * @param bool   $markdown    Equivalent of `$markup`. Deprecated.
     * @param bool   $smartypants Equivalent of `$typography`. Deprecated.
     *
     * @return string
     */
    public function smartdownFilter(
        $source,
        $markup = true,
        $typography = true,
        $markdown = true,
        $smartypants = true
    ) {
        $this->logUseOfDeprecatedOptions($markdown, $smartypants);

        $markup = ($markup !== false && $markdown !== false);
        $typography = ($typography !== false && $smartypants !== false);

        return new Twig_Markup(
            $this->parseInput($source, $markup, $typography),
            $this->getTwigCharset()
        );
    }

    /**
     * Logs usage of deprecated filter options.
     *
     * @param bool $markdown    The 'markdown' option passed to the filter.
     * @param bool $smartypants The 'smartypants' option passed to the filter.
     */
    protected function logUseOfDeprecatedOptions(
        $markdown,
        $smartypants
    ) {
        $keyFormat = 'smartdown(%s=false)';
        $messageFormat = 'smartdown(%s=false) has been deprecated. Use smartdown(%s=false) instead.';

        if ($markdown === false) {
            craft()->deprecator->log(
                sprintf($keyFormat, 'markdown'),
                sprintf($messageFormat, 'markdown', 'markup')
            );
        }

        if ($smartypants === false) {
            craft()->deprecator->log(
                sprintf($keyFormat, 'smartypants'),
                sprintf($messageFormat, 'smartypants', 'typography')
            );
        }
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
            $result = craft()->smartDown->parseMarkup($result);
        }

        if ($typography) {
            $result = craft()->smartDown->parseTypography($result);
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

