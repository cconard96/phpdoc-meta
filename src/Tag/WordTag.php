<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag;

use Jasny\Annotations\Tag\AbstractTag;

use function Jasny\expect_type;

/**
 * Only use the first word after the tag, ignoring the rest
 */
class WordTag extends AbstractTag
{
    /**
     * Default value if no value is given for tag
     * @var string|bool
     */
    protected $default;

    /**
     * WordTag constructor.
     *
     * @param string      $name
     * @param string|bool $default
     */
    public function __construct(string $name, $default = '')
    {
        parent::__construct($name);

        expect_type($default, 'string|bool');
        $this->default = $default;
    }

    /**
     * Return default if no value is specified
     *
     * @return string|bool
     */
    public function getDefault()
    {
        return $this->default;
    }


    /**
     * Process an annotation.
     *
     * @param array  $annotations
     * @param string $value
     * @return void
     */
    public function process(array &$annotations, string $value): void
    {
        [$word] = $value !== '' ? explode(' ', $value, 2) : [$this->default];
        $annotations[$this->name] = $word;
    }
}