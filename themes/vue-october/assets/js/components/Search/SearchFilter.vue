<template lang="pug">
  .filter
    input(type="text" v-model="searchInput" @input="onSearch" placeholder="Поиск по каталогу...")._input
    button(type="button" @click="onSearch")._btn
      icon(name="search" component="header")._btn-ico
</template>
<script>
export default {
  name: "SearchFilter",
  data() {
    return {
      searchInput: "",
    }
  },
  methods: {
    onSearch() {
      if (this.searchInput.length > 2) {
        this.$emit("onSearch", this.searchInput);
      }
    }
  }
}
</script>
