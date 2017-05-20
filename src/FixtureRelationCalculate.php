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
        $c = 0;

        while ( ! $this->process($this->tree)) {

            if ($c++ > 100) {
                throw new \Exception("Unable to resolve");
            }
        }

        return $this->tree;
    }

    public function process($tree, $parent = null, $relation = '')
    {

        $complete = 1;

        if (is_object($tree[0])) {
            $tree = [$tree];
        }

        foreach ($tree as $leaf) {

            $object = $leaf[0];

            $this->assignRelations($leaf[0], $parent, $relation);

            if ($this->evaluate($leaf)) {
                $object->save();
            }

            if ( ! $this->isComplete($leaf)) {
                $complete = 0;
            }

            foreach ($leaf as $key => $prop) {

                if ($key === 0) {
                    continue;
                }

                if ( ! $this->process($prop, $object, $key)) {
                    $complete = 0;
                }

            }
        }

        return $complete;
    }

    protected function assignRelations($leaf, $parent, $relation)
    {
        if ($parent === null || ! $parent->exists) {
            return;
        }

        switch (get_class($parent->$relation())) {

            case 'Illuminate\Database\Eloquent\Relations\HasMany':
            case 'Illuminate\Database\Eloquent\Relations\HasOne':

                $relation_field = $parent->$relation()->getForeignKeyName();

                $key = $parent->getKeyName();

                $leaf->$relation_field = $parent->$key;

                break;

            case 'Illuminate\Database\Eloquent\Relations\BelongsTo':

                $parent->$relation()->associate($leaf);

                break;

            case 'Illuminate\Database\Eloquent\Relations\BelongsToMany':

                if ($parent->exists && $leaf->exists) {

                    $ids = [];

                    $leaf_key = $leaf->getKeyName();

                    foreach ($parent->$relation()->get() as $object) {
                        $ids[] = $object->$leaf_key;
                    }

                    if ( ! in_array($leaf->$leaf_key, $ids)) {
                        $parent->$relation()->attach($leaf);
                    }
                }

                break;

            case 'Illuminate\Database\Eloquent\Relations\MorphTo':

                if ($parent->exists) {
                    $parent->$relation()->associate($leaf);
                }

                break;

            case 'Illuminate\Database\Eloquent\Relations\MorphMany':
                if ($parent->exists) {
                    $parent->$relation()->save($leaf);
                }
                break;

            default:
                // print_r($leaf);
                // die("-" . get_class($parent->$relation()));
        }

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

                case 'Illuminate\Database\Eloquent\Relations\MorphTo':
                    // Tiene dependencia!

                    $parent = $prop[0];

                    if ($parent->exists) {
                        $leaf[0]->$key()->associate($parent);
                    } else {
                        $valid = false;
                    }
                    break;

                default:
                    // print_r($relation);
                    // die("-" . get_class($parent));

            }

        }

        return $valid;
    }

    protected function isComplete($leaf)
    {
        $valid = true;

        foreach ($leaf as $key => $prop) {

            if ($key === 0) {
                continue;
            }

            $relation = get_class($leaf[0]->$key());

            switch ($relation) {

                case 'Illuminate\Database\Eloquent\Relations\BelongsTo':

                    $parent = $prop[0];

                    if ( ! $parent->exists) {
                        $valid = false;
                    }

                    break;

                case 'Illuminate\Database\Eloquent\Relations\BelongsToMany':

                    $ids = [];

                    foreach ($leaf[0]->$key()->get() as $object) {
                        $model_key = $object->getKeyName();
                        $ids[]     = $object->$model_key;
                    }

                    $ids2 = [];

                    foreach ($prop as $pro) {
                        $model_key = $pro[0]->getKeyName();
                        $ids2[]    = $pro[0]->$model_key;
                    }

                    if (count(array_diff($ids, $ids2)) || count(array_diff($ids2, $ids))) {
                        $valid = false;
                    }

                    break;

                case 'Illuminate\Database\Eloquent\Relations\MorphTo':

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
