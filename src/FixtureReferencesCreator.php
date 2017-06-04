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
        if (is_object($tree[0])) {
            $val = [$tree];
        } else {
            $val = $tree;
        }

        foreach ($val as &$leaf) {

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

                    break;
                }

            }

            if ( ! $hasReference) {
                array_push($leaf, ':' . uniqid());
            }
        }
    }
}
