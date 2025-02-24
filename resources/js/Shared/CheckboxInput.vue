<template>
  <div>
    <label v-if="label" class="form-label" :for="id">{{ label }}:</label>
    <input
      :id="id"
      type="checkbox"
      v-model="value"
      :class="['form-checkbox mt-1', error ? 'error' : '']"
      v-bind="$attrs"
    >
    <div v-if="error" class="form-error">{{ error }}</div>
  </div>
</template>

<script>
import { getCurrentInstance } from 'vue'

export default {
  inheritAttrs: false,
  props: {
    id: {
      type: String,
      default() {
        const instance = getCurrentInstance()
        return `checkbox-input-${instance?.uid || Math.random().toString(36).substr(2, 9)}`
      },
    },
    error: String,
    label: String,
    modelValue: Boolean,
  },
  computed: {
    value: {
      get() {
        return this.modelValue
      },
      set(value) {
        this.$emit('update:modelValue', value)
      },
    },
  },
}
</script>

<style scoped>
.form-checkbox {
  @apply rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50;
}
.form-checkbox.error {
  @apply border-red-400 focus:border-red-400 focus:ring focus:ring-red-200;
}
</style>