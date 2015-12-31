<?php namespace Smartdown\Tests\Utils;

use Craft\Logger as MockLogger;
use Mockery as m;
use Smartdown\Tests\BaseTest;
use Smartdown\Utils\Logger;

class LoggerTest extends BaseTest
{
    private $mockLogger;
    private $mockPlugin;
    private $testSubject;

    /**
     * Sets the stage before each test.
     */
    public function setUp()
    {
        $this->mockLogger = m::mock('\Craft\Logger');
        $this->mockPlugin = m::mock('alias:Craft\SmartdownPlugin');
        $this->mockTranslator = m::mock('alias:Craft\Craft');

        $this->mockTranslator
            ->shouldReceive('t')->once()->with(m::any())
            ->andReturnUsing(function ($message) {
                return $message;
            });

        $this->testSubject = new Logger();
    }

    public function testWeCanLogInformation()
    {
        $message = 'Test message';

        $this->mockPlugin
            ->shouldReceive('log')->once()
            ->with($message, MockLogger::LEVEL_INFO);

        $this->testSubject->logInfo($message);
    }

    public function testWeCanLogAWarning()
    {
        $message = 'Test message';

        $this->mockPlugin
            ->shouldReceive('log')->once()
            ->with($message, MockLogger::LEVEL_WARNING);

        $this->testSubject->logWarning($message);
    }

    public function testWeCanLogAnError()
    {
        $message = 'Test message';

        $this->mockPlugin
            ->shouldReceive('log')->once()
            ->with($message, MockLogger::LEVEL_ERROR);

        $this->testSubject->logError($message);
    }
}
