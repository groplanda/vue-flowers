<template lang="pug">
  <CategoryComponent :category="category" @sortedProducts="sortedProducts" :showLoading="showLoading" @changePage="changePage" />
</template>
<script>
import axios from "axios";
import CategoryComponent from '@vue/components/Category/CategoryComponent';
import setTitle from '@vue/helpers/setTitle.js';

export default {
  name: "Category",
  components: {
    CategoryComponent
  },
  data() {
    return {
      category: {},
      slug: "",
      showLoading: true,
      dir: "desc",
      order: "price"
    }
  },
  watch: {
    $route (to){
      this.slug = to.params.slug;
      this.fetchCategory(this.slug, 0);
    }
  },
  methods: {
    fetchCategory(slug, page) {
      const params = {
        page: page,
        dir: this.dir,
        order: this.order,
      };
      axios.get("/api/category/" + slug, { params: params })
      .then(response => {
        if(response.data) {
          this.category = response.data;
          this.showLoading = false;
        } else {
          this.$router.push({ name: 'notfound' })
        }
      })
      .catch(e => {
        console.log(e);
      })
    },
    changePage(offset) {
      this.fetchCategory(this.slug, offset);
    },
    sortedProducts(key, page) {
      switch (key) {
        case "prive_low":
          this.dir = "desc";
          this.order = "price";
          this.fetchCategory(this.slug, page)
          break;
        case "prive_up":
          this.dir = "asc";
          this.order = "price";
          this.fetchCategory(this.slug, page)
          break;
        case "fist_new":
          this.dir = "desc";
          this.order = "sort_order";
          this.fetchCategory(this.slug, page)
          break;
        case "fist_old":
          this.dir = "asc";
          this.order = "sort_order";
          this.fetchCategory(this.slug, page)
          break;
        default:
          break;
      }
    }
  },
  created() {
    this.slug = this.$route.params.slug;
    this.fetchCategory(this.slug, 0);
  },
  updated() {
    setTitle(this.category);
  }
}
</script>