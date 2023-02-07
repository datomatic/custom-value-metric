<template>
    <LoadingCard :loading="loading" class="px-6 py-4">
        <div class="h-6 flex items-center mb-4">
            <h3 class="mr-3 leading-tight text-sm font-bold">{{ title }}</h3>

            <HelpTextTooltip :text="helpText" :width="helpWidth"/>

            <SelectControl
                v-if="ranges.length > 0"
                class="ml-auto w-[6rem] flex-shrink-0"
                size="xxs"
                :class="rangeGroupClass"
                :options="ranges"
                @change="handleChange"
                :aria-label="__('Select Ranges')"
            />
        </div>
        <div class="grid grid-cols-2 gap text-xs">
            <div class="p-1" v-for="metric in this.metrics" :class="spanClass(metric)">
                <div v-if="multi">
                    <div class="text-70 font-bold uppercase mt-2 mb-1">{{ metric.name }}</div>
                    <div class="flex">
                        <div class="text-sm text-xl">{{ formattedValue(metric) }}</div>
                        <span v-if="metric.suffix" class="ml-2 text-sm font-bold text-80">{{
                                formattedSuffix(metric)
                            }}</span>
                    </div>
                    <div class="flex text-90 items-center text-80 " style="min-height: 24px;">

            <span v-if="increaseOrDecrease(metric) != 0">
              <span v-if="growthPercentage(metric) !== 0">
                <span class="text-base" :class="increaseOrDecrease(metric) < 0 ? 'text-red-700' : 'text-green-700'">
                    {{ increaseOrDecrease(metric) < 0 ? '-' : '+' }}{{ Math.round(growthPercentage(metric)) }}%
                </span>
                <span class="text-xs text-gray-400 whitespace-nowrap">
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
                    </div>
                </div>
                <div v-else>

                    <p class="flex items-center text-4xl mb-4">
                        {{ formattedValue(metric) }}
                        <span v-if="metric.suffix" class="ml-2 text-sm font-bold text-80">{{
                                formattedSuffix(metric)
                            }}</span>
                    </p>

                    <div>
                        <div class="flex items-center text-80 font-bold">
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
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </LoadingCard>
</template>

<script>
import singularOrPlural from "../Includes/singularOrPlural"
import CopiesToClipboard from "../Includes/CopiesToClipboard"

export default {
    name: 'CustomBaseValueMetric',

    mixins: [CopiesToClipboard],

    emits: ['selected'],

    props: {
        loading: {default: true},
        copyable: {default: false},
        title: {},
        helpText: {},
        helpWidth: {},
        maxWidth: {},
        multi: {default: false},
        metrics: {
            type: Array, default: () => []
        },
        rangeGroupClass: '',
        selectedRangeKey: [String, Number],
        ranges: {type: Array, default: () => []},
        format: {type: String, default: '(0[.]00a)'},
        tooltipFormat: {type: String, default: '(0[.]00)'},
    },

    data: () => ({copied: false}),

    methods: {
        handleChange(event) {
            let value = event?.target?.value || event

            this.$emit('selected', value)
        },

        handleCopyClick(metric) {
            if (metric.copyable) {
                this.copied = true
                this.copyValueToClipboard(this.tooltipFormattedValue)

                setTimeout(() => {
                    this.copied = false
                }, 2000)
            }
        },
        growthPercentage(metric) {
            return Math.abs(this.increaseOrDecrease(metric))
        },

        increaseOrDecrease(metric) {
            if (metric.previous == 0 || metric.previous == null || metric.value == 0)
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


        formattedValue(metric) {
            if (!this.isNullValue(metric)) {
                return (
                    metric.prefix + Nova.formatNumber(new String(metric.value), metric.format)
                )
            }

            return ''
        },


        formattedPreviousValue(metric) {
          if (!this.isNullValue(metric)) {
            return (
                metric.prefix + Nova.formatNumber(new String(metric.previous), metric.format)
            )
          }

          return ''
        },

        tooltipFormattedValue(metric) {
            if (!this.isNullValue(metric)) {
                return (
                    metric.prefix +
                    Nova.formatNumber(new String(metric.value), metric.tooltipFormat)
                )
            }

            return ''
        },
        spanClass(metric) {
            return metric.span ? 'col-span-' + metric.span : '';
        },

        formattedSuffix(metric) {
            if (metric.suffixInflection === false) {
                return metric.suffix
            }

            return singularOrPlural(metric.value, metric.suffix)
        },
    },
}
</script>
