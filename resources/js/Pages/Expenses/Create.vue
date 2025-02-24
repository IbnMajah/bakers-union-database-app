<template>
  <div>
    <Head title="Create Expense" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-brand-400 hover:text-brand-600" href="/expenses">Expenses</Link>
      <span class="text-brand-400 font-medium">/</span>
      Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="p-8 -mb-8 -mr-6 flex flex-wrap">
          <select-input
            v-model="form.bakery_id"
            :error="form.errors.bakery_id"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Bakery"
          >
            <option :value="null" />
            <option v-for="bakery in bakeries" :key="bakery.id" :value="bakery.id">
              {{ bakery.name }}
            </option>
          </select-input>
          <select-input
            v-model="form.expense_category_id"
            :error="form.errors.expense_category_id"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Category"
          >
            <option :value="null" />
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select-input>
          <text-input
            v-model="form.amount"
            :error="form.errors.amount"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="number"
            step="0.01"
            label="Amount"
          />
          <text-input
            v-model="form.receipt_number"
            :error="form.errors.receipt_number"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Receipt Number"
          />
          <text-input
            v-model="form.expense_date"
            :error="form.errors.expense_date"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="date"
            label="Expense Date"
          />
          <text-input
            v-model="form.description"
            :error="form.errors.description"
            class="pb-8 pr-6 w-full"
            type="text"
            label="Description"
          />
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
          <loading-button :loading="form.processing" class="btn-brand" type="submit">
            Create Expense
          </loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
  },
  layout: Layout,
  props: {
    bakeries: Array,
    categories: Array,
  },
  data() {
    return {
      form: this.$inertia.form({
        bakery_id: null,
        expense_category_id: null,
        amount: '',
        description: '',
        receipt_number: '',
        expense_date: '',
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/expenses')
    },
  },
}
</script>