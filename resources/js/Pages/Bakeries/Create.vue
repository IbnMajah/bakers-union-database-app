<template>
  <div>
    <Head title="Create Bakery" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-brand-400 hover:text-brand-600" href="/bakeries">Bakeries</Link>
      <span class="text-brand-400 font-medium">/</span> Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="p-8 -mb-8 -mr-6 flex flex-wrap">
          <text-input
            v-model="form.name"
            :error="form.errors.name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Name"
          />
          <select-input
            v-model="form.category_id"
            :error="form.errors.category_id"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Category"
          >
            <option :value="null" />
            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
          </select-input>
          <text-input
            v-model="form.contact_person"
            :error="form.errors.contact_person"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Contact Person"
          />
          <text-input
            v-model="form.phone"
            :error="form.errors.phone"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Phone"
          />
          <text-input
            v-model="form.email"
            :error="form.errors.email"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="email"
            label="Email"
          />
          <select-input
            v-model="form.status"
            :error="form.errors.status"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Status"
          >
            <option :value="null" />
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select-input>
          <textarea-input
            v-model="form.address"
            :error="form.errors.address"
            class="pb-8 pr-6 w-full"
            label="Address"
          />
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
          <loading-button :loading="form.processing" class="btn-brand" type="submit">Create Bakery</loading-button>
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
import TextareaInput from '@/Shared/TextareaInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TextareaInput,
  },
  layout: Layout,
  props: {
    categories: Array,
  },
  data() {
    return {
      form: this.$inertia.form({
        name: null,
        category_id: null,
        contact_person: null,
        phone: null,
        email: null,
        address: null,
        status: 'active',
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/bakeries')
    },
  },
}
</script>