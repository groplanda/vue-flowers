<template lang="pug">
  form.product-item(@submit.prevent="addToCart")
    input(type="hidden" v-model="product.id")
    ._top
      ._tags
        ._tag.-new(v-if="+product.is_new") New
        ._tag.-sale(v-if="+product.sale_price") {{ productPercent }}
        ._tag.-hit(v-if="+product.is_hit") Хит
      ._image
        router-link(:to="{ name: 'product', params: { id: product.id }}")._link
          img(:src="product.image ? '/storage/app/media' + product.image : noImage", :alt="product.title")._image-thumb

    ._bottom(:class="{ 'product-item__bottom--full': +product.price === 0 }")
      ._info
        router-link(:to="{ name: 'product', params: { id: product.id }}")._title {{ product.title }}
        router-link(:to="{ name: 'category', params: { slug: product.categories[0].slug }}" v-if="product.categories && product.categories[0]")._category  {{ product.categories[0].title}}
      template(v-if="+product.price > 0")
        ._amount
          ProductAmount(@changeAmount="changeAmount" :amount="amount")
        ._cart
          ._price
            ._current-price
              strong._price-val {{ productPrice }}
              span._price-label ₽
            ._old-price(v-if="+product.sale_price !== 0")
              strong._price-val {{ (product.price).toLocaleString('ru') }}
              span._price-label ₽
          button(type="submit")._order-btn
            icon(name="shopping-cart" component="product")._order-ico

      ._cart(v-else)
        ._price
          ._current-price
            strong._price-val Цена договорная



</template>
<script>
import ProductAmount from './ProductAmount'
export default {
  name: "ProductSliderItem",
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
      amount: 1,
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
    productPercent() {
      let result = 0;
      if (+this.product.sale_price !== 0) {
        result = 100 - ((this.product.sale_price / this.product.price * 100).toFixed(0))
      }
      return "-" + result + "%";
    }
  },
  methods: {
    changeAmount(val) {
      this.amount = val;
    },
    addToCart() {
      if( this.amount > 0 && this.product?.id) {
        this.$store.dispatch("addToCart", { id: this.product.id, amount: this.amount })
        this.amount = 1;
        this.$emit("showPopup", true);
      }
    }
  }
}
</script>
