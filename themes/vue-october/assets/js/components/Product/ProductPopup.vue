<template lang="pug">
  transition(name="fade")
    .product-popup(:class="movingClass")
      router-link(:to="{name: 'cart'}")._link Товар добавлен в корзину!
</template>
<script>
export default {
  name: "ProductPopup",
  props: {
    moving: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    movingClass() {
      return {
        'product-popup--moving': this.moving
      }
    }
  }
}
</script>
