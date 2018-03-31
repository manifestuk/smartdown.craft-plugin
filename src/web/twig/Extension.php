<?php

namespace experience\smartdown\web\twig;

use Craft;
use experience\smartdown\Smartdown;
use Twig_Extension;
use Twig_Markup;
use Twig_SimpleFilter;

class Extension extends Twig_Extension
{
    protected static $plugin;

    public function __construct()
    {
        if (is_null(static::$plugin)) {
            static::$plugin = Smartdown::getInstance();
        }
    }

    /**
     * Return the Twig extension name.
     *
     * @return string
     */
    public function getName()
    {
        return 'smartdown';
    }

    /**
     * Return an associative array of Twig filters provided by the extension.
     *
     * @return Twig_SimpleFilter[]
     */
    public function getFilters(): array
    {
        return [
            'smartdown' => new Twig_SimpleFilter(
                'smartdown',
                [$this, 'smartdownFilter'],
                ['markup' => true, 'typography' => true]
            ),
        ];
    }

    /**
     * Run the given string through the filter, and return the result.
     *
     * Usage:
     * {{ 'Gives us "pretty quotes" and an ellipsis'|smartdown }}
     *
     * @param string $source     The string to parse.
     * @param bool   $markup     Run the string through the markup filter?
     * @param bool   $typography Run the string through the typography filter?
     *
     * @return string
     */
    public function smartdownFilter(
        string $source,
        bool $markup = true,
        bool $typography = true
    ): string {
        $markup = ($markup !== false);
        $typography = ($typography !== false);

        return new Twig_Markup(
            $this->parseInput($source, $markup, $typography),
            $this->getTwigCharset()
        );
    }

    /**
     * Run the given input string through the specified parsers.
     *
     * @param string $input
     * @param bool   $markup
     * @param bool   $typography
     *
     * @return string
     */
    protected function parseInput(
        string $input,
        bool $markup,
        bool $typography
    ): string {
        $output = $input;

        if ($markup) {
            $output = static::$plugin->smartdown->parseMarkup($output);
        }

        if ($typography) {
            $output = static::$plugin->smartdown->parseTypography($output);
        }

        return $output;
    }

    /**
     * Return the Twig character set.
     *
     * @return string
     */
    protected function getTwigCharset(): string
    {
        return Craft::$app->view->getTwig()->getCharset();
    }
}
