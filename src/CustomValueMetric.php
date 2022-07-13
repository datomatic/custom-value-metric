<?php

namespace Sk4t0\CustomValueMetric;

use Laravel\Nova\Nova;
use Laravel\Nova\Metrics\Value;
use Illuminate\Database\Eloquent\Builder;
use Sk4t0\CustomValueMetric\MultiValueResult;
use Sk4t0\CustomValueMetric\CustomValueResult;

class CustomValueMetric extends Value
{
    public bool $grouped = false;

    public bool $multi = false;

    public string $classGroup = 'default';

    public $metrics = [];

    public $lastMetric = '';

    public $results = [];
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
    public function component()
    {
        return 'custom-value-metric';
    }

    /**
     * Return a value result showing the growth of an count aggregate over time.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  string|null  $dateColumn
     * @return \Laravel\Nova\Metrics\ValueResult
     */
    public function count($request, $model, $column = null, $dateColumn = null)
    {
        $this->addMetric($this->name(), $model, 'count', $column, $dateColumn);
        return $this->multiAggregate($request);
    }

    /**
     * Return a value result showing the growth of an average aggregate over time.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string|null  $dateColumn
     * @return \Laravel\Nova\Metrics\ValueResult
     */
    public function average($request, $model, $column, $dateColumn = null)
    {
        $this->addMetric($this->name(), $model, 'average', $column, $dateColumn);
        return $this->multiAggregate($request);
    }

    /**
     * Return a value result showing the growth of a sum aggregate over time.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string|null  $dateColumn
     * @return \Laravel\Nova\Metrics\ValueResult
     */
    public function sum($request, $model, $column, $dateColumn = null)
    {
        $this->addMetric($this->name(), $model, 'sum', $column, $dateColumn);
        return $this->multiAggregate($request);
    }

    /**
     * Return a value result showing the growth of a maximum aggregate over time.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string|null  $dateColumn
     * @return \Laravel\Nova\Metrics\ValueResult
     */
    public function max($request, $model, $column, $dateColumn = null)
    {
        $this->addMetric($this->name(), $model, 'max', $column, $dateColumn);
        return $this->multiAggregate($request);
    }

    /**
     * Return a value result showing the growth of a minimum aggregate over time.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  \Illuminate\Database\Query\Expression|string  $column
     * @param  string|null  $dateColumn
     * @return \Laravel\Nova\Metrics\ValueResult
     */
    public function min($request, $model, $column, $dateColumn = null)
    {
        $this->addMetric($this->name(), $model, 'min', $column, $dateColumn);
        return $this->multiAggregate($request);
    }

    /**
     * Return a value result showing the growth of a model over a given time frame.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return MultiValueResult
     */
    public function multiAggregate($request)
    {
        $results = new MultiValueResult($this->multi, $this->grouped, $this->classGroup);
        foreach ($this->metrics as $name => $metric) {
            $query = $metric['model'] instanceof Builder ? $metric['model'] : (new $metric['model'])->newQuery();

            $column = $metric['column'] ?? $query->getModel()->getQualifiedKeyName();

            if ($request->range === 'ALL') {
                $result = $this->multiResult(
                    round(with(clone $query)->{$metric['function']}($column), $this->precision),
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
            )->{$metric['function']}($column) ?? 0, $this->precision);

            $result = $this->multiResult(
                round(with(clone $query)->whereBetween(
                    $metric['dateColumn'] ?? $query->getModel()->getQualifiedCreatedAtColumn(),
                    array_map(function ($datetime) {
                        return $this->asQueryDatetime($datetime);
                    }, $this->currentRange($request->range, $timezone))
                )->{$metric['function']}($column) ?? 0, $this->precision),
                $name,
                $metric
            )->previous($previousValue);
            $results->addResult($result);
        }
        return $results;
    }

    /**
    * @param string $name 
    * @param  \Illuminate\Database\Eloquent\Builder|string  $model
    * @param  string  $function
    * @param  \Illuminate\Database\Query\Expression|string|null  $column
    * @param  string|null  $dateColumn
    */
    public function addMetric($name, $model, $function, $column = null, $dateColumn = null)
    {
        $this->metrics[$name] = ['model' => $model, 'function' => $function, 'column' => $column, 'dateColumn' => $dateColumn];
        $this->lastMetric = $name;
        return $this;
    }

    /**
     * Create a new value metric result.
     *
     * @param  mixed  $value
     * @return CustomValueResult
     */
    public function multiResult($value, $name, $metric)
    {
        return new CustomValueResult($value, $name, $metric);
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
        $this->metrics[$this->lastMetric]['prefix'] = $prefix;

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
