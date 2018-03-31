<?php

namespace experience\smartdown\tests\unit\services;

use experience\smartdown\events\Parse;
use experience\smartdown\services\Smartdown;
use UnitTester;

class SmartdownCest
{
    /**
     * @var Smartdown
     */
    protected $subject;

    public function _before()
    {
        $this->subject = new Smartdown;
    }

    public function parseMarkup(UnitTester $I)
    {
        $I->wantTo('convert markdown into markup');

        $input = 'This should be _italic_';
        $output = $this->normalize($this->subject->parseMarkup($input));
        $expected = '<p>This should be <em>italic</em></p>';

        $I->assertEquals($expected, $output);
    }

    /**
     * Remove leading and trailing whitespace and line-breaks
     *
     * @param string $result
     *
     * @return string
     */
    protected function normalize(string $result): string
    {
        return trim($result);
    }

    public function parseMarkupToString(UnitTester $I)
    {
        $I->wantTo('convert markdown of object');

        $input = new Stringable('This should be _italic_');
        $output = $this->normalize($this->subject->parseMarkup($input));
        $expected = '<p>This should be <em>italic</em></p>';

        $I->assertEquals($expected, $output);
    }

    public function parseMarkupNonString(UnitTester $I)
    {
        $I->wantToTest('parse markup with non-string returns an empty string');

        $input = ['Epic fail ahead'];
        $output = $this->normalize($this->subject->parseMarkup($input));

        $I->assertEquals('', $output);
    }

    public function parseMarkupBefore(UnitTester $I)
    {
        $I->wantTo('modify markdown before it is parsed');

        $input = 'This should be _italic_';

        $this->subject->on(
            Smartdown::EVENT_BEFORE_PARSE_MARKUP,
            function (Parse $e) {
                $e->content = str_replace('_italic_', '**bold**', $e->content);
            }
        );

        $expected = '<p>This should be <strong>bold</strong></p>';
        $output = $this->normalize($this->subject->parseMarkup($input));

        $I->assertEquals($expected, $output);
    }

    public function parseMarkupAfter(UnitTester $I)
    {
        $I->wantTo('modify markup after it is parsed');

        $input = 'This should be _italic_';

        $this->subject->on(
            Smartdown::EVENT_AFTER_PARSE_MARKUP,
            function (Parse $e) {
                $e->content = str_replace('p>', 'h1>', $e->content);
            }
        );

        $expected = '<h1>This should be <em>italic</em></h1>';
        $output = $this->normalize($this->subject->parseMarkup($input));

        $I->assertEquals($expected, $output);
    }

    public function parseTypography(UnitTester $I)
    {
        $I->wantTo('fancy up the typography');

        $input = 'These should be "fancy quote marks"...';
        $output = $this->normalize($this->subject->parseTypography($input));
        $expected = 'These should be &#8220;fancy quote marks&#8221;&#8230;';

        $I->assertEquals($expected, $output);
    }

    public function parseTypographyToString(UnitTester $I)
    {
        $I->wantTo('fancy up the typography of object');

        $input = new Stringable('These should be "fancy quote marks"...');
        $output = $this->normalize($this->subject->parseTypography($input));
        $expected = 'These should be &#8220;fancy quote marks&#8221;&#8230;';

        $I->assertEquals($expected, $output);
    }

    public function parseTypographyNonString(UnitTester $I)
    {
        $I->wantToTest('parse typography with non-string returns an empty string');

        $input = ['Epic fail ahead'];
        $output = $this->normalize($this->subject->parseTypography($input));

        $I->assertEquals('', $output);
    }

    public function parseTypographyBefore(UnitTester $I)
    {
        $I->wantTo('modify typography before it is parsed');

        $input = 'A simple string';

        $this->subject->on(
            Smartdown::EVENT_BEFORE_PARSE_TYPOGRAPHY,
            function (Parse $e) {
                $e->content = str_replace('simple', '"simple"', $e->content);
            }
        );

        $expected = 'A &#8220;simple&#8221; string';
        $output = $this->normalize($this->subject->parseTypography($input));

        $I->assertEquals($expected, $output);
    }

    public function parseTypographyAfter(UnitTester $I)
    {
        $I->wantTo('modify typography after it is parsed');

        $input = 'A "simple" string';

        $this->subject->on(
            Smartdown::EVENT_AFTER_PARSE_TYPOGRAPHY,
            function (Parse $e) {
                $e->content = str_replace('&#822', '&#832', $e->content);
            }
        );

        $expected = 'A &#8320;simple&#8321; string';
        $output = $this->normalize($this->subject->parseTypography($input));

        $I->assertEquals($expected, $output);
    }

    public function parseAll(UnitTester $I)
    {
        $I->wantTo('convert markdown and typography');

        $input = 'These should be "_fancy_ quote marks"...';
        $output = $this->normalize($this->subject->parseAll($input));
        $expected = '<p>These should be &#8220;<em>fancy</em> quote marks&#8221;&#8230;</p>';

        $I->assertEquals($expected, $output);
    }

    public function parseAllToString(UnitTester $I)
    {
        $I->wantTo('convert markdown and typography of object');

        $input = new Stringable('These should be "_fancy_ quote marks"...');
        $output = $this->normalize($this->subject->parseAll($input));
        $expected = '<p>These should be &#8220;<em>fancy</em> quote marks&#8221;&#8230;</p>';

        $I->assertEquals($expected, $output);
    }

    public function parseAllNonString(UnitTester $I)
    {
        $I->wantToTest('parse all with non-string returns an empty string');

        $input = ['Epic fail ahead'];
        $output = $this->normalize($this->subject->parseAll($input));

        $I->assertEquals('', $output);
    }
}

class Stringable
{
    protected $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
