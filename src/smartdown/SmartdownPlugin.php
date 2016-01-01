<?php namespace Craft;

use Michelf\MarkdownExtra;
use Michelf\SmartyPants;
use Smartdown\Utils\Logger as SmartdownLogger;
use Smartdown\Utils\Parser;

class SmartdownPlugin extends BasePlugin
{
    private $config;

    /**
     * SmartdownPlugin constructor. Loads the config file containing the plugin
     * information.
     *
     * We can't do this from the `init` method, because Craft calls `getName`,
     * `getVersion`, and so forth before running the `init` method.
     */
    public function __construct()
    {
        $this->loadConfig();
    }

    /**
     * Loads the JSON config file, and stores it in the config variable.
     *
     * @TODO Implement some error checking.
     */
    private function loadConfig()
    {
        $json = file_get_contents(__DIR__ . '/config.json');
        $this->config = JsonHelper::decode($json, false);
    }

    /**
     * Initialises the plugin.
     */
    public function init()
    {
        parent::init();
        $this->initializeAutoloader();
        $this->initializeServiceProvider();
        $this->populateServiceProvider();
    }

    /**
     * Initialises the autoloader.
     */
    private function initializeAutoloader()
    {
        require_once __DIR__ . '/vendor/autoload.php';
    }

    /**
     * Initialises the service provider.
     */
    private function initializeServiceProvider()
    {
        require_once __DIR__ . '/smartdown.php';
    }

    /**
     * Populates the service provider.
     */
    private function populateServiceProvider()
    {
        $this->stashApplication();
        $this->stashTranslator();
        $this->stashUtilities();
    }

    /**
     * Stashes a reference to the Craft application in the service locator.
     */
    private function stashApplication()
    {
        smartdown()->stash('app', function () {
            return Craft::app();
        });
    }

    /**
     * Stashes a "translate" helper in the service locator.
     */
    private function stashTranslator()
    {
        smartdown()->stash('translate',
            function ($message, array $variables = []) {
                return call_user_func_array(
                    ['\Craft\Craft', 't'],
                    func_get_args()
                );
            }
        );
    }

    /**
     * Stashes the plugin "utility" classes in the service locator.
     */
    private function stashUtilities()
    {
        // Stashes an instance of the Parser class in the service locator.
        smartdown()->stash('parser', function () {
            return Parser::getInstance(
                new MarkdownExtra(),
                new SmartyPants(),
                smartdown()->app->plugins,
                new SmartdownLogger()
            );
        });
    }

    /**
     * Returns the plugin name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->config->name;
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
     * Registers the Twig extension.
     *
     * @return SmartdownTwigExtension
     */
    public function addTwigExtension()
    {
        return new SmartdownTwigExtension();
    }
}