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
        if (is_object($tree[0])) {
            $val = [$tree];
        } else {
            $val = $tree;
        }

        foreach ($val as &$leaf) {

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
    }
}
