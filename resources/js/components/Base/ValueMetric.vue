<template>
  <loading-card :loading="loading" class="px-6 py-4" style="height: auto;">
    <div class="flex mb-4">
      <h3 class="mr-3 text-base text-80 font-bold">{{ title }}</h3>

      <div v-if="helpText" class="absolute pin-r pin-b p-2 z-20">
        <tooltip trigger="click" placement="top-start">
          <icon
            type="help"
            viewBox="0 0 17 17"
            height="16"
            width="16"
            class="cursor-pointer text-60 -mb-1"
          />

          <tooltip-content
            slot="content"
            v-html="helpText"
            :max-width="helpWidth"
          />
        </tooltip>
      </div>

      <select
        v-if="ranges.length > 0"
        @change="handleChange"
        :class="rangeGroupClass"
        class="select-box-sm ml-auto min-w-24 h-6 text-xs appearance-none bg-40 pl-2 pr-6 active:outline-none active:shadow-outline focus:outline-none focus:shadow-outline"
      >
        <option
          v-for="option in ranges"
          :key="option.value"
          :value="option.value"
          :selected="selectedRangeKey == option.value"
        >
          {{ option.label }}
        </option>
      </select>
    </div>



    <div v-for="metric in this.metrics">

      <div v-if="multi">
        <p class="flex text-sm items-center text-80 font-bold" style="min-height: 24px;">
          <span class="text-90">{{ metric.name }}:</span> &nbsp; {{ formattedValue(metric) }}
          <span v-if="metric.suffix" class="ml-2 text-sm font-bold text-80">{{
            formattedSuffix(metric)
          }}</span>, &nbsp;
            <svg
              v-if="increaseOrDecreaseLabel(metric) == 'Decrease'"
              xmlns="http://www.w3.org/2000/svg"
              class="text-danger stroke-current mr-2"
              width="24"
              height="24"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"
              />
            </svg>
            <svg
              v-if="increaseOrDecreaseLabel(metric) == 'Increase'"
              class="text-success stroke-current mr-2"
              width="24"
              height="24"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
              />
            </svg>

            <span v-if="increaseOrDecrease(metric) != 0">
              <span v-if="growthPercentage(metric) !== 0">
                {{ growthPercentage(metric) }}%

                <span>
                  ({{ formattedPreviousValue(metric) }})
                </span>
              </span>

              <span v-else> {{ __('No Increase') }} </span>
            </span>

            <span v-else>
              <span v-if="metric.previous == '0' && metric.value != '0'">
                {{ __('No Prior Data') }}
              </span>

              <span v-if="metric.value == '0' && metric.previous != '0' && !metric.zeroResult">
                {{ __('No Current Data') }}
              </span>

              <span v-if="metric.value == '0' && metric.previous == '0' && !metric.zeroResult">
                {{ __('No Data') }}
              </span>
            </span>
        </p>
      </div>

      <div v-else>

        <p class="flex items-center text-4xl mb-4">
          {{ formattedValue(metric) }}
          <span v-if="metric.suffix" class="ml-2 text-sm font-bold text-80">{{
            formattedSuffix(metric)
          }}</span>
        </p>

        <div>
          <p class="flex items-center text-80 font-bold">
            <svg
              v-if="increaseOrDecreaseLabel(metric) == 'Decrease'"
              xmlns="http://www.w3.org/2000/svg"
              class="text-danger stroke-current mr-2"
              width="24"
              height="24"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"
              />
            </svg>
            <svg
              v-if="increaseOrDecreaseLabel(metric) == 'Increase'"
              class="text-success stroke-current mr-2"
              width="24"
              height="24"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
              />
            </svg>

            <span v-if="increaseOrDecrease(metric) != 0">
              <span v-if="growthPercentage(metric) !== 0">
                {{ growthPercentage(metric) }}%
                {{ __(increaseOrDecreaseLabel(metric)) }}

                <span>
                  ({{ formattedPreviousValue(metric) }})
                </span>
              </span>

              <span v-else> {{ __('No Increase') }} </span>
            </span>

            <span v-else>
              <span v-if="metric.previous == '0' && metric.value != '0'">
                {{ __('No Prior Data') }}
              </span>

              <span v-if="metric.value == '0' && metric.previous != '0' && !metric.zeroResult">
                {{ __('No Current Data') }}
              </span>

              <span v-if="metric.value == '0' && metric.previous == '0' && !metric.zeroResult">
                {{ __('No Data') }}
              </span>
            </span>
          </p>
        </div>

      </div>

    </div>





  </loading-card>
</template>

<script>
import { SingularOrPlural } from 'laravel-nova'

export default {
  name: 'BaseValueMetric',
  props: {
    loading: { default: true },
    title: {},
    helpText: {},
    helpWidth: {},
    maxWidth: {},
    multi: { default: false},
    metrics: {
      type: Array, default: () => []
    },
    rangeGroupClass: '',
    selectedRangeKey: [String, Number],
    ranges: { type: Array, default: () => [] },
  },

  methods: {
    handleChange(event) {
      this.$emit('selected', event.target.value)
    },

    growthPercentage(metric) {
      return Math.abs(this.increaseOrDecrease(metric))
    },

    increaseOrDecrease(metric) {
      if (metric.previous == 0 || metric.previous == null)
        return 0

      return (((metric.value - metric.previous) / metric.previous) * 100).toFixed(2)
    },

    increaseOrDecreaseLabel(metric) {
      switch (Math.sign(this.increaseOrDecrease(metric))) {
        case 1:
          return 'Increase'
        case 0:
          return 'Constant'
        case -1:
          return 'Decrease'
      }
    },

    sign(metric) {
      switch (Math.sign(this.increaseOrDecrease(metric))) {
        case 1:
          return '+'
        case 0:
          return ''
        case -1:
          return '-'
      }
    },

    isNullValue(metric) {
      return metric.value == null
    },

    isNullPreviousValue(metric) {
      return metric.previous == null
    },

    formattedValue(metric) {
      if (!this.isNullValue(metric)) {
        return (
          metric.prefix + Nova.formatNumber(new String(metric.value), metric.format)
        )
      }

      return ''
    },

    formattedPreviousValue(metric) {
      if (!this.isNullPreviousValue(metric)) {
        return (
          metric.prefix + Nova.formatNumber(new String(metric.previous), metric.format)
        )
      }

      return ''
    },

    formattedSuffix(metric) {
      if (metric.suffixInflection === false) {
        return metric.suffix
      }

      return SingularOrPlural(metric.value, metric.suffix)
    },
  },
}
</script>
