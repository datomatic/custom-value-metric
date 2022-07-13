<?php

namespace Sk4t0\CustomValueMetric;

use JsonSerializable;

class MultiValueResult implements JsonSerializable
{
    public $results = [];

    public $serializables = [];

    public $lastResult = '';

    /**
     * Create a new value result instance.
     *
     * @param  mixed  $value
     * @return void
     */
    public function __construct($multi = false, $grouped = false, $className = 'default')
    {
        $this->results['multi'] = $multi;
        if ($grouped) {
            $this->results['rangeGroupClass'] = 'range-group-'.$className;
        }
    }

    public function addResult($result)
    {
        $this->serializables[$result->name] = $result;
        $this->lastResult = $result->name;
    }

    public function count()
    {
        return count($this->serializables);
    }

    /**
     * Indicate that the metric represents a dollar value.
     *
     * @param  string  $symbol
     * @return $this
     */
    public function dollars($symbol = '$')
    {
        return $this->currency($symbol);
    }

    /**
     * Indicate that the metric represents a currency value.
     *
     * @param  string  $symbol
     * @return $this
     */
    public function currency($symbol = '$')
    {
        return $this->prefix($symbol);
    }

    /**
     * Set the metric value prefix.
     *
     * @param  string  $prefix
     * @return $this
     */
    public function prefix($prefix)
    {
        $this->serializables[$this->lastResult]->prefix = $prefix;

        return $this;
    }

    /**
     * Set the metric value suffix.
     *
     * @param  string  $suffix
     * @return $this
     */
    public function suffix($suffix)
    {
        $this->serializables[$this->lastResult]->suffix = $suffix;

        return $this;
    }

    /**
     * Don't apply suffix inflections.
     *
     * @return $this
     */
    public function withoutSuffixInflection()
    {
        $this->serializables[$this->lastResult]->suffixInflection = false;

        return $this;
    }

    /**
     * Set the metric value formatting.
     *
     * @param  string  $format
     * @return $this
     */
    public function format($format)
    {
        $this->serializables[$this->lastResult]->format = $format;

        return $this;
    }

    /**
     * Sets the zeroResult value.
     *
     * @param  bool  $zeroResult
     * @return $this
     */
    public function allowZeroResult($zeroResult = true)
    {
        $this->serializables[$this->lastResult]->zeroResult = $zeroResult;

        return $this;
    }

    /**
     * Prepare the metric result for JSON serialization.
     *
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        foreach ($this->serializables as $key => $serializable) {
            $this->results['metrics'][] = $serializable->jsonSerialize();
        }
        return $this->results;
    }
}
