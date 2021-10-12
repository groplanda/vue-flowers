<template lang="pug">
  section.post.section
    ._container.container
      ._heading
        ._title Спасибо за оформление заказа!
        p._content Мы с вами свяжемся в ближайшее время.
    ProductSlider(title="Популярные товары" :products="popularProducts")

</template>
<script>
import ProductSlider from '@vue/components/ProductSlider/ProductSlider';

export default {
  name: "CheckoutResult",
  components: {
    ProductSlider
  },
  computed: {
    popularProducts() {
      return this.$store.getters.getPopularProducts;
    },
  },
  created() {
    this.$store.dispatch('fetchPopularProducts');
  }
}
</script>