<template>
  <div class="">
    <Head title="Dashboard" />
    <div class="flex justify-between items-center">
      <div v-for="(stat, index) in stats" :key="index" class="w-full md:w-1/3 m-2">
        <Card
          :icon="getIcon(stat.title)"
          :title="stat.title"
          :numberValue="stat.numValue"
          :change="stat.change"
        />
      </div>
    </div>
  </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Shared/Layout.vue'
import Card from '@/Shared/Card.vue'
import { BanknotesIcon, CreditCardIcon, BuildingStorefrontIcon } from "@heroicons/vue/24/outline";

export default {
  components: {
    Head,
    Card,
  },
  layout: Layout,
  props: {
    stats: {
      type: Array,
      required: true
    }
  },
  methods: {
    getIcon(title) {
      const iconMap = {
        'Total Expenses': BanknotesIcon,
        'Total Payments': CreditCardIcon,
        'Active Bakeries': BuildingStorefrontIcon
      };
      return iconMap[title];
    }
  }
}
</script>
