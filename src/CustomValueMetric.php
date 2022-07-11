<?php

namespace Sk4t0\CustomValueMetric;

use Laravel\Nova\Card;
use Laravel\Nova\Metrics\Value;

class CustomValueMetric extends Value
{
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
}
