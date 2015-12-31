<?php

use Smartdown\Utils\ServiceLocator;

if (! function_exists('smartdown')) {
    function smartdown()
    {
        return ServiceLocator::getInstance();
    }
}
