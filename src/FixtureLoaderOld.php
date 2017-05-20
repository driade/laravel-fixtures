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

        $this->references = [];

        $this->output = $this->process(null, $this->fixtures);
    }

    protected function process($parent, $datas, $relation = '')
    {
        $wasOne = false;

        if ( ! isset($datas[0])) {
            return [];
        }

        if ( ! is_array($datas[0])) {
            $datas  = [$datas];
            $wasOne = true;
        }

        $output = [];

        foreach ($datas as $data) {

            if ( ! isset($data[0])) {
                continue;
            }

            $object = $data[0];

            if (mb_substr($object, 0, 2) === '::') {
                return $this->references[$object];
            }

            $props = [];

            $reference = null;

            foreach ($data as $index => $prop) {
                if ($index === 0) {
                    continue;
                }

                if ( ! is_array($prop)) {
                    if (mb_substr($prop, 0, 2) !== '::') {
                        $props[$index] = $prop;
                    } else {
                        $reference = $prop;
                    }
                }
            }

            $myobject = new $object;

            // Avoid mass assignment issues
            foreach ($props as $key => $value) {
                $myobject->setAttribute($key, $value);
            }

            if ($reference !== null) {
                $this->references[$reference] = $myobject;
            }

            foreach ($data as $index => $prop) {

                if ($index === 0) {
                    continue;
                }

                if (is_array($prop)) {

                    $children = $this->process($myobject, $prop, $index);

                    if ( ! is_array($children)) {
                        $children = [$children];
                    }

                    if ($relation !== '') {

                        $parent->save();

                        $rel = $parent->$relation();

                        switch (get_class($rel)) {

                            case 'Illuminate\Database\Eloquent\Relations\HasMany':
                            case 'Illuminate\Database\Eloquent\Relations\HasOne':

                                foreach ($children as $child) {

                                    $parent->$relation()->save($myobject);
                                }

                                break;

                            case 'Illuminate\Database\Eloquent\Relations\BelongsTo':

                                foreach ($children as $child) {
                                    $parent->$relation()->associate($myobject);
                                    $myobject->save();
                                }

                                break;

                            default:
                                echo get_class($rel);
                                die();
                        }
                    }
                }
            }
        }

        $output[] = $myobject;

        if ($wasOne) {
            $output = array_pop($output);
        }

        return $output;
    }
}
