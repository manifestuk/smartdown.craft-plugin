<?php namespace Experience\Smartdown\App\ServiceProviders;

use Craft\WebApp;
use League\Container\ContainerInterface;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Experience\Smartdown\App\Utilities\Logger;
use Experience\Smartdown\App\Utilities\Parser;
use Michelf\MarkdownExtra;
use Michelf\SmartyPants;

class PluginServiceProvider extends AbstractServiceProvider
{
    /**
     * @var array
     */
    protected $provides = ['Logger', 'Parser'];

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var WebApp
     */
    protected $craft;

    /**
     * Constructor.
     *
     * @param WebApp $craft
     */
    public function __construct(WebApp $craft)
    {
        $this->craft = $craft;
    }

    /**
     * Registers items with the container.
     */
    public function register()
    {
        $this->initializeLogger();
        $this->initializeParser();
    }

    /**
     * Initialises the logger.
     */
    protected function initializeLogger()
    {
        $this->container->add('Logger', new Logger);
    }

    /**
     * Initialises the parser. MUST be initialised AFTER the Logger.
     */
    protected function initializeParser()
    {
        $this->container->add('Parser', new Parser(
            new MarkdownExtra(),
            new SmartyPants(),
            $this->craft->plugins,
            $this->container->get('Logger')
        ));
    }
}
