<?php

namespace Four026\Passphrase;

/**
 * Human-memorisable passphrase generator
 * @package Four026\Passphrase
 */
class PassphraseGenerator
{
    /**
     * Array of words that can be used in the passphrase.
     * @var Word[]
     */
    private $wordlist = array();

    /**
     * Associative array mapping tags to arrays of Word objects
     * @var Word[][]
     */
    private $tag_index = array();

    /**
     * @param string $wordlist_path The path to the wordlist file to use as the basis for this passphrase generator.
     */
    public function __construct($wordlist_path)
    {
        if (!file_exists($wordlist_path)) {
            throw new \InvalidArgumentException("Could not find specified wordlist file: $wordlist_path");
        }

        if (($handle = fopen($wordlist_path, "r")) === FALSE) {
            throw new \RuntimeException("Unable to open wordlist file: $wordlist_path");
        }

        //Read in dictionary as csv, use first field as word, subsequent fields as tags.
        while (($data = fgetcsv($handle)) !== FALSE) {
            $word = new Word($data[0], array_slice($data, 1));

            //Add word to wordlist and tag index.
            $this->wordlist[] = $word;
            foreach ($word->tags as $tag) {
                if (!array_key_exists($tag, $this->tag_index)) {
                    $this->tag_index[$tag] = array();
                }
                $this->tag_index[$tag][] = $word;
            }

        }

        fclose($handle);
    }

    /**
     * Get a randomly-selected Word object from the wordlist, optionally specifiying a tag that the result word must have.
     * @param string|null $with_tag
     * @return Word
     */
    private function getRandomWordObj($with_tag = null)
    {
        if (!isset($with_tag)) {
            return $this->wordlist[mt_rand(0, count($this->wordlist) - 1)];
        } elseif (!array_key_exists($with_tag, $this->tag_index) || empty($this->tag_index[$with_tag])) {
            throw new \InvalidArgumentException("No valid words in provided tag: $with_tag");
        } else {
            return $this->tag_index[$with_tag][mt_rand(0, count($this->tag_index[$with_tag]) - 1)];
        }
    }

    /**
     * Get a randomly-selected word from the wordlist, optionally specifiying a tag that the result word must have.
     * @param string|null $with_tag
     * @return string
     */
    public function getRandomWord($with_tag = null)
    {
        return $this->getRandomWordObj($with_tag)->word;
    }

    /**
     * Get a randomly-generated passphrase from the words in the wordlist.
     *
     * @param int $num_words The number of words to use in the passphrase.
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