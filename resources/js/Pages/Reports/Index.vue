<template>
  <div>
    <Head title="Reports" />
    <h1 class="mb-8 text-3xl font-bold">Reports</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <!-- Transactions Report Card -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-4">
          <div>
            <h2 class="text-xl font-semibold mb-2">Payments by Bakery</h2>
            <p class="text-gray-600">View payments received from each bakery</p>
          </div>
          <svg class="w-8 h-8 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
        <div class="mb-4 h-64">
          <canvas ref="transactionsChart"></canvas>
        </div>
        <div class="flex gap-2">
          <button @click="generateReport('transactions', 'pdf')" class="btn-kingbaker">
            Export PDF
          </button>
          <button @click="generateReport('transactions', 'xlsx')" class="btn-kingbaker">
            Export Excel
          </button>
        </div>
      </div>

      <!-- Expenses Report Card -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-4">
          <div>
            <h2 class="text-xl font-semibold mb-2">Daily Expenses</h2>
            <p class="text-gray-600">Track daily expense trends</p>
          </div>
          <svg class="w-8 h-8 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
        </div>
        <div class="mb-4 h-64">
          <canvas ref="expensesChart"></canvas>
        </div>
        <div class="flex gap-2">
          <button @click="generateReport('expenses', 'pdf')" class="btn-kingbaker">
            Export PDF
          </button>
          <button @click="generateReport('expenses', 'xlsx')" class="btn-kingbaker">
            Export Excel
          </button>
        </div>
      </div>

      <!-- Summary Reports Card -->
      <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-start mb-4">
          <div>
            <h2 class="text-xl font-semibold mb-2">Net Cash Flow Summary</h2>
            <p class="text-gray-600">View net cash flow over time</p>
          </div>
          <svg class="w-8 h-8 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
        </div>
        <div class="mb-4">
          <select v-model="summaryType" class="form-select w-full mb-4">
            <option value="weekly">Weekly</option>
            <option value="monthly">Monthly</option>
            <option value="yearly">Yearly</option>
          </select>
          <div class="h-64">
            <canvas ref="summaryChart"></canvas>
          </div>
        </div>
        <div class="flex gap-2">
          <button @click="generateReport('summary', 'pdf')" class="btn-kingbaker">
            Export PDF
          </button>
          <button @click="generateReport('summary', 'xlsx')" class="btn-kingbaker">
            Export Excel
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import Chart from 'chart.js/auto'

export default {
  components: {
    Head,
  },
  layout: Layout,
  props: {
    transactionStats: Object,
    expenseStats: Object,
    summaryStats: Object,
  },
  data() {
    return {
      summaryType: 'monthly',
      charts: {
        transactions: null,
        expenses: null,
        summary: null,
      }
    }
  },
  watch: {
    summaryType() {
      this.updateSummaryChart()
    }
  },
  mounted() {
    this.initCharts()
  },
  methods: {
    getChartOptions() {
      return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'top',
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              callback: function(value) {
                return 'GMD ' + value.toLocaleString()
              }
            }
          }
        }
      }
    },
    initCharts() {
      // Transactions Chart
      this.charts.transactions = new Chart(this.$refs.transactionsChart, {
        type: 'bar',
        data: {
          labels: this.transactionStats.labels,
          datasets: [{
            label: 'Payments by Bakery',
            data: this.transactionStats.data,
            backgroundColor: 'rgba(155, 103, 42, 0.5)',
            borderColor: 'rgb(155, 103, 42)',
            borderWidth: 1
          }]
        },
        options: this.getChartOptions()
      })

      // Expenses Chart
      this.charts.expenses = new Chart(this.$refs.expensesChart, {
        type: 'line',
        data: {
          labels: this.expenseStats.labels,
          datasets: [{
            label: 'Daily Expenses',
            data: this.expenseStats.data,
            borderColor: 'rgb(155, 103, 42)',
            backgroundColor: 'rgba(155, 103, 42, 0.1)',
            fill: true,
            tension: 0.4
          }]
        },
        options: this.getChartOptions()
      })

      this.updateSummaryChart()
    },
    updateSummaryChart() {
      if (this.charts.summary) {
        this.charts.summary.destroy()
      }

      this.charts.summary = new Chart(this.$refs.summaryChart, {
        type: 'bar',
        data: {
          labels: this.summaryStats[this.summaryType].labels,
          datasets: [{
            label: `${this.summaryType.charAt(0).toUpperCase() + this.summaryType.slice(1)} Net Cash Flow`,
            data: this.summaryStats[this.summaryType].data,
            backgroundColor: 'rgba(155, 103, 42, 0.5)',
            borderColor: 'rgb(155, 103, 42)',
            borderWidth: 1
          }]
        },
        options: this.getChartOptions()
      })
    },
    generateReport(type, format) {
      window.location.href = `/reports/generate/${type}/${this.summaryType}/${format}`
    }
  }
}
</script>
