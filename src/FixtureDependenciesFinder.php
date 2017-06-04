<?php

namespace Driade\Fixtures;

class FixtureDependenciesFinder
{
    private $dependencies = [];

    public function __construct($tree)
    {
        $this->tree = $tree;
    }

    public function handle()
    {
        $this->findDependencies($this->tree);

        return $this->dependencies;
    }

    protected function findDependencies($tree)
    {
        $wasOne = false;

        if (is_object($tree[0])) {
            $tree   = [$tree];
            $wasOne = true;
        }

        foreach ($tree as $leaf) {

            foreach ($leaf as $key => $props) {

                if ( ! is_numeric($key)) {
                    $this->evaluateDependency($leaf, $key, $props);
                    $this->findDependencies($props);
                }

            }

        }

        if ($wasOne) {
            $tree = array_pop($tree);
        }
    }

    protected function evaluateDependency($leaf, $key, $props)
    {
        $object    = $leaf[0];
        $relation  = get_class($object->$key());
        $reference = $this->getReference($leaf);

        switch ($relation) {

            case 'Illuminate\Database\Eloquent\Relations\HasMany':
            case 'Illuminate\Database\Eloquent\Relations\HasOne':

                if ( ! is_array($props)) {
                    $props = [$props];
                }

                foreach ($props as $prop) {

                    $prop_reference = $this->getReference($prop);

                    if ( ! isset($this->dependencies[$prop_reference])) {
                        $this->dependencies[$prop_reference] = [];
                    }

                    $this->dependencies[$prop_reference][] = (object) [
                        'parent' => $reference,
                        'key'    => $key,
                        'type'   => $relation,
                    ];

                }

                break;

        }
    }

    protected function getReference($leaf)
    {
        foreach ($leaf as $key => $prop) {
            if (is_numeric($key) && $prop[0] === ':') {
                return $prop;
            }
        }

        return false;
    }
}
