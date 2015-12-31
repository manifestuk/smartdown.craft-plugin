<?php namespace Craft;

/**
 * Mock Craft Logger class. Required, because mocking the class constants is a
 * pain otherwise.
 *
 * @package Craft
 */
class Logger
{
    const LEVEL_ERROR = 'MockError';
    const LEVEL_INFO = 'MockInfo';
    const LEVEL_WARNING = 'MockWarning';
}