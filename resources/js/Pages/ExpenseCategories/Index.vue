<template>
  <div>
    <Head title="Expense Categories" />
    <h1 class="mb-8 text-3xl font-bold">Expense Categories</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <Link class="btn-brand" href="/expense-categories/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Expense Category</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Description</th>
        </tr>
        <tr v-for="category in expenseCategories.data" :key="category.id" class="hover:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/expense-categories/${category.id}/edit`">
              {{ category.name }}
              <icon v-if="category.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/expense-categories/${category.id}/edit`">
              {{ category.description }}
            </Link>
          </td>
          <td class="border-t w-px">
            <Link class="flex items-center px-4" :href="`/expense-categories/${category.id}/edit`">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="expenseCategories.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="4">No expense categories found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="expenseCategories.links" />
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
    expenseCategories: Object,
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
        this.$inertia.get('/expense-categories', pickBy(this.form), { preserveState: true })
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