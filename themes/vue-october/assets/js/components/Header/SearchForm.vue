<template lang="pug">
  .search-form
    form(@submit.prevent="onSearch")._form
      input(type="text" autocomplete="off" v-model="search" placeholder="Поиск по каталогу...")._input
      button._btn(type="submit")
        icon(name="search" component="header")._btn-ico
</template>
<script>
export default {
  name: "SearchForm",
  data() {
    return {
      search: ""
    }
  },
  methods: {
    onSearch() {
      if (this.search.length > 2) {
        this.$router.push({ name: 'search', query: { val: this.search } })
        this.search = "";
      }
    }
  }
}
</script>