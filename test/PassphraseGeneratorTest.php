<?php
/**
 * Testing passphrase generator
 */
class PassphraseGeneratorTest extends PHPUnit_Framework_TestCase
{
    function testGenerate()
    {
        $generator = new Four026\Passphrase\PassphraseGenerator(__DIR__ . '\testWordList');
        $phrase = $generator->generate(5, ',');
        $this->assertCount(5, explode(',', $phrase), "Generated passphrase - '$phrase' - doesn't contain the expected number of words and/or uses the wrong separator.");
        $this->assertGreaterThan(10, strlen($phrase), "Generated passphrase - '$phrase' - isn't as long as expected.");
    }
}
