<?php

namespace Sk4t0\CustomValueMetric\Traits;

use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Nova;
use Sk4t0\CustomValueMetric\CustomValueResult;

trait LinkedRanges
{
    /**
     * Create a new value metric result.
     *
     * @param  mixed  $value
     * @return CustomValueResult
     */
    public function result($value)
    {
        return new CustomValueResult($value, $this->classGroup);
    }

    /**
     * Return a value result showing the growth of a model over a given time frame.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder|string  $model
     * @param  string  $function
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  string|null  $dateColumn
     * @return \Laravel\Nova\Metrics\ValueResult
     */
    protected function aggregate($request, $model, $function, $column = null, $dateColumn = null)
    {
        $query = $model instanceof Builder ? $model : (new $model)->newQuery();

        $column = $column ?? $query->getModel()->getQualifiedKeyName();

        if ($request->range === 'ALL') {
            return $this->result(
                round(with(clone $query)->{$function}($column), $this->precision)
            );
        }

        $timezone = Nova::resolveUserTimezone($request) ?? $request->timezone;

        $previousValue = round(with(clone $query)->whereBetween(
            $dateColumn ?? $query->getModel()->getQualifiedCreatedAtColumn(),
            array_map(function ($datetime) {
                return $this->asQueryDatetime($datetime);
            }, $this->previousRange($request->range, $timezone))
        )->{$function}($column) ?? 0, $this->precision);

        return $this->result(
            round(with(clone $query)->whereBetween(
                $dateColumn ?? $query->getModel()->getQualifiedCreatedAtColumn(),
                array_map(function ($datetime) {
                    return $this->asQueryDatetime($datetime);
                }, $this->currentRange($request->range, $timezone))
            )->{$function}($column) ?? 0, $this->precision)
        )->previous($previousValue);
    }
}
