<template lang="pug">
  .cart-table
    ._inner
      ._heading
        ._heading-left
          ._heading-title Товар
        ._heading-right
          ._heading-title Цена
          ._heading-title Кол-во
          ._heading-title Сумма
      ._body
        CartItem(
          v-for="product in cartProduct"
          :key="product.id"
          :product="product"
          @deleteProduct="deleteProduct"
          @updateProduct="updateProduct")

</template>
<script>
import CartItem from "./CartItem"

export default {
  name: "CartTable",
  components: {
    CartItem
  },
  props: {
    products: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      cartProduct: this.products
    }
  },
  watch: {
    products() {
      this.cartProduct = this.products;
    }
  },
  methods: {
    deleteProduct(id) {
      const productIndex = this.cartProduct.findIndex(el => el.id === id);
      if (productIndex !== -1) {
        this.cartProduct.splice(productIndex, 1);
      }
    },
    updateProduct(data) {
      const productIndex = this.cartProduct.findIndex(el => el.id === data.id);
      this.cartProduct[productIndex].amount =  data.amount;
    }
  }
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.cart-table {
  width: 100%;
  max-width: calc(100% - 380px);

  @media(max-width: 1440px) {
    max-width: 100%;
  }
  @media(max-width: 767px) {
    max-width: none;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
  }
  $root: &;

  &__inner {
    @media(max-width: 767px) {
      width: 747px;
    }
  }

  &__heading {
    padding: 0px 90px 30px 30px;
    display: flex;
    align-items: center;

    @media(max-width: 1740px) {
      padding: 0px 75px 20px 20px;
    }

    @media(max-width: 1440px) {
      padding: 0px 60px 15px 15px;
    }

    @media(max-width: 991px) {
      padding: 0px 40px 10px 10px;
    }
  }

  &__heading-left {
    width: 100%;
    max-width: 300px;
    @media(max-width: 1740px) {
      max-width: 250px;
    }
    @media(max-width: 1440px) {
      max-width: 200px;
    }
    @media(max-width: 991px) {
      max-width: 180px;
    }
  }

  &__heading-right {
    width: 100%;
    max-width: calc(100% - 300px);
    display: flex;
    align-items: center;

    @media(max-width: 1740px) {
      max-width: calc(100% - 250px);
    }
    @media(max-width: 1440px) {
     max-width: calc(100% - 200px);
    }
    @media(max-width: 991px) {
      max-width: calc(100% - 180px);
    }

    #{$root} {
      &__heading-title {
        width: 100%;
        max-width: calc(33.333%);
      }
    }
  }

  &__heading-title {
    font-weight: 500;
    font-size: 17px;
    color: $primary;

    @media(max-width: 1440px) {
      font-size: 16px;
    }
    @media(max-width: 991px) {
      font-size: 14px;
    }
  }
}
</style>