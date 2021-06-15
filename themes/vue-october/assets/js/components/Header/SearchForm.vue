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
<style lang="scss">
@import '@/scss/vars.scss';
.search-form {
  width: 100%;
  max-width: 290px;

  @media(max-width: 991px) {
    max-width: 220px;
  }

  @media(max-width: 767px) {
    max-width: 100%;
  }

  &__form {
    width: 100%;
    display: flex;
    position: relative;
  }

  &__input {
    border-radius: 30px;
    padding: 0 30px;
    outline: 0;
    color: $primary;
    width: 100%;
    font-size: 15px;
    height: 44px;
    display: block;
    background: #FFF;

    @media(max-width: 991px) {
      font-size: 14px;
      padding: 0 20px;
    }
  }

  &__btn {
    z-index: 1;
    position: absolute;
    width: 20px;
    height: 20px;
    right: 25px;
    top: calc(50% - 10px);
    cursor: pointer;
  }

  &__btn-ico {
    fill: $primary;
    width: 100%;
    height: 100%;
  }
}
</style>