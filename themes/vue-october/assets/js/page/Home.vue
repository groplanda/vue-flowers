<template lang="pug">
  .page
    Slider
    Categories
    ProductSlider(title="Самое популярное" :products="popularProducts")
    ProductSlider(title="Новинка" :products="newProducts")
    Post
    Special
    Advantages
</template>
<script>
import Slider from '@vue/components/Slider/Slider';
import Categories from '@vue/components/Categories/Categories';
import ProductSlider from '@vue/components/ProductSlider/ProductSlider';
import Post from '@vue/components/Post/Post';
import Special from '@vue/components/Special/Special';
import Advantages from '@vue/components/Advantages/Advantages.vue';

export default {
  name: "Home",
  components: {
    Slider,
    Categories,
    ProductSlider,
    Post,
    Special,
    Advantages
  },
  computed: {
    popularProducts() {
      return this.$store.getters.getNewProducts;
    },
    newProducts() {
      return this.$store.getters.getPopularProducts;
    }
  },
  created() {
    this.$store.dispatch('fetchPopularProducts');
    this.$store.dispatch('fetchNewProducts');
  }
}
</script>