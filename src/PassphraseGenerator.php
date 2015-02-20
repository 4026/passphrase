<?php

namespace Four026\Passphrase;

/**
 * Human-memorisable passphrase generator
 */
class PassphraseGenerator
{
    /**
     * Array of words that can be used in the passphrase.
     * @var string[]
     */
    private $wordlist;

    /**
     * @param string $wordlist_path The path to the wordlist file to use as the basis for this passphrase generator.
     */
    public function __construct($wordlist_path)
    {
        if (!file_exists($wordlist_path)) {
            throw new \InvalidArgumentException("Could not find specified wordlist file: $wordlist_path");
        }

        if (($this->wordlist = file($wordlist_path, FILE_IGNORE_NEW_LINES)) === false) {
            throw new \InvalidArgumentException("Failed to load specified wordlist file: $wordlist_path");
        }
    }

    /**
     * Get a randomly-selected word from the wordlist.
     * @return string
     */
    private function getRandomWord()
    {
        return $this->wordlist[mt_rand(0, count($this->wordlist) - 1)];
    }

    /**
     * Get a randomly-generated passphrase from the words in the wordlist.
     *
     * @param int    $num_words The number of words to use in the passphrase.
     * @param string $separator The separator to use between words.
     *
     * @return string
     */
    public function generate($num_words = 4, $separator = " ")
    {
        $words = array();
        for ($i = 0; $i < $num_words; ++$i) {
            $words[] = $this->getRandomWord();
        }
        return implode($separator, $words);
    }
}