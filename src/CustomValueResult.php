<?php

namespace Datomatic\CustomValueMetric;

use JsonSerializable;
use Stringable;
use Laravel\Nova\Metrics\TransformsResults;

class CustomValueResult implements JsonSerializable
{
    use TransformsResults;

    /**
     * The value of the result.
     *
     * @var int|float|numeric-string|null
     */
    public $value;

    /**
     * The previous value.
     *
     * @var int|float|numeric-string|null
     */
    public $previous;

    /**
     * The previous value label.
     *
     * @var \Stringable|string
     */
    public $previousLabel;

    /**
     * The metric value prefix.
     *
     * @var \Stringable|string
     */
    public $prefix;

    /**
     * The metric value suffix.
     *
     * @var \Stringable|string
     */
    public $suffix;

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
     * Determines whether a value of 0 counts as "No Current Data".
     *
     * @var bool
     */
    public $zeroResult = false;

    /**
     * Indicates if the metric value is copyable inside Nova.
     *
     * @var bool
     */
    public $copyable = false;

    /**
     * The metric tooltip value formatting.
     *
     * @var string
     */
    public $tooltipFormat = '0';

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
        $this->prefix = $metric['prefix'] ?? null;
        $this->suffix = $metric['suffix'] ?? null;
        $this->suffixInflection = $metric['suffixInflection'] ?? null;
        $this->format = $metric['format'] ?? null;
        $this->span = $metric['span'] ?? null;
        $this->zeroResult = $metric['zeroResult'] ?? null;
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
     * Allow the metric value to be copyable to the clipboard inside Nova.
     *
     * @return $this
     */
    public function copyable()
    {
        $this->copyable = true;

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
            'name' => $this->name,
            'span' => $this->span,

            'copyable' => $this->copyable,
            'format' => $this->format,
            'prefix' => $this->prefix,
            'previous' =>  $this->resolveTransformedValue($this->previous),
            'previousLabel' => $this->previousLabel,
            'suffix' => $this->suffix,
            'suffixInflection' => $this->suffixInflection,
            'tooltipFormat' => $this->tooltipFormat,
            'value' => $this->resolveTransformedValue($this->value),
            'zeroResult' => $this->zeroResult,
        ];
    }
}
