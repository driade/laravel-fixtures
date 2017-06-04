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
        $this->tree         = (new FixtureReferencesCreator($this->tree))->handle();
        $this->dependencies = (new FixtureDependenciesFinder($this->tree))->handle();

        $c = 0;

        while ( ! $this->process($this->tree)) {
            if ($c++ > 100) {
                throw new \Exception("Unable to resolve");
            }
        }

        $this->tree = (new FixtureClean($this->tree))->handle();

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

            $this->assignRelations($object, $parent, $relation);

            if ($this->canSaveObject($leaf)) {
                $object->save();
            }

            if ( ! $this->isComplete($leaf)) {
                $complete = 0;
            }

            foreach ($leaf as $key => $prop) {

                if (is_numeric($key)) {
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
            case 'Illuminate\Database\Eloquent\Relations\MorphTo':

                $parent->$relation()->associate($leaf);

                break;

            case 'Illuminate\Database\Eloquent\Relations\BelongsToMany':

                if ($leaf->exists) {

                    $ids = [];

                    $leaf_key = $leaf->getKeyName();

                    foreach ($parent->$relation()->get() as $object) { // Try lo leverage the number of queries here
                        $ids[] = $object->$leaf_key;
                    }

                    if ( ! in_array($leaf->$leaf_key, $ids)) {
                        $parent->$relation()->attach($leaf);
                    }
                }

                break;

            case 'Illuminate\Database\Eloquent\Relations\MorphMany':

                $parent->$relation()->save($leaf);

                break;
        }

    }

    protected function canSaveObject($leaf)
    {
        // if ($leaf[0]->exists) {
        //     return false;
        // }

        $valid     = true;
        $reference = $this->getReference($leaf);

        foreach ($leaf as $key => $prop) {

            if (is_numeric($key)) {
                continue;
            }

            $relation = get_class($leaf[0]->$key());

            switch ($relation) {
                case 'Illuminate\Database\Eloquent\Relations\BelongsTo':
                case 'Illuminate\Database\Eloquent\Relations\MorphTo':

                    if (is_array($prop)) {
                        $parent = $prop[0][0];
                    } else {
                        $parent = $prop;
                    }

                    if ($parent->exists) {
                        $leaf[0]->$key()->associate($parent);
                    } else {
                        $valid = false;
                    }

                    break;

            }

        }

        if (isset($this->dependencies[$reference])) {
            $valid = false;
        }

        return $valid;
    }

    protected function isComplete($leaf)
    {
        $valid = true;

        foreach ($leaf as $key => $prop) {

            if (is_numeric($key)) {
                continue;
            }

            $relation = get_class($leaf[0]->$key());

            switch ($relation) {

                case 'Illuminate\Database\Eloquent\Relations\BelongsTo':
                case 'Illuminate\Database\Eloquent\Relations\MorphTo':

                    if (is_array($prop)) {
                        $parent = $prop[0][0];
                    } else {
                        $parent = $prop;
                    }

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

            }

        }

        return $valid;
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
