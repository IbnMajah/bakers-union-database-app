<template>
  <div>
    <Head title="Create User" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-brand-400 hover:text-brand-600" href="/users">Users</Link>
      <span class="text-brand-400 font-medium">/</span>
      Create
    </h1>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="p-8 -mb-8 -mr-6 flex flex-wrap">
          <text-input
            v-model="form.first_name"
            :error="form.errors.first_name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="First Name"
          />
          <text-input
            v-model="form.last_name"
            :error="form.errors.last_name"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Last Name"
          />
          <text-input
            v-model="form.email"
            :error="form.errors.email"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="email"
            label="Email"
          />
          <text-input
            v-model="form.password"
            :error="form.errors.password"
            class="pb-8 pr-6 w-full lg:w-1/2"
            type="password"
            label="Password"
          />
          <select-input
            v-model="form.role"
            :error="form.errors.role"
            class="pb-8 pr-6 w-full lg:w-1/2"
            label="Role"
          >
            <option :value="null" />
            <option value="user">User</option>
            <option value="admin">Admin</option>
          </select-input>
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex justify-end items-center">
          <loading-button :loading="form.processing" class="btn-brand" type="submit">
            Create User
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
  data() {
    return {
      form: this.$inertia.form({
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        role: null,
      }),
    }
  },
  methods: {
    store() {
      this.form.post('/users')
    },
  },
}
</script>
