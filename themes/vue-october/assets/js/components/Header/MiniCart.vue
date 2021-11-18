<template lang="pug">
  .mini-cart
    ._heading
      span(v-if="cart > 0") {{ cart }} товар(ов) в вашей корзине
      span(v-else) Корзина пуста!
      button._close(@click="$emit('closeMiniCart')")
        icon(name="close" component="header")._close-ico
    ._body(v-if="miniCartProducts")
      ._product-list
        ._product-item(v-for="product in miniCartProducts" :key="product.id")
          router-link(:to="{ name: 'product', params: { id: product.id }}")._product-image
            img(:src="product.image ? '/storage/app/media' + product.image : noImage", :alt="product.title")._product-thumb
          ._product-content
            router-link(:to="{ name: 'product', params: { id: product.id }}")._product-name {{ product.title }}
            ._product-price(v-if="product.price" :class="'mini-cart__product-price--strike:' +product.sale_price !== 0") {{ productPrice(product) }} ₽
            ._product-price.-sale(v-if="+product.sale_price !== 0") {{ (product.price).toLocaleString('ru') }} ₽

          button._product-remove(type="button" @click="deleteProduct(product.id)")
            icon(name="close" component="header")._product-ico
    ._footer(v-if="cart > 0")
      router-link(:to="{ name: 'cart' }")._btn Оформить

</template>
<script>
export default {
  name:"MiniCart",
  props: {
    products: {
      type: Array,
      default() {
        return []
      }
    },
    cart: {
      type: Number,
      default: 0
    }
  },
  data() {
    return {
      noImage: "/themes/vue-october/assets/images/no-image.jpg",
      miniCartProducts: this.products
    }
  },
  methods: {
    deleteProduct(id) {
      this.$store.dispatch("deleteProduct", id);
      this.$emit("deleteProduct", id);
    },
    productPrice(product) {
      if (+product.sale_price !== 0) {
        return (product.sale_price).toLocaleString('ru')
      }
      return (product.price).toLocaleString('ru')
    }
  }
}
</script>