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