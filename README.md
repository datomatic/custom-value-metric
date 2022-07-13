![Custom Value Metric-Dark](branding/dark.png#gh-dark-mode-only)![Custom Value Metric-Light](branding/light.png#gh-light-mode-only)
# Custom Value Metric
A custom value metric for Nova. It brings new functionalities to the usual Value Metric, like:
- Show not only the percentage of change between the current value and the previous one, but also the previous value itself.
- The ability to link the range dates of a group of metrics so that changing one you would change the ones on the other linked metrics too.
- The ability to show more than one value metric on the same card.

## Installation

PHP 8.1+ is required, as well as Nova 3+.

```sh
composer require sk4t0/custom-value-metric
```
## Usage
To use it you need to extend the `CustomValueMetric` class on your metric class, instead of the usual `Value` one. Than you can write your `calculate`, `ranges` etc... methods as you would do in a normal Value Metric.

```php
<?php

namespace App\Nova\Metrics;

use App\Models\User;
use Illuminate\Http\Request;
use Sk4t0\CustomValueMetric\CustomValueMetric;

class NewUsers extends CustomValueMetric
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->count($request, User::class);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => '30 Days',
            60 => '60 Days',
            365 => '365 Days',
            'TODAY' => 'Today',
            'MTD' => 'Month To Date',
            'QTD' => 'Quarter To Date',
            'YTD' => 'Year To Date',
        ];
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'new-users';
    }
}
```

This will result in metric that would be exactly as a classic Nova value metric, but showing also the value for the previous date range.

### Grouped ranges for multiple metrics cards
You can use this package to also link the date ranges of multiple metric cards. This means that you can write different metrics and, when you will change the range dates for one metric card of the group, it will change also for all the other metric cards of the same group. 
To do so you would just need to add `public bool $grouped = true;` and a name for the html class of the group `public string $classGroup = 'class-name';`

```php
<?php

namespace App\Nova\Metrics;

use App\Models\User;
use Illuminate\Http\Request;
use Sk4t0\CustomValueMetric\CustomValueMetric;

class NewUsers extends CustomValueMetric
{
	public bool $grouped = true;
	public string $classGroup = 'users';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->count($request, User::class);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => '30 Days',
            60 => '60 Days',
            365 => '365 Days',
            'TODAY' => 'Today',
            'MTD' => 'Month To Date',
            'QTD' => 'Quarter To Date',
            'YTD' => 'Year To Date',
        ];
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'new-users';
    }
}
```
and
```php
<?php

namespace App\Nova\Metrics;

use App\Models\User;
use Illuminate\Http\Request;
use Sk4t0\CustomValueMetric\CustomValueMetric;

class AverageUsersAge extends CustomValueMetric
{
	public bool $grouped = true;
	public string $classGroup = 'users';
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->average($request, User::class, 'age');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => '30 Days',
            60 => '60 Days',
            365 => '365 Days',
            'TODAY' => 'Today',
            'MTD' => 'Month To Date',
            'QTD' => 'Quarter To Date',
            'YTD' => 'Year To Date',
        ];
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'average-users-age';
    }
}
```
Doing this you would have 2 metrics that, if placed on the same dashboard or resource would have the date ranges linked and, changing the range on the first one it would change also on the second one.

### Put multiple metrics on the same metric card
You also have the ability to put more than one metric on the same metric card. This would change the graphic of the card to show the list of the metrics that you would add.
To do it you would need to add `public bool $multi = true;` . Here, in the `calculate` method, instead of using the classical `sum`, `count`, `average`, `min` and `max` methods, you will need to use the `addMetric` method, defining inside the name of the metric to show on the list and the operation, in this way: `addMetric($name, $model, $function, $column = null, $dateColumn = null)`. You can call the modifiers you were able to call on the `sum`, `count` ... methods, like `prefix`, `suffix`, `format`, `currency` etc and then, on the same object, another metric always using `addMetric`. After that, you will need to call the `multiAggregate` method passing it the request

```php
<?php

namespace App\Nova\Metrics;

use App\Models\User;
use Illuminate\Http\Request;
use Sk4t0\CustomValueMetric\CustomValueMetric;

class UserMetrics extends CustomValueMetric
{
	public bool $multi = true;
    /**
     * Calculate the value of the metric.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        $metrics = $this->addMetric('New Users', User::class, 'count')->suffix(' users')
        	->addMetric('Average age', User::class, 'average', 'age')->suffix(' years')->format('0');
        return $metrics->multiAggregate($request);
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            30 => '30 Days',
            60 => '60 Days',
            365 => '365 Days',
            'TODAY' => 'Today',
            'MTD' => 'Month To Date',
            'QTD' => 'Quarter To Date',
            'YTD' => 'Year To Date',
        ];
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'user-metrics';
    }
}
```