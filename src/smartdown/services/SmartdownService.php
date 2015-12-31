<?php namespace Craft;

class SmartdownService extends BaseApplicationComponent
{
    protected $parser;

    /**
     * Initialises the parser instance.
     */
    public function __construct()
    {
        $this->parser = smartdown()->parser;
    }

    /**
     * Runs the given string through all of the available parsers.
     *
     * @param $source
     *
     * @return string
     */
    public function parseAll($source)
    {
        return $this->parser->parseAll($source);
    }

    /**
     * Runs the given string through "markup" parser.
     *
     * @param $source
     *
     * @return string
     */
    public function parseMarkup($source)
    {
        return $this->parser->parseMarkup($source);
    }

    /**
     * Runs the given string through "typography" parser.
     *
     * @param string $source
     *
     * @return string
     */
    public function parseTypography($source)
    {
        return $this->parser->parseTypography($source);
    }
}