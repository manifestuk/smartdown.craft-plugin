<?php namespace Craft;

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
        // Returns an instance of the Craft application.
        smartdown()->stash('app', function () {
            return Craft::app();
        });

        // Wrapper for the Craft::t static method.
        smartdown()->stash('translate', function ($message, array $variables = []) {
            return call_user_func_array(
                ['\Craft\Craft', 't'],
                func_get_args()
            );
        });

        // Writes the given error message to the plugin log file.
        smartdown()->stash('logError', function ($message) {
            SmartdownPlugin::log($message, LogLevel::Error);
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