<?php

declare(strict_types=1);

namespace Jasny\Annotations\Tag\PhpDocumentor;

use Jasny\Annotations\Tag\AbstractTag;
use Jasny\Annotations\AnnotationException;

/**
 * Custom logic for PhpDocumentor 'var', 'param' and 'property' tag
 */
class VarTag extends AbstractTag
{
    /**
     * @var array
     */
    protected $additional;

    /**
     * @var callable|null
     */
    protected $fqsenConvertor;


    /**
     * Class constructor.
     *
     * @param string        $name            Tag name
     * @param callable|null $fqsenConverter  Logic to convert class to FQCN
     * @param array         $additional      Additional properties
     */
    public function __construct(string $name, ?callable $fqsenConverter = null, array $additional = [])
    {
        parent::__construct($name);

        $this->fqsenConvertor = $fqsenConverter;
        $this->additional = $additional;
    }

    /**
     * Get additional properties that are always applied.
     *
     * @return array
     */
    public function getAdditionalProperties(): array
    {
        return $this->additional;
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
        $regexp = '/^(?:(?<type>[^$\s]+)\s*)?(?:\$(?<name>\w+)\s*)?(?:"(?<id>[^"]+)")?/';

        if (!preg_match($regexp, $value, $props)) {
            throw new AnnotationException("Failed to parse '@{$this->name} $value': invalid syntax");
        }

        if (isset($props['type']) && isset($this->fqsenConvertor)) {
            $props['type'] = call_user_func($this->fqsenConvertor, $props['type']);
        }

        $annotations[$this->name] = $props + $this->additional;
    }
}