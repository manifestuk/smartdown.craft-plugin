<?php

namespace experience\smartdown\events;

use yii\base\Event;

class Parse extends Event
{
    /**
     * The content being parsed.
     *
     * @var string $content
     */
    public $content;
}
