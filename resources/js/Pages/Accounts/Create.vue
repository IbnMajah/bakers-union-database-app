<template>
  <div>
    <Head title="Create Account" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-brand-400 hover:text-brand-600" href="/accounts">Accounts</Link>
      <span class="text-brand-400 font-medium">/</span>
      Create
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
            v-model="form.bakery_id"
            :error="form.errors.bakery_id"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Bakery"
          >
            <option :value="null" />
            <option v-for="bakery in bakeries" :key="bakery.id" :value="bakery.id">{{ bakery.name }}</option>
          </select-input>
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
          <loading-button :loading="form.processing" class="btn-brand" type="submit">Create Account</loading-button>
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
  },
  data() {
    return {
      form: this.$inertia.form({
        name: null,
        bakery_id: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/accounts')
    },
  },
}
</script>