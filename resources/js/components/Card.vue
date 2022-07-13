<template>
  <BaseValueMetric
    @selected="handleRangeSelected"
    :title="card.name"
    :help-text="card.helpText"
    :help-width="card.helpWidth"
    :multi="multi"
    :rangeGroupClass="rangeGroupClass"
    :metrics="metrics"
    :ranges="card.ranges"
    :selected-range-key="selectedRangeKey"
    :loading="loading"
  />
</template>

<script>
import { InteractsWithDates, Minimum } from 'laravel-nova'
import BaseValueMetric from './Base/ValueMetric'
import MetricBehavior from './MetricBehavior'

export default {
  name: 'ValueMetric',

  mixins: [InteractsWithDates, MetricBehavior],

  components: {
    BaseValueMetric,
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
    multi: false,
    selectedRangeKey: null,
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
  },

  mounted() {
    this.fetch(this.selectedRangeKey)
  },

  methods: {
    handleRangeSelected(key) {
      this.selectedRangeKey = key
      var selectors = document.getElementsByClassName(this.rangeGroupClass);
        for (var i = 0; i < selectors.length; i++) {
            if (selectors[i].value != key) {
              selectors[i].value = key;
              const options = Array.from(selectors[i].options);
              const optionToSelect = options.find(item => item.value ===key);
              optionToSelect.selected = true;
              var event = new Event('change');
              selectors[i].dispatchEvent(event);
            }
        }
      this.fetch()
    },

    fetch() {
      this.loading = true

      Minimum(Nova.request().get(this.metricEndpoint, this.metricPayload)).then(
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
  },

  computed: {
    hasRanges() {
      return this.card.ranges.length > 0
    },

    metricPayload() {
      const payload = {
        params: {
          timezone: this.userTimezone,
        },
      }

      if (this.hasRanges) {
        payload.params.range = this.selectedRangeKey
      }

      return payload
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
