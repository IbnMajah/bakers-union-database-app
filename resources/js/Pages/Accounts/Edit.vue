<template>
  <div>
    <Head :title="`${form.name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-brand-400 hover:text-brand-600" href="/accounts">Accounts</Link>
      <span class="text-brand-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="account.deleted_at" class="mb-6" @restore="restore">
      This account has been deleted.
    </trashed-message>
    <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
          <div class="pb-8 pr-6 w-full lg:w-1/2">
            <label class="form-label">Balance</label>
            <div class="form-input bg-gray-50">{{ account.balance }}</div>
          </div>
          <div class="pb-8 pr-6 w-full lg:w-1/2">
            <label class="form-label">Last Transaction</label>
            <div class="form-input bg-gray-50">{{ account.last_transaction }}</div>
          </div>
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button
            v-if="!account.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            Delete Account
          </button>
          <loading-button :loading="form.processing" class="btn-brand ml-auto" type="submit">Update Account</loading-button>
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
    account: Object,
    bakeries: Array,
  },
  data() {
    return {
      form: this.$inertia.form({
        name: this.account.name,
        bakery_id: this.account.bakery_id,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/accounts/${this.account.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this account?')) {
        this.$inertia.delete(`/accounts/${this.account.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this account?')) {
        this.$inertia.put(`/accounts/${this.account.id}/restore`)
      }
    },
  },
}
</script>

<style scoped>
.form-input.bg-gray-50 {
  @apply py-2 px-3 rounded border border-gray-300 text-gray-700;
}
</style>