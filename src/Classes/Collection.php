<?php

namespace Limelight\Classes;

use ArrayAccess;
use Limelight\Helpers\Arr;
use Limelight\Classes\LimelightResults;

abstract class Collection implements ArrayAccess
{
    use Arr;

    /**
     * Collection methods adopted from Laravel Collection.
     * https://github.com/illuminate/support/blob/master/Collection.php
     */
    
    /**
     * Get all words.
     *
     * @return $this
     */
    public function all()
    {
        return $this->words;
    }
    
    /**
     * Count the number of items on the object.
     *
     * @return int
     */
    public function count()
    {
        return count($this->words);
    }

    /**
     * Get all items except for those with the specified keys.
     *
     * @param  mixed  $keys
     *
     * @return static
     */
    public function except($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return new static($this->text, $this->arrExcept($this->words, $keys), $this->pluginData);
    }

    /**
     * Run a filter over each of the items.
     *
     * @param  callable|null  $callback
     *
     * @return static
     */
    public function filter(callable $callback = null)
    {
        if ($callback) {
            $return = [];

            foreach ($this->words as $key => $value) {
                if ($callback($value, $key)) {
                    $return[$key] = $value;
                }
            }
            return new static($this->text, $return, $this->pluginData);
        }

        return new static($this->text, array_filter($this->words), $this->pluginData);
    }

    /**
     * Get the first item from the collection.
     *
     * @param  callable|null  $callback
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function first(callable $callback = null, $default = null)
    {
        return $this->arrFirst($this->words, $callback, $default);
    }

    /**
     * Remove an item from the collection by key.
     *
     * @param  string|array  $keys
     *
     * @return $this
     */
    public function forget($keys)
    {
        foreach ((array) $keys as $key) {
            $this->offsetUnset($key);
        }

        return $this;
    }

    /**
     * Concatenate values of a given key as a string.
     *
     * @param  string  $value
     * @param  string  $glue
     *
     * @return string
     */
    public function implode($value, $glue = null)
    {
        $first = $this->first();

        if (is_array($first) || is_object($first)) {
            return implode($glue, $this->pluck($value)->all());
        }

        return implode($value, $this->words);
    }

    /**
     * Determine if the collection is empty or not.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->words);
    }

    /**
     * Get the last item from the collection.
     *
     * @param  callable|null  $callback
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function last(callable $callback = null, $default = null)
    {
        return $this->arrLast($this->words, $callback, $default);
    }
    
    /**
     * Run a map over each of the items.
     *
     * @param  callable  $callback
     *
     * @return static
     */
    public function map(callable $callback)
    {
        $keys = array_keys($this->words);

        $items = array_map($callback, $this->words, $keys);

        return new static($this->text, array_combine($keys, $items), $this->pluginData);
    }

    /**
     * Merge the collection with the given items.
     *
     * @param  mixed  $items
     *
     * @return static
     */
    public function merge($items)
    {
        return new static($this->text, array_merge($this->words, $this->getArrayableItems($items)), $this->pluginData);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     *
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->words[$key];
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->words);
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->words[] = $value;
        } else {
            $this->words[$key] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string  $key
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->words[$key]);
    }

    /**
     * Get the items with the specified keys.
     *
     * @param  mixed  $keys
     *
     * @return static
     */
    public function only($keys)
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return new static($this->text, $this->arrOnly($this->words, $keys), $this->pluginData);
    }

    /**
     * Get the values of a given key.
     *
     * @param  string  $value
     * @param  string|null  $key
     *
     * @return static
     */
    public function pluck($value, $key = null)
    {
        return new static($this->text, $this->arrPluck($this->words, $value, $key), $this->pluginData);
    }

    /**
     * Get and remove the last item from the collection.
     *
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->words);
    }

    /**
     * Push an item onto the beginning of the collection.
     *
     * @param  mixed  $value
     * @param  mixed  $key
     *
     * @return $this
     */
    public function prepend($value, $key = null)
    {
        $this->words = $this->arrPrepend($this->words, $value, $key);

        return $this;
    }

    /**
     * Get and remove an item from the collection.
     *
     * @param  mixed  $key
     * @param  mixed  $default
     *
     * @return mixed
     */
    public function pull($key, $default = null)
    {
        return $this->arrPull($this->words, $key, $default);
    }

    /**
     * Push an item onto the end of the collection.
     *
     * @param  mixed  $value
     *
     * @return $this
     */
    public function push($value)
    {
        $this->offsetSet(null, $value);

        return $this;
    }

    /**
     * Get and remove the first item from the collection.
     *
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->words);
    }
}
