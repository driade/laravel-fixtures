<?php

namespace Driade\Fixtures;

class Loader
{
    public function __construct($input)
    {
        $this->input = $input;
    }

    public static function load($input)
    {
        $action = new self($input);

        $action->handle();

        return $action->output;
    }

    protected function handle()
    {
        if (is_string($this->input)) {
            $this->input = (include $this->input);
        }

        $this->fixtures = $this->input;

        $this->output = (new FixtureTreeMaker($this->fixtures))->handle();
        $this->output = (new FixtureRelationCalculate($this->output))->handle();

        $this->output = $this->output[0];

    }
}
