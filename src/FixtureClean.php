<?php

namespace Driade\Fixtures;

class FixtureClean
{
    private $output = [];

    public function __construct($tree)
    {
        $this->tree = $tree;
    }

    public function handle()
    {
        $this->clean($this->tree);

        return $this->tree;
    }

    protected function clean(&$tree)
    {
        $wasOne = false;

        if (is_object($tree[0])) {
            $tree   = [$tree];
            $wasOne = true;
        }

        foreach ($tree as &$leaf) {

            foreach ($leaf as $key => &$prop) {
                if (is_numeric($key)) {
                    if ($prop[0] === ':') {
                        unset($leaf[$key]);
                    }
                } else {
                    $this->clean($prop);
                }
            }
        }

        if ($wasOne) {
            $tree = array_pop($tree);
        }
    }
}
