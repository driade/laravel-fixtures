<?php

namespace Driade\Fixtures;

class FixtureReferencesCreator
{
    private $output = [];

    public function __construct($tree)
    {
        $this->tree = $tree;
    }

    public function handle()
    {
        $this->createReferences($this->tree);

        return $this->tree;
    }

    protected function createReferences(&$tree)
    {
        $wasOne = false;

        if (is_object($tree[0])) {
            $tree   = [$tree];
            $wasOne = true;
        }

        foreach ($tree as &$leaf) {

            $hasReference = false;

            foreach ($leaf as $key => &$prop) {

                if ($key === 0) {
                    continue;
                }

                if (is_array($prop)) {
                    $this->createReferences($prop);
                    continue;
                }

                if (is_numeric($key) && $prop[0] === ':') {
                    $hasReference = true;
                }

            }

            if ( ! $hasReference) {
                array_push($leaf, ':' . uniqid());
            }
        }

        if ($wasOne) {
            $tree = array_pop($tree);
        }
    }
}
