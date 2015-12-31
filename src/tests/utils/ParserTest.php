<?php namespace Smartdown\Tests\Utils;

use Mockery as m;
use Smartdown\Tests\BaseTest;
use Smartdown\Utils\Parser;

class ParserTest extends BaseTest
{
    private $mockLogger;
    private $mockMarkup;
    private $testSubject;
    private $mockPlugins;
    private $mockTypography;

    /**
     * Sets the stage before each test.
     */
    public function setUp()
    {
        $this->mockLogger = m::mock('\Smartdown\Utils\Logger');
        $this->mockMarkup = m::mock('MockMarkupService');
        $this->mockPlugins = m::mock('MockPluginsService');
        $this->mockTypography = m::mock('MockTypographyService');

        $this->testSubject = new Parser(
            $this->mockMarkup,
            $this->mockTypography,
            $this->mockPlugins,
            $this->mockLogger
        );
    }

    public function testGetInstanceReturnsASingleton()
    {
        $firstInstance = Parser::getInstance(
            $this->mockMarkup,
            $this->mockTypography,
            $this->mockPlugins,
            $this->mockLogger
        );

        $secondInstance = Parser::getInstance(
            $this->mockMarkup,
            $this->mockTypography,
            $this->mockPlugins,
            $this->mockLogger
        );

        $this->assertSame($firstInstance, $secondInstance);
    }

    public function testItParsesMarkup()
    {
        $input = 'Input';
        $output = 'Output';

        // Not testing the hooks.
        $this->mockPlugins->shouldReceive('getPlugins')->andReturn([]);

        $this->mockMarkup
            ->shouldReceive('transform')->with($input)->once()
            ->andReturn($output);

        $this->assertEquals($output, $this->testSubject->parseMarkup($input));
    }

    public function testItParsesTypography()
    {
        $input = 'Input';
        $output = 'Output';

        // Not testing the hooks.
        $this->mockPlugins->shouldReceive('getPlugins')->andReturn([]);

        $this->mockTypography
            ->shouldReceive('transform')->with($input)->once()
            ->andReturn($output);

        $this->assertEquals(
            $output,
            $this->testSubject->parseTypography($input)
        );
    }

    public function testItParsesEverything()
    {
        $input = 'Input';
        $markupOutput = 'Markup output';
        $typographyOutput = 'Typography output';

        // Not testing the hooks.
        $this->mockPlugins->shouldReceive('getPlugins')->andReturn([]);

        $this->mockMarkup
            ->shouldReceive('transform')->with($input)->once()
            ->andReturn($markupOutput);

        $this->mockTypography
            ->shouldReceive('transform')->with($markupOutput)->once()
            ->andReturn($typographyOutput);

        $this->assertEquals(
            $typographyOutput,
            $this->testSubject->parseAll($input)
        );
    }

    public function testItLogsAnErrorWhenPassingInvalidDataToMarkup()
    {
        $this->mockLogger->shouldReceive('logError')->once()->with(m::any());
        $this->mockMarkup->shouldReceive('transform')->never();

        $this->assertEquals('', $this->testSubject->parseMarkup([]));
    }

    public function testItLogsAnErrorWhenPassingInvalidDataToTypography()
    {
        $this->mockLogger->shouldReceive('logError')->once()->with(m::any());
        $this->mockMarkup->shouldReceive('transform')->never();

        $this->assertEquals('', $this->testSubject->parseTypography([]));
    }

    public function testItLogsAnErrorWhenPassingInvalidDataToParseAll()
    {
        $this->mockLogger->shouldReceive('logError')->once()->with(m::any());
        $this->mockMarkup->shouldReceive('transform')->never();
        $this->mockTypography->shouldReceive('transform')->never();

        $this->assertEquals('', $this->testSubject->parseAll([]));
    }

    public function testItCallsParseMarkupHooks()
    {
        $input = 'Input';
        $inputHookResult = 'Modified by input hook';
        $markupParserResult = 'Parsed';
        $outputHookResult = 'Modified by output hook';

        $mockHandler = m::mock('\Smartdown\Tests\Utils\MockHookHandler[
            modifySmartdownMarkupInput,
            modifySmartdownMarkupOutput
        ]');

        $mockHandler
            ->shouldReceive('modifySmartdownMarkupInput')->once()
            ->with($input)
            ->andReturn($inputHookResult);

        $this->mockPlugins
            ->shouldReceive('getPlugins')->andReturn([$mockHandler]);

        $mockHandler
            ->shouldReceive('modifySmartdownMarkupOutput')->once()
            ->with($markupParserResult)
            ->andReturn($outputHookResult);

        $this->mockMarkup
            ->shouldReceive('transform')->with($inputHookResult)->once()
            ->andReturn($markupParserResult);

        $this->assertEquals(
            $outputHookResult,
            $this->testSubject->parseMarkup($input)
        );
    }

    public function testItCallsParseTypographyHooks()
    {
        $input = 'Input';
        $inputHookResult = 'Modified by input hook';
        $typographyParserResult = 'Parsed';
        $outputHookResult = 'Modified by output hook';

        $mockHandler = m::mock('\Smartdown\Tests\Utils\MockHookHandler[
            modifySmartdownTypographyInput,
            modifySmartdownTypographyOutput
        ]');

        $mockHandler
            ->shouldReceive('modifySmartdownTypographyInput')->once()
            ->with($input)
            ->andReturn($inputHookResult);

        $this->mockPlugins
            ->shouldReceive('getPlugins')->andReturn([$mockHandler]);

        $mockHandler
            ->shouldReceive('modifySmartdownTypographyOutput')->once()
            ->with($typographyParserResult)
            ->andReturn($outputHookResult);

        $this->mockTypography
            ->shouldReceive('transform')->with($inputHookResult)->once()
            ->andReturn($typographyParserResult);

        $this->assertEquals(
            $outputHookResult,
            $this->testSubject->parseTypography($input)
        );
    }
}

/**
 * Mock "hook" handler class. Required because the Parses runs a `method_exists`
 * check before call the hook handler methods.
 *
 * @package Smartdown\Tests\Utils
 */
abstract class MockHookHandler
{
    abstract public function modifySmartdownMarkupInput($source);
    abstract public function modifySmartdownMarkupOutput($source);
    abstract public function modifySmartdownTypographyInput($source);
    abstract public function modifySmartdownTypographyOutput($source);
}
