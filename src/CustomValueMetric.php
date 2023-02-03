<?php

namespace Datomatic\CustomValueMetric;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use Laravel\Nova\Metrics\Value;
use Laravel\Nova\Nova;

class CustomValueMetric extends Value
{
    public bool $grouped = false;

    public bool $multi = false;

    public string $classGroup = 'default';

    public array $metrics = [];

    public string $lastMetric = '';

    public array $results = [];

    /**
     * The width of the card (1/3, 1/2, or full).
     *
     * @var string
     */
    public $width = '1/3';

    /**
     * Get the component name for the element.
     *
     * @return string
     */
    public function component(): string
    {
        return 'custom-value-metric';
    }

    /**
     * Return a value result showing the growth of an count aggregate over time.
     *
     * @param  Request  $request
     * @param  Builder|string  $model
     * @param  Expression|string|null  $column
     * @param  string|null  $dateColumn
     * @return MultiValueResult
     */
    public function count($request, $model, $column = null, $dateColumn = null): MultiValueResult
    {
        $this->addMetric($this->name(), $model, 'count', $column, $dateColumn);

        return $this->multiAggregate($request);
    }

    /**
     * Return a value result showing the growth of an average aggregate over time.
     *
     * @param  Request  $request
     * @param  Builder|string  $model
     * @param  Expression|string  $column
     * @param  string|null  $dateColumn
     * @return MultiValueResult
     */
    public function average($request, $model, $column, $dateColumn = null): MultiValueResult
    {
        $this->addMetric($this->name(), $model, 'average', $column, $dateColumn);

        return $this->multiAggregate($request);
    }

    /**
     * Return a value result showing the growth of a sum aggregate over time.
     *
     * @param  Request  $request
     * @param  Builder|string  $model
     * @param  Expression|string  $column
     * @param  string|null  $dateColumn
     * @return MultiValueResult
     */
    public function sum($request, $model, $column, $dateColumn = null): MultiValueResult
    {
        $this->addMetric($this->name(), $model, 'sum', $column, $dateColumn);

        return $this->multiAggregate($request);
    }

    /**
     * Return a value result showing the growth of a maximum aggregate over time.
     *
     * @param  Request  $request
     * @param  Builder|string  $model
     * @param  Expression|string  $column
     * @param  string|null  $dateColumn
     * @return MultiValueResult
     */
    public function max($request, $model, $column, $dateColumn = null): MultiValueResult
    {
        $this->addMetric($this->name(), $model, 'max', $column, $dateColumn);

        return $this->multiAggregate($request);
    }

    /**
     * Return a value result showing the growth of a minimum aggregate over time.
     *
     * @param  Request  $request
     * @param  Builder|string  $model
     * @param  Expression|string  $column
     * @param  string|null  $dateColumn
     * @return MultiValueResult
     */
    public function min($request, $model, $column, $dateColumn = null): MultiValueResult
    {
        $this->addMetric($this->name(), $model, 'min', $column, $dateColumn);

        return $this->multiAggregate($request);
    }

    /**
     * Return a value result showing the growth of a model over a given time frame.
     *
     * @param  Request  $request
     * @return MultiValueResult
     */
    public function multiAggregate(Request $request): MultiValueResult
    {
        $results = new MultiValueResult($this->multi, $this->grouped, $this->classGroup);
        foreach ($this->metrics as $name => $metric) {
            $query = $metric['model'] instanceof Builder ? $metric['model'] : (new $metric['model'])->newQuery();

            $column = $metric['column'] ?? $query->getModel()->getQualifiedKeyName();

            if ($request->range === 'ALL') {
                $result = $this->multiResult(
                    round(with(clone $query)->{$metric['function']}($column), $this->roundingPrecision),
                    $name,
                    $metric
                );
                $results->addResult($result);

                continue;
            }

            $timezone = Nova::resolveUserTimezone($request) ?? $request->timezone;

            $previousValue = round(with(clone $query)->whereBetween(
                $metric['dateColumn'] ?? $query->getModel()->getQualifiedCreatedAtColumn(),
                array_map(function ($datetime) {
                    return $this->asQueryDatetime($datetime);
                }, $this->previousRange($request->range, $timezone))
            )->{$metric['function']}($column) ?? 0, $this->roundingPrecision);

            $result = $this->multiResult(
                round(with(clone $query)->whereBetween(
                    $metric['dateColumn'] ?? $query->getModel()->getQualifiedCreatedAtColumn(),
                    array_map(function ($datetime) {
                        return $this->asQueryDatetime($datetime);
                    }, $this->currentRange($request->range, $timezone))
                )->{$metric['function']}($column) ?? 0, $this->roundingPrecision),
                $name,
                $metric
            )->previous($previousValue);
            $results->addResult($result);
        }

        return $results;
    }

    /**
     * @param  string  $name
     * @param  Builder|string  $model
     * @param  string  $function
     * @param  null  $column
     * @param  null  $dateColumn
     * @return CustomValueMetric
     */
    public function addMetric(string $name, $model, $function, $column = null, $dateColumn = null): static
    {
        $this->metrics[$name] = ['model' => $model, 'function' => $function, 'column' => $column, 'dateColumn' => $dateColumn];
        $this->lastMetric = $name;

        return $this;
    }

    /**
     * Create a new value metric result.
     *
     * @param  mixed  $value
     * @param $name
     * @param $metric
     * @return CustomValueResult
     */
    public function multiResult($value, $name, $metric): CustomValueResult
    {
        return new CustomValueResult($value, $name, $metric);
    }

    /**
     * Indicate that the metric represents a dollar value.
     *
     * @param  string  $symbol
     * @return $this
     */
    public function dollars(string $symbol = '$'): static
    {
        return $this->currency($symbol);
    }

    /**
     * Indicate that the metric represents a currency value.
     *
     * @param  string  $symbol
     * @return $this
     */
    public function currency(string $symbol = '$'): static
    {
        return $this->prefix($symbol);
    }

    /**
     * Set the metric value prefix.
     *
     * @param  string  $prefix
     * @return $this
     */
    public function prefix($prefix): static
    {
        $this->metrics[$this->lastMetric]['prefix'] = $prefix;

        return $this;
    }

    /**
     * Set the metric value suffix.
     *
     * @param  string  $suffix
     * @return $this
     */
    public function suffix($suffix): static
    {
        $this->metrics[$this->lastMetric]['suffix'] = $suffix;

        return $this;
    }

    /**
     * Don't apply suffix inflections.
     *
     * @return $this
     */
    public function withoutSuffixInflection()
    {
        $this->metrics[$this->lastMetric]['suffixInflection'] = false;

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
        $this->metrics[$this->lastMetric]['format'] = $format;

        return $this;
    }

    /**
     * Set the metric span.
     *
     * @param  int|string  $span
     * @return $this
     */
    public function span(int|string $span): static
    {
        $this->metrics[$this->lastMetric]['span'] = $span;

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
        $this->metrics[$this->lastMetric]['zeroResult'] = $zeroResult;

        return $this;
    }
}
