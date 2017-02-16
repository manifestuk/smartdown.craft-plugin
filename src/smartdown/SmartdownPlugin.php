<?php namespace Craft;

use League\Container\Container;
use Experience\Smartdown\App\Helpers\ConfigHelper;
use Experience\Smartdown\App\ServiceProviders\PluginServiceProvider;

class SmartdownPlugin extends BasePlugin
{
    /**
     * @var \League\Container\Container;
     */
    public static $container;

    /**
     * @param object
     */
    protected $config;

    /**
     * Constructor. Loads the config file containing the plugin information.
     *
     * We can't do this from the `init` method, because Craft calls `getName`,
     * `getVersion`, and so forth before running the `init` method.
     */
    public function __construct()
    {
        $this->initializeAutoloader();
        $this->initializeContainer();
        $this->loadConfig();
    }

    /**
     * Initialises the autoloader.
     */
    private function initializeAutoloader()
    {
        require_once __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialises the dependency-injection container.
     */
    private function initializeContainer()
    {
        static::$container = new Container();
        static::$container->addServiceProvider(
            new PluginServiceProvider(craft())
        );
    }

    /**
     * Loads the plugin configuration details from the config file.
     */
    private function loadConfig()
    {
        $path = __DIR__ . '/config.json';
        $this->config = (new ConfigHelper())->getConfig($path);
    }

    /**
     * Returns the plugin name.
     *
     * @return string
     */
    public function getName()
    {
        return Craft::t($this->config->name);
    }

    /**
     * Returns the plugin description.
     *
     * @return string
     */
    public function getDescription()
    {
        return Craft::t($this->config->description);
    }

    /**
     * Returns the plugin’s version number.
     *
     * @return string The plugin’s version number.
     */
    public function getVersion()
    {
        return $this->config->version;
    }

    /**
     * Returns the plugin developer’s name.
     *
     * @return string The plugin developer’s name.
     */
    public function getDeveloper()
    {
        return $this->config->developer;
    }

    /**
     * Returns the plugin developer’s URL.
     *
     * @return string The plugin developer’s URL.
     */
    public function getDeveloperUrl()
    {
        return $this->config->developerUrl;
    }

    /**
     * Returns the documentation URL.
     *
     * @return string
     */
    public function getDocumentationUrl()
    {
        return $this->config->documentationUrl;
    }

    /**
     * Returns the "releases" URL.
     *
     * @return string
     */
    public function getReleaseFeedUrl()
    {
        return $this->config->releasesFeedUrl;
    }

    /**
     * Returns a faux schema version, so Craft doesn't attempt to run database
     * updates when the plugin version changes.
     *
     * @return string
     */
    public function getSchemaVersion()
    {
        return '0.0.0';
    }

    /**
     * Returns a boolean indicating whether the plugin has settings.
     *
     * @return bool
     */
    public function hasSettings()
    {
        return false;
    }

    /**
     * Returns a boolean indicating whether the plugin has it's own control
     * panel section.
     *
     * @return bool
     */
    public function hasCpSection()
    {
        return false;
    }

    /**
     * Registers the Twig extension.
     *
     * @return SmartdownTwigExtension
     */
    public function addTwigExtension()
    {
        return new SmartdownTwigExtension();
    }
}
