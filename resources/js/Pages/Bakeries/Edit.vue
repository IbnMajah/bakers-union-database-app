<template>
  <div>
    <Head :title="`${form.name}`" />
    <div class="max-w-3xl flex items-center justify-between mb-8">
      <h1 class="text-3xl font-bold flex items-center">
        <Link class="text-brand-400 hover:text-brand-600" href="/bakeries">Bakeries</Link>
        <span class="text-brand-400 font-medium">/</span>
        {{ form.name }}
      </h1>
    </div>

    <div class="mb-6">
      <nav class="flex bg-gray-100 rounded-lg p-1">
        <button
          @click="activeTab = 'details'"
          :class="[
            'w-full text-sm font-medium rounded-md py-2 px-4 transition-colors duration-150',
            activeTab === 'details'
              ? 'bg-white text-gray-900 shadow'
              : 'text-gray-500 hover:text-gray-900'
          ]"
        >
          Details
        </button>
        <button
          @click="activeTab = 'payments'"
          :class="[
            'w-full text-sm font-medium rounded-md py-2 px-4 transition-colors duration-150',
            activeTab === 'payments'
              ? 'bg-white text-gray-900 shadow'
              : 'text-gray-500 hover:text-gray-900'
          ]"
        >
          Payments
        </button>
      </nav>
    </div>

    <trashed-message v-if="bakery.deleted_at" class="mb-6" @restore="restore">
      This bakery has been deleted.
    </trashed-message>

    <!-- Details Tab -->
    <div v-if="activeTab === 'details'" class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="update">
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
          <div class="pb-8 pr-6 w-full">
            <label class="form-label">Last Payment</label>
            <div class="form-input bg-gray-50">{{ bakery.last_payment ? `${bakery.last_payment}` : '-' }}</div>
          </div>
        </div>
        <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 flex items-center">
          <button
            v-if="!bakery.deleted_at"
            class="text-red-600 hover:underline"
            tabindex="-1"
            type="button"
            @click="destroy"
          >
            Delete Bakery
          </button>
          <loading-button :loading="form.processing" class="btn-brand ml-auto" type="submit">
            Update Bakery
          </loading-button>
        </div>
      </form>
    </div>

    <!-- Payments Tab -->
    <div v-else-if="activeTab === 'payments'" class="max-w-3xl">
      <div class="bg-white rounded-md shadow overflow-hidden mb-6">
        <div class="p-8">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-medium">Recent Transactions</h2>
            <button
              v-if="!bakery.deleted_at"
              class="btn-brand inline-flex items-center"
              type="button"
              @click="showPaymentModal = true"
            >
              <svg
                class="w-5 h-5 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"
                />
              </svg>
              Record Payment
            </button>
          </div>
          <div v-if="transactions.length > 0" class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
              <tr class="text-left font-bold">
                <th class="pb-4 pt-6 px-6">Date</th>
                <th class="pb-4 pt-6 px-6">Amount</th>
                <th class="pb-4 pt-6 px-6">Type</th>
                <th class="pb-4 pt-6 px-6">Description</th>
              </tr>
              <tr v-for="transaction in transactions" :key="transaction.id" class="hover:bg-gray-100">
                <td class="border-t p-6">{{ transaction.transaction_date }}</td>
                <td class="border-t p-6">GMD {{ transaction.amount }}</td>
                <td class="border-t p-6">{{ transaction.type }}</td>
                <td class="border-t p-6">{{ transaction.description }}</td>
              </tr>
            </table>
          </div>
          <div v-else class="p-6 text-gray-500 text-center">
            No transactions found.
          </div>
        </div>
      </div>
    </div>

    <!-- Payment Modal -->
    <Modal :show="showPaymentModal" @close="closePaymentModal">
      <div class="p-6">
        <div class="text-lg font-medium text-gray-900 mb-4">Record Payment</div>
        <div class="mb-4">
          <text-input
            v-model="paymentForm.amount"
            :error="paymentForm.errors.amount"
            type="number"
            step="0.01"
            label="Amount"
            class="w-full"
          />
        </div>
        <div class="flex items-center justify-end">
          <button type="button" class="btn-secondary mr-2" @click="closePaymentModal">Cancel</button>
          <loading-button :loading="paymentForm.processing" class="btn-brand" @click="recordPayment">
            Record Payment
          </loading-button>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script>
import { Head, Link } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import TextInput from '@/Shared/TextInput.vue'
import SelectInput from '@/Shared/SelectInput.vue'
import TextareaInput from '@/Shared/TextareaInput.vue'
import LoadingButton from '@/Shared/LoadingButton.vue'
import TrashedMessage from '@/Shared/TrashedMessage.vue'
import Modal from '@/Shared/Modal.vue'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    TextareaInput,
    TrashedMessage,
    Modal,
  },
  layout: Layout,
  props: {
    bakery: {
      type: Object,
      required: true
    },
    categories: {
      type: Array,
      required: true
    },
    bakeryAccount: {
      type: Object,
      required: true
    },
    generalAccount: {
      type: Object,
      required: true
    },
    transactions: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  data() {
    return {
      activeTab: 'details',
      form: this.$inertia.form({
        name: this.bakery.name,
        category_id: this.bakery.category_id,
        contact_person: this.bakery.contact_person,
        phone: this.bakery.phone,
        email: this.bakery.email,
        address: this.bakery.address,
        status: this.bakery.status,
      }),
      showPaymentModal: false,
      paymentForm: this.$inertia.form({
        amount: '',
      }),
    }
  },
  methods: {
    update() {
      this.form.put(`/bakeries/${this.bakery.id}`)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this bakery?')) {
        this.$inertia.delete(`/bakeries/${this.bakery.id}`)
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this bakery?')) {
        this.$inertia.put(`/bakeries/${this.bakery.id}/restore`)
      }
    },
    closePaymentModal() {
      this.showPaymentModal = false
      this.paymentForm.reset()
    },
    recordPayment() {
      this.paymentForm.post(`/bakeries/${this.bakery.id}/payment`, {
        onSuccess: () => {
          this.closePaymentModal()
        },
      })
    },
  },
}
</script>

<style scoped>
.form-input.bg-gray-50 {
  @apply py-2 px-3 rounded border border-gray-300 text-gray-700;
}
</style>