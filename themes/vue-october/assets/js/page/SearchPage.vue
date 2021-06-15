<template lang="pug">
  <SearchComponent :products="products" :search="search" :showLoading="showLoading" @inputSearch="inputSearch" />
</template>
<script>
import axios from "axios";
import SearchComponent from '@vue/components/Search/SearchComponent';

export default {
  name: "SearchPage",
  components: {
    SearchComponent
  },
  data() {
    return {
      products: [],
      search: "",
      showLoading: true
    }
  },
  watch: {
    $route (to){
      if(to.query && (to.query.val && to.query.val.length)) {
        this.search =  to.query.val;
        this.searchProducts(this.search);
      }
    }
  },
  methods: {
    searchProducts(query) {
      axios.get("/api/search/" + query)
      .then(response => {
        this.products = response.data;
        this.showLoading = false
      })
      .catch(e => {
        console.log(e);
      })
    },
    inputSearch(val) {
      if (val !== this.search) {
        this.showLoading = true;
        this.searchProducts(val);
        this.$router.push({ name: 'search', query: { val: val } })
        this.search = val;
      }
    }
  },
  created() {
    if(this.$route.query && (this.$route.query.val && this.$route.query.val.length)) {
      this.search =  this.$route.query.val;
      this.searchProducts(this.search);
    } else {
      this.showLoading = false;
    }
  }
}
</script>