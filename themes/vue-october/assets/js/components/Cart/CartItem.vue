<template lang="pug">
.cart-item
  ._product
    ._product-img
      router-link(:to="{ name: 'product', params: { id: product.id }}")._product-link
      img(:src="product.image ? '/storage/app/media' + product.image : noImage", :alt="product.title")._product-thumb
    ._product-group
      router-link(:to="{ name: 'product', params: { id: product.id }}")._product-title {{ product.title }}
  ._right
    ._col
      ._price
        ._price-val {{ productPrice }}
        ._price-currency руб.

    ._col
      ProductAmount(@changeAmount="updateAmount" :amount="count")
    ._col
      ._price
        ._price-val {{ totalPrice }}
        ._price-currency руб.
  button(type="button" @click="deleteProduct")._remove
    icon(name="trash" component="cart")._remove-ico

</template>
<script>
import ProductAmount from '@vue/components/ProductSlider/ProductAmount.vue'

export default {
  name: "CartItem",
  components: {
    ProductAmount
  },
  props: {
    product: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      count: this.product.amount,
      noImage: "/themes/vue-october/assets/images/no-image.jpg"
    }
  },
  computed: {
    productPrice() {
      if (+this.product.sale_price !== 0) {
        return (this.product.sale_price).toLocaleString('ru')
      }
      return (this.product.price).toLocaleString('ru')
    },
    totalPrice() {
      let result = 0
      if (+this.product.sale_price !== 0) {
        result = +this.product.sale_price * +this.count;
      } else {
        result = +this.product.price * +this.count;
      }
      return (result).toLocaleString('ru');
    }
  },
  methods: {
    updateAmount(val) {
      this.count = val;
      this.updateCart();
      this.$emit("updateProduct", { id: this.product.id, amount: this.count });
    },
    updateCart() {
      if( this.count > 0 && this.product?.id) {
        this.$store.dispatch("updateCart", { id: this.product.id, amount: this.count })
      }
    },
    deleteProduct() {
      this.$store.dispatch("deleteProduct", this.product.id);
      this.$emit("deleteProduct", this.product.id);
    }
  }
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.cart-item {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  padding: 30px 90px 30px 30px;
  background: #FFF;
  border-radius: 20px;
  margin-bottom: 40px;
  position: relative;
  box-shadow: 0 7px 15px 0 $shadow;

  $root: &;

  @media(max-width: 1740px) {
    padding: 20px 75px 20px 20px;
  }
  @media(max-width: 1440px) {
    padding: 15px 60px 15px 15px;
  }
  @media(max-width: 991px) {
    padding: 10px 40px 10px 10px;
  }

  &__product {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    width: 100%;
    max-width: 300px;
    padding-right: 20px;

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

  &__product-img {
    width: 60px;
    margin-right: 20px;
    position: relative;
    overflow: hidden;
    border-radius: 7px;

    @media(max-width: 1440px) {
      width: 50px;
      margin-right: 10px;
    }

    &::after {
      content: "";
      display: block;
      padding-top: 100%;
    }
  }

  &__product-thumb {
    top: 0;
    left: 0;
    position: absolute;
    max-width: none;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: 50% 50%;
  }

  &__product-link {
    top: 0;
    left: 0;
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: 1;
  }

  &__product-group {
    width: 100%;
    max-width: calc(100% - 80px);
    @media(max-width: 1440px) {
      max-width: calc(100% - 60px);
    }
  }

  &__product-title {
    font-weight: 500;
    font-size: 15px;
    color: $primary;
    margin-bottom: 10px;
    @media(max-width: 991px) {
      font-size: 14px;
    }
  }

  &__right {
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
  }

  &__col {
    width: 100%;
    max-width: calc(33.333%);
  }

  &__price {
    display: flex;
    align-items: flex-end;
  }

  &__price-val {
    font-size: 20px;
    font-weight: 700;
    color: $primary;
    margin-right: 5px;
    @media(max-width: 1740px) {
      font-size: 18px;
    }
    @media(max-width: 991px) {
      font-size: 16px;
    }
  }

  &__price-currency {
    font-size: 18px;
    font-weight: 400;
    @media(max-width: 1740px) {
      font-size: 16px;
    }
    @media(max-width: 991px) {
      font-size: 14px;
    }
  }

  &__remove {
    width: 60px;
    height: 60px;
    position: absolute;
    top: calc(50% - 30px);
    right: 30px;
    border-radius: 10px;
    background: #F8F8F8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
    cursor: pointer;

    &:hover, &:focus {
      #{$root} {
        &__remove-ico {
          fill: $primary;
        }
      }
    }

    @media(max-width: 991px) {
      width: 40px;
      height: 40px;
      top: calc(50% - 20px);
    }
  }
  &__remove-ico {
    width: 24px;
    height: 24px;
    fill: $red;
    @media(max-width: 991px) {
      width: 18px;
      height: 18px;
    }
  }
}
</style>