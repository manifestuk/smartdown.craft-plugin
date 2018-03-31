<?php

namespace experience\smartdown\services;

use Craft;
use experience\smartdown\events\Parse;
use Michelf\MarkdownExtra;
use Michelf\SmartyPants;
use yii\base\Component;

class Smartdown extends Component
{
    /**
     * @event Parse Event triggered before Smartdown parses the markup.
     */
    const EVENT_BEFORE_PARSE_MARKUP = 'beforeParseMarkup';

    /**
     * @event Parse Event triggered after Smartdown parses the markup.
     */
    const EVENT_AFTER_PARSE_MARKUP = 'afterParseMarkup';

    /**
     * @event Parse Event triggered before Smartdown parses the typography.
     */
    const EVENT_BEFORE_PARSE_TYPOGRAPHY = 'beforeParseTypography';

    /**
     * @event Parse Event triggered after Smartdown parses the typography.
     */
    const EVENT_AFTER_PARSE_TYPOGRAPHY = 'afterParseTypography';

    /**
     * @var MarkdownExtra
     */
    protected $markupParser;

    /**
     * @var SmartyPants
     */
    protected $typographyParser;

    /**
     * Initialise the service.
     */
    public function init()
    {
        $this->markupParser = new MarkdownExtra();
        $this->typographyParser = new SmartyPants();
    }

    /**
     * Run the given string through all of the available parsers.
     *
     * @param $input
     *
     * @return string
     */
    public function parseAll($input): string
    {
        if (! $this->isStringable($input)) {
            $this->logError(__METHOD__ . ' expects a string');

            return '';
        }

        return $this->parseTypography($this->parseMarkup($input));
    }

    /**
     * Return a boolean indicating whether the given input can be converted to
     * a string.
     *
     * @param mixed $input
     *
     * @return bool
     */
    protected function isStringable($input): bool
    {
        return is_string($input) or method_exists($input, '__toString');
    }

    /**
     * Log the given error.
     *
     * @param string $message
     */
    protected function logError(string $message)
    {
        Craft::error($message, 'smartdown');
    }

    /**
     * Run the given string through "typography" parser.
     *
     * @param string $input
     *
     * @return string
     */
    public function parseTypography($input): string
    {
        if (! $this->isStringable($input)) {
            $this->logError(__METHOD__ . ' expects a string');

            return '';
        }

        // Allow event listeners to modify the input, before it is parsed.
        $input = $this->callListeners(
            static::EVENT_BEFORE_PARSE_TYPOGRAPHY, $input);

        // Parse the input.
        $output = $this->typographyParser->transform($input);

        // Allow event listeners to modify the parsed string.
        $output = $this->callListeners(
            static::EVENT_AFTER_PARSE_TYPOGRAPHY, $output);

        return $output;
    }

    /**
     * Run the given string through "markup" parser.
     *
     * @param $input
     *
     * @return string
     */
    public function parseMarkup($input): string
    {
        if (! $this->isStringable($input)) {
            $this->logError(__METHOD__ . ' expects a string');

            return '';
        }

        // Allow event listeners to modify the input, before it is parsed.
        $input = $this->callListeners(
            static::EVENT_BEFORE_PARSE_MARKUP, $input);

        $output = $this->markupParser->transform($input);

        // Allow plugins to modify the output.
        $output = $this->callListeners(
            static::EVENT_AFTER_PARSE_MARKUP, $output);

        return $output;
    }

    /**
     * Call the event listeners for the given event, and pass them the given
     * content. Return the content after any transformations.
     *
     * @param string $eventName
     * @param string $content
     *
     * @return string
     */
    protected function callListeners(string $eventName, string $content): string
    {
        $event = new Parse(['content' => $content]);

        $this->trigger($eventName, $event);

        return $event->content;
    }
}
