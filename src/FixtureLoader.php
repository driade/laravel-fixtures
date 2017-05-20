<?php

namespace Driade\Fixtures;

class FixtureLoader
{
    public function __construct($path)
    {
        $this->path = $path;
    }

    public static function load($path)
    {
        $action = new self($path);

        $action->handle();

        return $action->output;
    }

    protected function handle()
    {
        $this->fixtures = (include $this->path . '.php');

        $this->output = (new FixtureTreeMaker($this->fixtures))->handle();
        $this->output = (new FixtureRelationCalculate($this->output))->handle();

        $this->output = $this->output[0];

    }
}
