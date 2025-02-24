<template>
  <div>
    <Head :title="`${form.first_name} ${form.last_name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-brand-400 hover:text-brand-600" href="/users">Users</Link>
      <span class="text-brand-400 font-medium">/</span>
      {{ form.first_name }} {{ form.last_name }}
    </h1>
    <trashed-message v-if="user.deleted_at" class="mb-6" @restore="restore">
      This user has been deleted.
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button
            v-if="!user.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            Delete User
          </button>
          <loading-button :loading="form.processing" class="btn-brand ml-auto" type="submit">
            Update User
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
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    user: Object,
  },
  remember: 'form',
  data() {
    return {
      form: this.$inertia.form({
        first_name: this.user.first_name,
        last_name: this.user.last_name,
        email: this.user.email,
        password: '',
        role: this.user.role,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/users/${this.user.id}`, {
        onSuccess: () => this.form.reset('password'),
      })
    },
    destroy() {
      if (confirm('Are you sure you want to delete this user?')) {
        this.$inertia.delete(`/users/${this.user.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this user?')) {
        this.$inertia.put(`/users/${this.user.id}/restore`)
      }
    },
  },
}
</script>
