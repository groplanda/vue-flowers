<template lang="pug">
  section.cart.section
    ._container.container.container--main
      ._heading
        ._title Оформление заказа
      ._row
        CheckoutForm(:products="products" @hideTotal="showCartTotal = false" :total="total")
        CartTotal(v-if="showCartTotal" :price="{ sum, sale, total }" :showBtn="false" className="checkout")

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
      this.showCartTotal = true;
      this.$store.dispatch("fetchProductsByIds", cart);
    }
  }
}
</script>