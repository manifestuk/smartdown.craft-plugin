<?php namespace Experience\Smartdown\Tests\App\Utilities;

use Mockery as m;
use Experience\Smartdown\Tests\BaseTest;
use Experience\Smartdown\App\Utilities\Parser;
use Michelf\MarkdownExtra;
use Michelf\SmartyPants;

/**
 * Simple "integration" tests, to ensure that Markdown Extra and SmartyPants are
 * being called correctly.
 *
 * @package Experience\Smartdown\Tests\App\Utilities
 */
class ParserIntegrationTest extends BaseTest
{
    /**
     * @var \Experience\Smartdown\App\Utilities\Logger;
     */
    protected $mockLogger;

    /**
     * @var Parser
     */
    protected $testSubject;

    /**
     * @var \Craft\PluginsService
     */
    protected $mockPluginsService;

    /**
     * Sets the stage before each test.
     */
    public function setUp()
    {
        $this->mockLogger = m::mock('\Experience\Smartdown\App\Utilities\Logger');
        $this->mockPluginsService = m::mock('\Craft\PluginsService');

        $this->testSubject = new Parser(
            new MarkdownExtra(),
            new SmartyPants(),
            $this->mockPluginsService,
            $this->mockLogger
        );
    }

    public function testItParsesMarkup()
    {
        $input = 'This is _italic_';
        $expected = '<p>This is <em>italic</em></p>';

        $this->mockNoMarkupHandlers();

        $this->assertEquals(
            $expected,
            trim($this->testSubject->parseMarkup($input))
        );
    }

    /**
     * Mocks the behaviour of no markup hook handlers.
     */
    protected function mockNoMarkupHandlers()
    {
        $this->mockNoHandlers('modifySmartdownMarkupInput');
        $this->mockNoHandlers('modifySmartdownMarkupOutput');
    }

    /**
     * Mocks the behaviour of no handlers for the specified hook.
     *
     * @param string $hook
     */
    protected function mockNoHandlers($hook)
    {
        $this->mockPluginsService->shouldReceive('call')
            ->zeroOrMoreTimes()
            ->with($hook, m::any())
            ->andReturn(null);
    }

    public function testItParsesTypography()
    {
        $input = 'This is an ellipsis...';
        $expected = 'This is an ellipsis&#8230;';

        $this->mockNoTypographyHandlers();

        $this->assertEquals(
            $expected,
            $this->testSubject->parseTypography($input)
        );
    }

    /**
     * Mocks the behaviour of no typography hook handlers.
     */
    protected function mockNoTypographyHandlers()
    {
        $this->mockNoHandlers('modifySmartdownTypographyInput');
        $this->mockNoHandlers('modifySmartdownTypographyOutput');
    }

    public function testItParsesMarkupAndTypographyTogether()
    {
        $input = 'This is _italic_...';
        $expected = '<p>This is <em>italic</em>&#8230;</p>';

        $this->mockNoMarkupHandlers();
        $this->mockNoTypographyHandlers();

        $this->assertEquals(
            $expected,
            trim($this->testSubject->parseAll($input))
        );
    }
}
