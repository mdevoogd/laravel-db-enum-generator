<?php

namespace mdevoogd\LaravelDatabaseEnum;

/**
 * The stub assembler.
 *
 */
class StubAssembler
{
    use ExportsValues;

    /**
     * The stub to assemble.
     *
     * @var string
     */
    protected $stub;

    /**
     * The list of enum definitions.
     *
     * @var array
     */
    protected $enums;

    /**
     * The called command.
     *
     * @var string
     */
    private $command;

    /**
     * Set the dependencies.
     *
     * @param string $stub
     * @param array $enums
     */
    public function __construct(string $stub, array $enums)
    {
        $this->stub = $stub;
        $this->enums = $enums;
    }

    /**
     * Retrieve the stub
     *
     * @return string
     */
    public function getStub(): string
    {
        return $this->stub;
    }

    /**
     * Replace the called command for the given stub
     *
     * @param string $command
     * @return self
     */
    public function replaceCommand(string $command): self
    {
        $this->stub = str_replace('DummyCommand', $command, $this->stub);

        return $this;
    }

    /**
     * Replace the constants for the given stub
     *
     * @return self
     */
    public function replaceConstants(): self
    {
        $constants = array_map(function (EnumDefinition $enum) {
            $padding = 4;

            $key = $this->export($enum->key, $padding);

            return str_repeat(' ', $padding) . "case $enum->name = $key;";
        }, $this->enums);

        $this->stub = str_replace('DummyConstants', implode(PHP_EOL, $constants), $this->stub);

        return $this;
    }

    /**
     * Replace the map for the given stub
     *
     * @return self
     */
    public function replaceMap(): self
    {
        // Map enums key and value pairs only if enums have values
        if ($this->enumsHaveValues()) {
            $mapStub = file_get_contents(__DIR__ . '/../stubs/map.stub');
            $this->stub = str_replace('DummyMap', $mapStub, $this->stub);
            $this->replaceMapPairs();
        } else {
            $this->stub = str_replace('DummyMap', '', $this->stub);
        }

        return $this;
    }

    /**
     * Determine whether the given enums contain values
     *
     * @return bool
     */
    private function enumsHaveValues(): bool
    {
        foreach ($this->enums as $enum) {
            if ($enum->value !== null) {
                return true;
            }
        }

        return false;
    }

    /**
     * Replace the enums key and value pairs
     *
     * @return self
     */
    public function replaceMapPairs(): self
    {
        $pairs = array_map(function (EnumDefinition $enum) {
            $padding = 12;

            $value = $this->export($enum->value, $padding);

            return str_repeat(' ', $padding) . "self::$enum->name => {$value},";
        }, $this->enums);

        $this->stub = str_replace('DummyKeyValuePairs', implode(PHP_EOL, $pairs), $this->stub);

        return $this;
    }
}
