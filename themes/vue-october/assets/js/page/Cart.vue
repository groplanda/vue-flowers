<template lang="pug">
  section.cart.section
    Preloader(v-if="loading")
    ._container.container
      ._heading
        ._title Корзина
      ._row(v-if="products.length > 0")
        CartTable(:products="products")
        CartTotal(:products="products")
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
<style lang="scss">
@import '@/scss/vars.scss';
.cart {
  padding: 50px 0 100px;
  background: #fbf8ec;

  @media(max-width: 767px) {
    padding: 30px 0 50px;
  }
  $root: &;

  &__heading {
    margin-bottom: 60px;
    @media(max-width: 1199px) {
      margin-bottom: 40px;
    }
    @media(max-width: 767px) {
      margin-bottom: 30px;
    }
  }

  &__title {
    font-size: 60px;
    color: $primary;
    font-weight: bold;
    @media(max-width: 1199px) {
      font-size: 50px;
    }
    @media(max-width: 767px) {
      font-size: 40px;
    }
    @media(max-width: 575px) {
      font-size: 30px;
    }
  }

  &__row {
    display: flex;
    align-items: flex-start;
    flex-wrap: wrap;
  }

  &__empty {
    max-width: 480px;
    width: 100%;
    border-radius: 20px;
    padding: 30px;
    background: $primary;
    box-shadow: 0 10px 29px 0 $shadow;
    margin: 0 auto;

    @media(max-width: 767px) {
      padding: 20px;
    }
  }

  &__empty-inner {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    width: 100%;
    @media(max-width: 575px) {
      flex-direction: column;
    }
  }

  &__empty-text {
    font-weight: 500;
    font-size: 20px;
    color: #fff;
    margin-right: 15px;
    @media(max-width: 575px) {
      font-size: 16px;
      margin: 0 0 10px 0;
    }
  }

  &__empty-link {
    color: #cbcbd0;
    font-size: 16px;
    @media(max-width: 575px) {
      font-size: 14px;
    }
  }
}
</style>