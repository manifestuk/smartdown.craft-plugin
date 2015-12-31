<?php namespace Craft;

use Michelf\MarkdownExtra;
use Michelf\SmartyPants;
use Smartdown\Utils\Logger;
use Smartdown\Utils\Parser;

class SmartdownPlugin extends BasePlugin
{
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
                new Logger()
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
        return 'Smartdown';
    }

    /**
     * Returns the plugin description.
     *
     * @return string
     */
    public function getDescription()
    {
        return Craft::t("Smarter Markdown for Craft.");
    }

    /**
     * Returns the plugin’s version number.
     *
     * @return string The plugin’s version number.
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * Returns the plugin developer’s name.
     *
     * @return string The plugin developer’s name.
     */
    public function getDeveloper()
    {
        return 'Experience';
    }

    /**
     * Returns the plugin developer’s URL.
     *
     * @return string The plugin developer’s URL.
     */
    public function getDeveloperUrl()
    {
        return 'https://experiencehq.net';
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