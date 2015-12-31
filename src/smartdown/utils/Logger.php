<?php namespace Smartdown\Utils;

use Craft\Logger as CraftLogger;
use Craft\SmartdownPlugin;

class Logger
{
    /**
     * Logs an informational message to the plugin log file.
     *
     * @param string $message
     */
    public function logInfo($message)
    {
        SmartdownPlugin::log($message, CraftLogger::LEVEL_INFO);
    }

    /**
     * Logs a warning message to the plugin log file.
     *
     * @param string $message
     */
    public function logWarning($message)
    {
        SmartdownPlugin::log($message, CraftLogger::LEVEL_WARNING);
    }

    /**
     * Logs an error message to the plugin log file.
     *
     * @param string $message
     */
    public function logError($message)
    {
        SmartdownPlugin::log($message, CraftLogger::LEVEL_ERROR);
    }
}