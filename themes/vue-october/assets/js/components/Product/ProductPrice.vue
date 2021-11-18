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