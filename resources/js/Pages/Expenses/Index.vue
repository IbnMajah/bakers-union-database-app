<template>
  <div>
    <Head title="Expenses" />
    <h1 class="mb-8 text-3xl font-bold">Expenses</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <Link class="btn-brand" href="/expenses/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Expense</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Date</th>
          <th class="pb-4 pt-6 px-6">Bakery</th>
          <th class="pb-4 pt-6 px-6">Category</th>
          <th class="pb-4 pt-6 px-6">Amount</th>
          <th class="pb-4 pt-6 px-6">Receipt #</th>
          <th class="pb-4 pt-6 px-6">Status</th>
          <th class="pb-4 pt-6 px-6">Created By</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Actions</th>
        </tr>
        <tr v-for="expense in expenses.data" :key="expense.id" class="hover:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/expenses/${expense.id}/edit`">
              {{ expense.expense_date }}
              <icon v-if="expense.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/expenses/${expense.id}/edit`">
              {{ expense.bakery }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/expenses/${expense.id}/edit`">
              {{ expense.category }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/expenses/${expense.id}/edit`">
              GMD {{ expense.amount.toLocaleString() }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/expenses/${expense.id}/edit`">
              {{ expense.receipt_number }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/expenses/${expense.id}/edit`">
              <div :class="{
                'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                'bg-yellow-100 text-yellow-800': expense.status === 'pending',
                'bg-green-100 text-green-800': expense.status === 'approved',
                'bg-red-100 text-red-800': expense.status === 'rejected'
              }">
                {{ expense.status }}
              </div>
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/expenses/${expense.id}/edit`">
              {{ expense.creator }}
            </Link>
          </td>
          <td class="border-t w-px">
            <div v-if="$page.props.auth.user.is_admin" class="flex items-center px-4">
              <button
                v-if="expense.status === 'pending'"
                @click="approve(expense.id)"
                class="mr-2 px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600"
                title="Approve"
              >
                <CheckIcon class="w-4 h-4" />
              </button>
              <button
                v-if="expense.status === 'pending'"
                @click="reject(expense.id)"
                class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
                title="Reject"
              >
                <XMarkIcon class="w-4 h-4" />
              </button>
            </div>
          </td>
        </tr>
        <tr v-if="expenses.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="8">No expenses found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="expenses.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import Layout from '@/Shared/Layout.vue'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import pickBy from 'lodash/pickBy'
import { CheckIcon, XMarkIcon } from "@heroicons/vue/24/outline"

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
    CheckIcon,
    XMarkIcon,
  },
  layout: Layout,
  props: {
    filters: Object,
    expenses: Object,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/expenses', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
    approve(expenseId) {
      if (confirm('Are you sure you want to approve this expense?')) {
        this.$inertia.put(`/expenses/${expenseId}/approve`)
      }
    },
    reject(expenseId) {
      if (confirm('Are you sure you want to reject this expense?')) {
        this.$inertia.put(`/expenses/${expenseId}/reject`)
      }
    },
  },
}
</script>