<template>
  <div>
    <Head title="Accounts" />
    <h1 class="mb-8 text-3xl font-bold">Accounts</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <Link class="btn-brand" href="/accounts/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Account</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6">Bakery</th>
          <th class="pb-4 pt-6 px-6">Balance</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Last Transaction</th>
        </tr>
        <tr v-for="account in sortedAccounts" :key="account.id" class="hover:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/accounts/${account.id}/edit`">
              {{ account.name }}
              <icon v-if="account.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/accounts/${account.id}/edit`">
              {{ account.is_general ? 'General Account' : account.bakery }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/accounts/${account.id}/edit`">
              {{ account.balance }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/accounts/${account.id}/edit`">
              {{ account.last_transaction }}
            </Link>
          </td>
          <td class="border-t w-px">
            <Link class="flex items-center px-4" :href="`/accounts/${account.id}/edit`">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="accounts.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="5">No accounts found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="accounts.links" />
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
    accounts: Object,
  },
  computed: {
    sortedAccounts() {
      return [...this.accounts.data].sort((a, b) => {
        if (a.is_general && !b.is_general) return -1;
        if (!a.is_general && b.is_general) return 1;
        return a.name.localeCompare(b.name);
      });
    }
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
        this.$inertia.get('/accounts', pickBy(this.form), { preserveState: true })
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