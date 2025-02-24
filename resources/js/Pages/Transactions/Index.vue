<template>
  <div>
    <Head title="Transactions" />
    <h1 class="mb-8 text-3xl font-bold">Transactions</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <div class="mb-4">
          <label class="block text-gray-700">Bakery:</label>
          <select v-model="form.bakery_id" class="form-select mt-1 w-full">
            <option :value="null">All Bakeries</option>
            <option v-for="bakery in bakeries" :key="bakery.id" :value="bakery.id">
              {{ bakery.name }}
            </option>
          </select>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700">Account:</label>
          <select v-model="form.account_id" class="form-select mt-1 w-full">
            <option :value="null">All Accounts</option>
            <option v-for="account in accounts" :key="account.id" :value="account.id">
              {{ account.name }}
            </option>
          </select>
        </div>
        <div>
          <label class="block text-gray-700">Trashed:</label>
          <select v-model="form.trashed" class="form-select mt-1 w-full">
            <option :value="null" />
            <option value="with">With Trashed</option>
            <option value="only">Only Trashed</option>
          </select>
        </div>
      </search-filter>

    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Date</th>
          <th class="pb-4 pt-6 px-6">Account</th>
          <th class="pb-4 pt-6 px-6">Bakery</th>
          <th class="pb-4 pt-6 px-6">Type</th>
          <th class="pb-4 pt-6 px-6">Amount</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Description</th>
        </tr>
        <tr v-for="transaction in transactions.data" :key="transaction.id" class="hover:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/transactions/${transaction.id}/edit`">
              {{ transaction.transaction_date }}
              <icon v-if="transaction.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/transactions/${transaction.id}/edit`">
              {{ transaction.account }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/transactions/${transaction.id}/edit`">
              {{ transaction.bakery }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/transactions/${transaction.id}/edit`">
              <div :class="{
                'px-2 py-1 inline-flex text-xs font-semibold rounded-full': true,
                'bg-green-100 text-green-800': transaction.type === 'credit',
                'bg-red-100 text-red-800': transaction.type === 'debit'
              }">
                {{ transaction.type }}
              </div>
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/transactions/${transaction.id}/edit`">
              GMD {{ transaction.amount }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/transactions/${transaction.id}/edit`">
              {{ transaction.description }}
            </Link>
          </td>
          <td class="border-t w-px">
            <Link class="flex items-center px-4" :href="`/transactions/${transaction.id}/edit`">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="transactions.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="7">No transactions found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="transactions.links" />
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Icon from '@/Shared/Icon.vue'
import pickBy from 'lodash/pickBy'
import Layout from '@/Shared/Layout.vue'
import throttle from 'lodash/throttle'
import mapValues from 'lodash/mapValues'
import Pagination from '@/Shared/Pagination.vue'
import SearchFilter from '@/Shared/SearchFilter.vue'

export default {
  components: {
    Head,
    Icon,
    Link,
    Pagination,
    SearchFilter,
  },
  layout: Layout,
  props: {
    filters: Object,
    transactions: Object,
    bakeries: Array,
    accounts: Array,
  },
  data() {
    return {
      form: {
        search: this.filters.search,
        trashed: this.filters.trashed,
        bakery_id: this.filters.bakery_id,
        account_id: this.filters.account_id,
      },
    }
  },
  watch: {
    form: {
      deep: true,
      handler: throttle(function () {
        this.$inertia.get('/transactions', pickBy(this.form), { preserveState: true })
      }, 150),
    },
  },
  methods: {
    reset() {
      this.form = mapValues(this.form, () => null)
    },
  },
}
</script>