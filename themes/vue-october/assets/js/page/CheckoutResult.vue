<template lang="pug">
  section.post.section
    ._container.container
      ._heading
        ._title Спасибо за оформление заказа!
        p._content Мы с вами свяжемся в ближайшее время.
    ProductSlider(title="Популярные товары" :products="popularProducts")

</template>
<script>
import axios from "axios";
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
  methods: {
    fetchCheckoutData() {
      axios.get("/api/checkout/yandex-kassa")
        .catch(e => {
          console.log(e);
        })
    }
  },
  created() {
    this.fetchCheckoutData();
    this.$store.dispatch('fetchPopularProducts');
  }
}
</script>