<?php namespace Smartdown\Utils;

class Parser
{
    protected static $instance;
    protected $logger;
    protected $plugins;
    protected $markup;
    protected $typography;

    /**
     * Parser constructor.
     *
     * @param \Michelf\MarkdownExtra $markup
     * @param \michelf\SmartyPants   $typography
     * @param \Craft\PluginsService  $plugins
     * @param Logger                 $logger
     */
    public function __construct($markup, $typography, $plugins, $logger)
    {
        $this->markup = $markup;
        $this->typography = $typography;
        $this->plugins = $plugins;
        $this->logger = $logger;
    }

    /**
     * Returns an instance of the Parser class.
     *
     * @param \Michelf\MarkdownExtra $markup
     * @param \michelf\SmartyPants   $typography
     * @param \Craft\PluginsService  $plugins
     * @param Logger                 $logger
     *
     * @return static
     */
    public static function getInstance($markup, $typography, $plugins, $logger)
    {
        if (! static::$instance) {
            static::$instance = new static(
                $markup, $typography, $plugins, $logger);
        }

        return static::$instance;
    }

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
            $this->logger->logError('Smartdown::parseAll expects a string.');
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
        if (! is_string($source)) {
            $this->logger->logError('Smartdown::parseTypography expects a string.');
            return '';
        }

        $source = $this->callHook('modifySmartdownTypographyInput', $source);
        $source = $this->typography->transform($source);
        $source = $this->callHook('modifySmartdownTypographyOutput', $source);

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
        if (! is_string($source)) {
            $this->logger->logError('Smartdown::parseMarkup expects a string.');
            return '';
        }

        $source = $this->callHook('modifySmartdownMarkupInput', $source);
        $source = $this->markup->transform($source);
        $source = $this->callHook('modifySmartdownMarkupOutput', $source);

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

        foreach ($this->plugins->getPlugins() as $plugin) {
            if (method_exists($plugin, $hook)) {
                $result = $plugin->$hook($source);
            }
        }

        return $result;
    }
}