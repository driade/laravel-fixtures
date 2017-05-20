<?php

namespace Driade\Fixtures;

class FixtureRelationCalculate
{
    public function __construct($tree)
    {
        $this->tree = $tree;
    }

    public function handle()
    {
        $this->done  = 0;
        $this->total = 0;

        $this->countObjects();

        while ($this->done !== $this->total) {
            $this->process($this->tree);
        }

        return $this->tree;
    }

    protected function countObjects()
    {
        $this->count($this->tree);
    }

    protected function count($tree)
    {
        if (is_object($tree[0])) {
            $tree = [$tree];
        }

        foreach ($tree as $leaf) {

            $this->total++;

            foreach ($leaf as $key => $prop) {

                if ($key === 0) {
                    continue;
                }

                $this->count($prop);

            }

        }
    }

    public function process($tree, $parent = null, $relation = '')
    {

        if (is_object($tree[0])) {
            $tree = [$tree];
        }

        foreach ($tree as $leaf) {

            $object = $leaf[0];

            $this->canAssignRelation($leaf[0], $parent, $relation);

            if ($this->evaluate($leaf)) {
                $this->done++;
                $object->save();
            }

            foreach ($leaf as $key => $prop) {

                if ($key === 0) {
                    continue;
                }

                $this->process($prop, $object, $key);

            }
        }
    }

    protected function canAssignRelation($leaf, $parent, $relation)
    {
        if ($parent === null || ! $parent->exists) {
            return false;
        }

        switch (get_class($parent->$relation())) {

            case 'Illuminate\Database\Eloquent\Relations\HasMany':

                $relation_field = $parent->$relation()->getForeignKeyName();

                $key = $parent->getKeyName();

                $leaf->$relation_field = $parent->$key;

                return true;
                break;
        }

        return false;
    }

    protected function evaluate($leaf)
    {
        if ($leaf[0]->exists) {
            return false;
        }

        $valid = true;

        foreach ($leaf as $key => $prop) {

            if ($key === 0) {
                continue;
            }

            $relation = get_class($leaf[0]->$key());

            switch ($relation) {
                case 'Illuminate\Database\Eloquent\Relations\BelongsTo':

                    $parent = $prop[0];

                    if ($parent->exists) {
                        $leaf[0]->$key()->associate($parent);
                    } else {
                        $valid = false;
                    }

                    break;
            }

        }

        return $valid;
    }
}
