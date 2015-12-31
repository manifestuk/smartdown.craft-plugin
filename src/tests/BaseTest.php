<?php namespace Smartdown\Tests;

use \Mockery as m;
use \PHPUnit_Framework_TestCase;

abstract class BaseTest extends PHPUnit_Framework_TestCase
{
    /**
     * Cleans up after each test.
     */
    public function tearDown()
    {
        m::close();
    }
}
