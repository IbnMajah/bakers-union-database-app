<template>
  <div>
    <Head title="Bakeries" />
    <h1 class="mb-8 text-3xl font-bold">Bakeries</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        <label class="block text-gray-700">Trashed:</label>
        <select v-model="form.trashed" class="form-select mt-1 w-full">
          <option :value="null" />
          <option value="with">With Trashed</option>
          <option value="only">Only Trashed</option>
        </select>
      </search-filter>
      <Link class="btn-brand" href="/bakeries/create">
        <span>Create</span>
        <span class="hidden md:inline">&nbsp;Bakery</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <tr class="text-left font-bold">
          <th class="pb-4 pt-6 px-6">Name</th>
          <th class="pb-4 pt-6 px-6">Category</th>
          <th class="pb-4 pt-6 px-6">Contact Person</th>
          <th class="pb-4 pt-6 px-6">Contact</th>
          <th class="pb-4 pt-6 px-6">Status</th>
          <th class="pb-4 pt-6 px-6" colspan="2">Last Payment</th>
        </tr>
        <tr v-for="bakery in bakeries.data" :key="bakery.id" class="hover:bg-gray-100">
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/bakeries/${bakery.id}/edit`">
              {{ bakery.name }}
              <icon v-if="bakery.deleted_at" name="trash" class="flex-shrink-0 ml-2 w-3 h-3 fill-gray-400" />
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/bakeries/${bakery.id}/edit`">
              {{ bakery.category }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/bakeries/${bakery.id}/edit`">
              {{ bakery.contact_person }}
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/bakeries/${bakery.id}/edit`">
              <div class="flex flex-col">
                <span>{{ bakery.phone }}</span>
                <span class="text-sm text-gray-600">{{ bakery.email }}</span>
              </div>
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/bakeries/${bakery.id}/edit`">
              <div :class="{
                'px-2 py-1 inline-flex text-xs font-semibold rounded-full': true,
                'bg-green-100 text-green-800': bakery.status === 'active',
                'bg-gray-100 text-gray-800': bakery.status === 'inactive'
              }">
                {{ bakery.status }}
              </div>
            </Link>
          </td>
          <td class="border-t">
            <Link class="flex items-center px-6 py-4" :href="`/bakeries/${bakery.id}/edit`">
              {{ bakery.last_payment }}
            </Link>
          </td>
          <td class="border-t w-px">
            <Link class="flex items-center px-4" :href="`/bakeries/${bakery.id}/edit`">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </Link>
          </td>
        </tr>
        <tr v-if="bakeries.data.length === 0">
          <td class="px-6 py-4 border-t" colspan="7">No bakeries found.</td>
        </tr>
      </table>
    </div>
    <pagination class="mt-6" :links="bakeries.links" />
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
    bakeries: Object,
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
        this.$inertia.get('/bakeries', pickBy(this.form), { preserveState: true })
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