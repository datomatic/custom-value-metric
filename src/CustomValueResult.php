<?php

namespace Datomatic\CustomValueMetric;

use JsonSerializable;

class CustomValueResult implements JsonSerializable
{
    /**
     * The value of the result.
     *
     * @var mixed
     */
    public $value;

    /**
     * The previous value.
     *
     * @var mixed
     */
    public $previous;

    /**
     * The previous value label.
     *
     * @var string
     */
    public $previousLabel = '';

    /**
     * The metric value prefix.
     *
     * @var string
     */
    public $prefix = '';

    /**
     * The metric value suffix.
     *
     * @var string
     */
    public $suffix = '';

    /**
     * Whether to run inflection on the suffix.
     *
     * @var bool
     */
    public $suffixInflection = true;

    /**
     * The metric value formatting.
     *
     * @var string
     */
    public $format = '0';

    /**
     * The metric span.
     *
     * @var string|int
     */
    public $span = 1;

    /**
     * Determines whether a value of 0 counts as "No Current Data".
     *
     * @var bool
     */
    public $zeroResult = false;

    /**
     * Create a new value result instance.
     *
     * @param  mixed  $value
     * @return void
     */
    public function __construct($value, $name, $metric)
    {
        $this->value = $value;
        $this->name = $name;
        isset($metric['prefix']) ? $this->prefix = $metric['prefix'] : null;
        isset($metric['suffix']) ? $this->suffix = $metric['suffix'] : null;
        isset($metric['suffixInflection']) ? $this->suffixInflection = $metric['suffixInflection'] : null;
        isset($metric['format']) ? $this->format = $metric['format'] : null;
        isset($metric['span']) ? $this->span = $metric['span'] : null;
        isset($metric['zeroResult']) ? $this->zeroResult = $metric['zeroResult'] : null;
    }

    /**
     * Set the previous value for the metric.
     *
     * @param  mixed  $previous
     * @param  string  $label
     * @return $this
     */
    public function previous($previous, $label = null)
    {
        $this->previous = $previous;
        $this->previousLabel = $label;

        return $this;
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
        $this->prefix = $prefix;

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
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * Don't apply suffix inflections.
     *
     * @return $this
     */
    public function withoutSuffixInflection()
    {
        $this->suffixInflection = false;

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
        $this->format = $format;

        return $this;
    }

    /**
     * Set the metric span.
     *
     * @param  string|int  $span
     * @return $this
     */
    public function span($span)
    {
        $this->span = $span;

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
        $this->zeroResult = $zeroResult;

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
        return [
            'value' => $this->value,
            'name' => $this->name,
            'previous' => $this->previous,
            'previousLabel' => $this->previousLabel,
            'prefix' => $this->prefix,
            'suffix' => $this->suffix,
            'suffixInflection' => $this->suffixInflection,
            'format' => $this->format,
            'span' => $this->span,
            'zeroResult' => $this->zeroResult,
        ];
    }
}
