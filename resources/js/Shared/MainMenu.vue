<template>
  <div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
        </svg>
        <div :class="isUrl('') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Dashboard</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/bakeries">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('bakeries') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
          <path fill-rule="evenodd" d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z" clip-rule="evenodd" />
        </svg>
        <div :class="isUrl('bakeries') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Bakeries</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/accounts">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('accounts') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
        </svg>
        <div :class="isUrl('accounts') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Accounts</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/transactions">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('transactions') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path d="M8 5a1 1 0 100 2h5.586l-1.293 1.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L13.586 5H8zM12 15a1 1 0 100-2H6.414l1.293-1.293a1 1 0 10-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L6.414 15H12z" />
        </svg>
        <div :class="isUrl('transactions') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Transactions</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/expenses">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('expenses') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm4.707 3.707a1 1 0 00-1.414-1.414l-3 3a1 1 0 001.414 1.414l3-3zM11 9a1 1 0 100 2h4a1 1 0 100-2h-4zm-.707 3.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 011.414-1.414l3 3zM11 13a1 1 0 100 2h4a1 1 0 100-2h-4z" clip-rule="evenodd" />
        </svg>
        <div :class="isUrl('expenses') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Expenses</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/expense-categories">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('expense-categories') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
        </svg>
        <div :class="isUrl('expense-categories') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Expense Categories</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/users">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('users') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
        </svg>
        <div :class="isUrl('users') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Users</div>
      </Link>
    </div>
    <div class="mb-4">
      <Link class="group flex items-center py-3" href="/reports">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" :class="isUrl('reports') ? 'text-white' : 'text-brand-400 group-hover:text-white'" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm2 10a1 1 0 10-2 0v3a1 1 0 102 0v-3zm2-3a1 1 0 011 1v5a1 1 0 11-2 0v-5a1 1 0 011-1zm4-1a1 1 0 10-2 0v7a1 1 0 102 0V8z" clip-rule="evenodd" />
        </svg>
        <div :class="isUrl('reports') ? 'text-white' : 'text-brand-300 group-hover:text-white'">Reports</div>
      </Link>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
  components: {
    Link
  },
  methods: {
    isUrl(...urls) {
      let currentUrl = this.$page.url.substr(1)
      if (urls[0] === '') {
        return currentUrl === ''
      }
      return urls.filter((url) => currentUrl.startsWith(url)).length
    },
  },
}
</script>
