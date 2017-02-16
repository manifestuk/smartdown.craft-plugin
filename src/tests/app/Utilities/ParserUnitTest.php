<?php namespace Experience\Smartdown\Tests\App\Utilities;

use Mockery as m;
use Experience\Smartdown\Tests\BaseTest;
use Experience\Smartdown\App\Utilities\Parser;

class ParserTest extends BaseTest
{
    /**
     * @var \Experience\Smartdown\App\Utilities\Logger;
     */
    protected $mockLogger;

    /**
     * @var \Michelf\MarkdownExtra
     */
    protected $mockMarkup;

    /**
     * @var Parser
     */
    protected $testSubject;

    /**
     * @var \Craft\PluginsService
     */
    protected $mockPluginsService;

    /**
     * @var \Michelf\SmartyPants
     */
    protected $mockTypography;

    /**
     * Sets the stage before each test.
     */
    public function setUp()
    {
        $this->mockLogger = m::mock('\Experience\Smartdown\App\Utilities\Logger');
        $this->mockMarkup = m::mock('\Michelf\MarkdownExtra');
        $this->mockPluginsService = m::mock('\Craft\PluginsService');
        $this->mockTypography = m::mock('\Michelf\SmartyPants');

        $this->testSubject = new Parser(
            $this->mockMarkup,
            $this->mockTypography,
            $this->mockPluginsService,
            $this->mockLogger
        );
    }

    public function testItParsesMarkup()
    {
        $input = 'Input';
        $output = 'Output';

        $this->mockNoMarkupHandlers();

        $this->mockMarkup->shouldReceive('transform')
            ->once()->with($input)
            ->andReturn($output);

        $this->assertEquals($output, $this->testSubject->parseMarkup($input));
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
            ->once()->with($hook, m::any())
            ->andReturnUsing(function ($h, $v) {
                return $v;
            });
    }

    public function testItParsesTypography()
    {
        $input = 'Input';
        $output = 'Output';

        $this->mockNoTypographyHandlers();

        $this->mockTypography->shouldReceive('transform')
            ->once()->with($input)
            ->andReturn($output);

        $this->assertEquals(
            $output,
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

    public function testItParsesEverything()
    {
        $input = 'Input';
        $markupOutput = 'Markup output';
        $finalOutput = 'Typography output';

        $this->mockNoMarkupHandlers();
        $this->mockNoTypographyHandlers();

        $this->mockMarkup->shouldReceive('transform')
            ->once()->with($input)
            ->andReturn($markupOutput);

        $this->mockTypography->shouldReceive('transform')
            ->once()->with($markupOutput)
            ->andReturn($finalOutput);

        $this->assertEquals($finalOutput, $this->testSubject->parseAll($input));
    }

    public function testMarkupLogsAnErrorWhenGivenInvalidData()
    {
        $this->mockLogger->shouldReceive('logError')->once()->with(m::any());
        $this->mockMarkup->shouldReceive('transform')->never();
        $this->testSubject->parseMarkup([]);
    }

    public function testMarkupAcceptsAnObjectWithAToStringMethod()
    {
        $this->mockNoMarkupHandlers();

        $inputString = 'I am a string';
        $inputObject = new Stringable($inputString);
        $expectedOutput = 'Expected output';

        $this->mockMarkup->shouldReceive('transform')
            ->once()->with($inputObject)
            ->andReturn($expectedOutput);

        $this->assertEquals(
            $expectedOutput,
            $this->testSubject->parseMarkup($inputObject)
        );
    }

    public function testTypographyLogsAnErrorWhenGivenInvalidData()
    {
        $this->mockLogger->shouldReceive('logError')->once()->with(m::any());
        $this->mockTypography->shouldReceive('transform')->never();
        $this->testSubject->parseTypography([]);
    }

    public function testTypographyAcceptsAnObjectWithAToStringMethod()
    {
        $this->mockNoTypographyHandlers();

        $inputString = 'I am a string';
        $inputObject = new Stringable($inputString);
        $expectedOutput = 'Expected output';

        $this->mockTypography->shouldReceive('transform')
            ->once()->with($inputObject)
            ->andReturn($expectedOutput);

        $this->assertEquals(
            $expectedOutput,
            $this->testSubject->parseTypography($inputObject)
        );
    }

    public function testOtherPluginsCanModifyTheMarkupInput()
    {
        $originalInput = 'Original input';
        $modifiedInput = 'Modified input';
        $expectedOutput = '<p>Modified input</p>';

        $this->mockNoHandlers('modifySmartdownMarkupOutput');

        $this->mockPluginsService->shouldReceive('call')
            ->once()->with('modifySmartdownMarkupInput', $originalInput)
            ->andReturn($modifiedInput);

        $this->mockMarkup->shouldReceive('transform')
            ->once()->with($modifiedInput)
            ->andReturn($expectedOutput);

        $this->assertEquals(
            $expectedOutput,
            $this->testSubject->parseMarkup($originalInput)
        );
    }

    public function testOtherPluginsCanModifyTheMarkupOutput()
    {
        $originalInput = 'Original input';
        $originalOutput = '<p>Original output</p>';
        $modifiedOutput = '<p>Modified output</p>';

        $this->mockNoHandlers('modifySmartdownMarkupInput');

        $this->mockMarkup->shouldReceive('transform')
            ->once()->with($originalInput)
            ->andReturn($originalOutput);

        $this->mockPluginsService->shouldReceive('call')
            ->once()->with('modifySmartdownMarkupOutput', $originalOutput)
            ->andReturn($modifiedOutput);

        $this->assertEquals(
            $modifiedOutput,
            $this->testSubject->parseMarkup($originalInput)
        );
    }

    public function testOtherPluginsCanModifyTheTypographyInput()
    {
        $originalInput = 'Original input';
        $modifiedInput = 'Modified input';
        $expectedOutput = 'Expected output';

        $this->mockNoHandlers('modifySmartdownTypographyOutput');

        $this->mockPluginsService->shouldReceive('call')
            ->once()->with('modifySmartdownTypographyInput', $originalInput)
            ->andReturn($modifiedInput);

        $this->mockTypography->shouldReceive('transform')
            ->once()->with($modifiedInput)
            ->andReturn($expectedOutput);

        $this->assertEquals(
            $expectedOutput,
            $this->testSubject->parseTypography($originalInput)
        );
    }

    public function testOtherPluginsCanModifyTheTypographyOutput()
    {
        $originalInput = 'Original input';
        $originalOutput = 'Original output';
        $modifiedOutput = 'Modified output';

        $this->mockNoHandlers('modifySmartdownTypographyInput');

        $this->mockTypography->shouldReceive('transform')
            ->once()->with($originalInput)
            ->andReturn($originalOutput);

        $this->mockPluginsService->shouldReceive('call')
            ->once()->with('modifySmartdownTypographyOutput', $originalOutput)
            ->andReturn($modifiedOutput);

        $this->assertEquals(
            $modifiedOutput,
            $this->testSubject->parseTypography($originalInput)
        );
    }
}

class Stringable
{
    protected $stringValue;

    public function __construct($stringValue)
    {
        $this->stringValue = $stringValue;
    }

    public function __toString()
    {
        return $this->stringValue;
    }
}
