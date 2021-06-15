<template lang="pug">
  section.cart.section
    ._container.container.container--main
      ._heading
        ._title Оформление заказа
      ._row
        CheckoutForm(:products="products" @hideTotal="showCartTotal = false")
        CartTotal(v-if="showCartTotal" :products="products" :showBtn="false" className="checkout")

</template>
<script>
import CartTotal from '@vue/components/Cart/CartTotal';
import CheckoutForm from '@vue/components/ChekoutForm/CheckoutForm';

export default {
  name: "Checkout",
  components: {
    CartTotal,
    CheckoutForm
  },
  data() {
    return {
      showCartTotal: true
    }
  },
  computed: {
    products() {
      return this.$store.getters.getCartProducts;
    },
  },
  created() {
    const cart = this.$store.getters.getCart;
    if (cart.length > 0) {
      this.showCartTotal = true;
      this.$store.dispatch("fetchProductsByIds", cart);
    }
  }
}
</script>