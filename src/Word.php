<?php
namespace Four026\Passphrase;

/**
 * A single word loaded from the dictionary.
 * @package Four026\Passphrase
 * @property-read string $word The word itself
 */
class Word
{
    /**
     * The word itself
     * @var string
     */
    private $word;

    /**
     * Array of tags associated with this word in the dictionary.
     * @var string[]
     */
    public $tags;

    /**
     * @param string $word
     * @param string[] $tags
     */
    public function __construct($word, $tags = array())
    {
        $this->word = $word;
        $this->tags = $tags;
    }

    public function __get($var_name)
    {
        switch ($var_name) {
            case 'word':
                return $this->word;
            default:
                $trace = debug_backtrace();
                trigger_error(
                    'Undefined property via __get(): ' . $var_name . ' in ' . $trace[0]['file'] . ' on line ' . $trace[0]['line'],
                    E_USER_NOTICE
                );
                return null;
        }
    }
}