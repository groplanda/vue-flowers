<template lang="pug">
  section.cart.section
    Preloader(v-if="loading")
    ._container.container
      ._heading
        ._title Корзина
      ._row(v-if="products.length > 0")
        CartTable(:products="products")
        CartTotal(:price="{ sum, sale, total }")
      ._empty(v-else)
        ._empty-inner
          ._empty-text Корзина пуста!
          router-link(:to="{ name: 'home' }")._empty-link Вернуться на главную

</template>
<script>
import CartTable from "@vue/components/Cart/CartTable";
import Preloader from '@vue/components/Preloader/Preloader';
import CartTotal from '@vue/components/Cart/CartTotal';

export default {
  name: "Cart",
  components: {
    CartTable,
    Preloader,
    CartTotal
  },
  computed: {
    products() {
      return this.$store.getters.getCartProducts;
    },
    loading() {
      return this.$store.getters.getLoading;
    },
    sum() {
      return this.calcSum();
    },
    sale() {
      return this.calcSaleSum();
    },
    total() {
      return Number(this.sum) - Number(this.sale);
    }
  },
  methods: {
    calcSum() {
      let result = 0;
      this.products.forEach(product => {
        result += Number(product.amount) * Number(product.price);
      })
      return result;
    },
    calcSaleSum() {
      let result = 0;
      this.products.forEach(product => {
        if (+product.sale_price !== 0) {
          result += (Number(product.price) - Number(product.sale_price)) * Number(product.amount);
        }
      })
      return result;
    }
  },
  created() {
    const cart = this.$store.getters.getCart;
    if (cart.length > 0) {
      this.$store.dispatch("fetchProductsByIds", cart);
    }
  }
}
</script>
