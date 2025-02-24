<template>
  <div>
    <Head :title="`${form.name}`" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-brand-400 hover:text-brand-600" href="/expense-categories">Expense Categories</Link>
      <span class="text-brand-400 font-medium">/</span>
      {{ form.name }}
    </h1>
    <trashed-message v-if="expenseCategory.deleted_at" class="mb-6" @restore="restore">
      This expense category has been deleted.
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
          <text-input
            v-model="form.description"
            :error="form.errors.description"
            class="pb-8 pr-6 w-full"
            label="Description"
          />
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button
            v-if="!expenseCategory.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            Delete Expense Category
          </button>
          <loading-button :loading="form.processing" class="btn-brand ml-auto" type="submit">
            Update Expense Category
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
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    TextInput,
    TrashedMessage,
  },
  layout: Layout,
  props: {
    expenseCategory: Object,
  },
  data() {
    return {
      form: this.$inertia.form({
        name: this.expenseCategory.name,
        description: this.expenseCategory.description,
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/expense-categories/${this.expenseCategory.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this expense category?')) {
        this.$inertia.delete(`/expense-categories/${this.expenseCategory.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this expense category?')) {
        this.$inertia.put(`/expense-categories/${this.expenseCategory.id}/restore`)
      }
    },
  },
}
</script>