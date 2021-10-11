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
        router-link(:to="{ name: 'category', params: { slug: productSlug }}" v-if="product.categories")._category  {{ productTitle }}
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
    },
    productSlug() {
      let slug = "#";
      if (this.product.categories[0] && this.product.categories[0].slug) {
        slug = this.product.categories[0].slug;
      }
      return slug;
    },
    productTitle() {
      let title = null;
      if (this.product.categories[0] && this.product.categories[0].title) {
        title = this.product.categories[0].title;
      }
      return title;
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
<style lang="scss">
@import '@/scss/vars.scss';
.product-item {
  width: 100%;
  max-width: 500px;
  position: relative;
  box-shadow: 0 10px 29px 0 $shadow;

  $root: &;

  &__top {
    position: relative;

    &::after {
      position: absolute;
      content: '';
      bottom: 0;
      left: 0;
      right: 0;
      height: 20px;
      background: #fbfbfb;
      border-radius: 20px 20px 0 0;
      z-index: 1;
    }
  }

  &__tags {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    position: absolute;
    top: 20px;
    left: 20px;
    z-index: 1;
  }

  &__tag {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #FFF;
    font-size: 13px;
    font-weight: 500;
    padding: 7px 15px;
    border-radius: 7px;
    text-align: center;
    margin-right: 9px;

    &--new {
      background: $green;
    }

    &--sale {
      background: $red;
    }

    &--hit {
      background: $blue;
    }

    &:last-child {
      margin-right: 0;
    }
  }

  &__image {
    position: relative;
    padding-top: 100%;
    margin: 0;
    overflow: hidden;
    border-radius: 20px 20px 0 0;
  }

  &__link {
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
  }

  &__image-thumb {
    max-width: none;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: 50% 50%;
  }

  &__order-btn {
    padding: 0;
    width: 50px;
    height: 50px;
    border-radius: 25px;
    background: $yellow;
    pointer-events: all;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  &__order-ico {
    width: 25px;
    height: 25px;
    fill: #FFF;
  }

  &__bottom {
    background: #fbfbfb;
    padding: 10px 20px 20px;
    border-radius: 0 0 20px 20px;
    display: flex;
    flex-wrap: wrap;

    @media(max-width: 575px) {
      padding: 10px 15px 15px;
    }

    &--full {
      #{$root} {
        &__info, &__price {
          max-width: 100%;
        }
      }
    }
  }

  &__info {
    width: 100%;
    max-width: calc(100% - 125px);
    height: 47px;
    overflow: hidden;
  }

  &__amount {
    width: 100%;
    max-width: 122px;
    display: flex;
    align-items: center;
  }

  &__title {
    display: block;
    font-weight: 500;
    font-size: 18px;
    color: $primary;
    margin-bottom: 5px;
    transition: color 0.3s;
  }

  &__category {
    font-weight: 500;
    font-size: 14px;
    color: $primary;
    opacity: 0.4;
    margin: 0 0 10px;
  }

  &__cart {
    width: 100%;
    min-height: 50px;
    margin-top: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  &__price {
    max-width: calc(100% - 80px);
    width: 100%;
  }

  &__current-price {
    font-size: 20px;
    color: $red;
    margin-right: 20px;

    #{$root} {
      &__price-label {
        font-size: 18px;
      }
    }

    @media(max-width: 575px) {
      font-size: 18px;
      margin-right: 15px;
    }
  }

  &__price-val {
    font-weight: bold;
    margin-right: 5px;
  }

  &__old-price {
    font-size: 16px;
    color: $primary;
    opacity: .2;
    text-decoration: line-through;
    #{$root} {
      &__price-label {
        font-size: 15px;

        @media(max-width: 575px) {
          font-size: 14px;
        }
      }
    }

    @media(max-width: 575px) {
      font-size: 15px;
    }
  }
}
</style>