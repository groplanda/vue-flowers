<template lang="pug">
  .product-price
    ._current
      strong._val {{ productPrice }}
      span._label руб.
    ._old(v-if="+salePrice !== 0")
      strong._val {{ price.toLocaleString('ru') }}
      span._label руб.
</template>
<script>
export default {
  name: "ProductPrice",
  props: {
    price: {
      type: Number,
      defaul: 0,
    },
    salePrice: {
      type: Number,
      defaul: 0,
    }
  },
  computed: {
    productPrice() {
      if (+this.salePrice !== 0) {
        return (this.salePrice).toLocaleString('ru')
      }
      return (this.price).toLocaleString('ru')
    },
  },
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.product-price {
  width: 100%;
  display: flex;
  flex-wrap: wrap;
  align-items: center;
  margin: 20px 0;

  $root: &;

  @media(max-width: 991px) {
    margin: 0;
    height: 36px;
  }

  @media(max-width: 410px) {
    height: auto;
  }

  &__current {
    font-size: 24px;
    color: $primary;
    margin-right: 20px;

    #{$root} {
      &__price-label {
        font-size: 20px;
      }
    }

    @media(max-width: 1199px) {
      font-size: 22px;
    }

    @media(max-width: 575px) {
      font-size: 18px;
      margin-right: 15px;
    }
  }

  &__val {
    font-weight: bold;
    margin-right: 5px;
  }

  &__old {
    font-size: 20px;
    color: $primary;
    opacity: .2;
    text-decoration: line-through;
    #{$root} {
      &__val {
        font-weight: 500;
      }
      &__label {
        font-size: 18px;

        @media(max-width: 575px) {
          font-size: 14px;
        }
      }
    }

    @media(max-width: 1199px) {
      font-size: 18px;
    }

    @media(max-width: 575px) {
      font-size: 16px;
    }
  }
}
</style>