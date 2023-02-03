<template>
    <CustomBaseValueMetric
        @selected="handleRangeSelected"
        :title="card.name"
        :help-text="card.helpText"
        :help-width="card.helpWidth"
        :multi="multi"
        :rangeGroupClass="rangeGroupClass"
        :metrics="metrics"
        :ranges="card.ranges"
        :format="format"
        :tooltip-format="tooltipFormat"
        :selected-range-key="selectedRangeKey"
        :loading="loading"
    />
</template>

<script>
import minimum from "./Includes/minimum"
import InteractsWithDates from "./Includes/InteractsWithDates"
import MetricBehavior  from "./Includes/MetricBehavior"
import CustomBaseValueMetric from "./Base/CustomBaseValueMetric";

export default {
    name: 'CustomValueMetric',

    mixins: [InteractsWithDates, MetricBehavior],

    components: {
        CustomBaseValueMetric
    },


    props: {
        card: {
            type: Object,
            required: true,
        },

        resourceName: {
            type: String,
            default: '',
        },

        resourceId: {
            type: [Number, String],
            default: '',
        },

        lens: {
            type: String,
            default: '',
        },
    },

    data: () => ({
        loading: true,
        copyable: false,
        format: '(0[.]00a)',
        tooltipFormat: '(0[.]00)',
        metrics: {},
    }),

    watch: {
        resourceId() {
            this.fetch()
        },
    },

    created() {
        if (this.hasRanges) {
            this.selectedRangeKey =
                this.card.selectedRangeKey || this.card.ranges[0].value
        }

        this.fetch()
    },

    mounted() {
        if (this.card && this.card.refreshWhenFiltersChange === true) {
            Nova.$on('filter-changed', this.fetch(this.selectedRangeKey))
        }
    },

    beforeUnmount() {
        if (this.card && this.card.refreshWhenFiltersChange === true) {
            Nova.$off('filter-changed', this.fetch(this.selectedRangeKey))
        }
    },

    methods: {
        handleRangeSelected(key) {
            this.selectedRangeKey = key
            var selectors = document.getElementsByClassName(this.rangeGroupClass);
            for (var i = 0; i < selectors.length; i++) {
                let selector = selectors[i].getElementsByTagName('select')[0];
                if (selector.value != key) {
                    selector.value = key;
                    const options = Array.from(selector.options);
                    const optionToSelect = options.find(item => item.value === key);
                    optionToSelect.selected = true;
                    var event = new Event('change');
                    selector.dispatchEvent(event);
                }
            }
            this.fetch()
        },

        fetch() {
            this.loading = true
            minimum(Nova.request().get(this.metricEndpoint, this.metricPayload())).then(
                ({
                     data: {
                         value: {
                             multi,
                             rangeGroupClass,
                             metrics,
                         },
                     },
                 }) => {
                    this.multi = multi || this.multi
                    this.rangeGroupClass = rangeGroupClass || this.rangeGroupClass
                    this.metrics = metrics
                    this.loading = false
                }
            )
        },
        metricPayload() {
            const payload = {
                params: {
                    timezone: this.userTimezone,
                },
            }

            if (
                !Nova.missingResource(this.resourceName) &&
                this.card &&
                this.card.refreshWhenFiltersChange === true
            ) {
                payload.params.filter =
                    this.$store.getters[`${this.resourceName}/currentEncodedFilters`]
            }

            if (this.hasRanges) {
                payload.params.range = this.selectedRangeKey
            }

            return payload
        },
    },

    computed: {
        hasRanges() {
            return this.card.ranges.length > 0
        },



        metricEndpoint() {
            const lens = this.lens !== '' ? `/lens/${this.lens}` : ''
            if (this.resourceName && this.resourceId) {
                return `/nova-api/${this.resourceName}${lens}/${this.resourceId}/metrics/${this.card.uriKey}`
            } else if (this.resourceName) {
                return `/nova-api/${this.resourceName}${lens}/metrics/${this.card.uriKey}`
            } else {
                return `/nova-api/metrics/${this.card.uriKey}`
            }
        },
    },
}
</script>
