<?php

namespace Driade\Fixtures;

class FixtureTreeMaker
{
    public function __construct($fixtures)
    {
        $this->fixtures = $fixtures;
    }

    public function handle()
    {
        return $this->process($this->fixtures);
    }

    protected function process($datas)
    {
        $wasOne = false;

        if ( ! is_array($datas[0])) {
            $datas  = [$datas];
            $wasOne = true;
        }

        $output = [];

        foreach ($datas as $data) {

            $props = [];

            foreach ($data as $index => $prop) {

                if ($index === 0) {
                    continue;
                }

                if ( ! is_array($prop)) {
                    $props[$index] = $prop;
                    unset($data[$index]);
                }
            }

            $data[0] = new $data[0];

            foreach ($props as $key => $value) {
                $data[0]->setAttribute($key, $value);
            }

            foreach ($data as $index => $prop) {

                if ($index === 0) {
                    continue;
                }

                if (is_array($prop)) {
                    $data[$index] = $this->process($prop);
                }
            }

            $output[] = $data;

        }

        if ($wasOne) {
            $output = array_pop($output);
        }

        return $output;
    }
}
