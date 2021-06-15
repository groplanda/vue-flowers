<template lang="pug">
  .mini-cart
    ._heading
      span(v-if="cart > 0") {{ cart }} товар(ов) в вашей корзине
      span(v-else) Корзина пуста!
      button._close(@click="$emit('closeMiniCart')")
        icon(name="close" component="header")._close-ico
    ._body(v-if="products")
      ._product-list
        ._product-item(v-for="product in products" :key="product.id")
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
      button(type="button")._btn.-clear Очистить

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
      noImage: "/themes/vue-october/assets/images/no-image.jpg"
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
<style lang="scss">
@import '@/scss/vars.scss';
.mini-cart {
  position: absolute;
  right: 0;
  top: 100%;
  z-index: 100;
  background: #fff;
  width: 290px;
  border-radius: 10px;
  box-shadow: 0 10px 29px 0 $shadow;

  &__heading {
    padding: 22px 10px 22px 30px;
    color: $primary;
    font-size: 14px;
    font-weight: 400;
    line-height: 1;
    position: relative;
  }

  &__close {
    position: absolute;
    width: 20px;
    height: 20px;
    right: 10px;
    top: calc(50% - 10px);
  }

  &__close-ico {
    width: 100%;
    height: 100%;
    fill: $red;
  }

  &__body {
    border-top: 1px solid $borderColor;
    border-bottom: 1px solid $borderColor;
  }

  &__product-list {
    max-height: calc(100vh - 220px);
    overflow: auto;
  }

  &__product-item {
    padding: 20px 0 20px 30px;
    border-bottom: 1px solid $borderColor;
    display: flex;
    align-items: flex-start;
    position: relative;

    &:last-child {
      border-bottom: 0;
    }
  }

  &__product-image {
    width: 100%;
    max-width: 70px;
    position: relative;
    margin-right: 15px;
    border-radius: 5px;
    overflow: hidden;

    &::after {
      content: "";
      padding-top: 100%;
      display: block;
    }
  }

  &__product-thumb {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    max-width: none;
    object-fit: cover;
    object-position: center;
  }

  &__product-content {
    width: 100%;
    max-width: calc(100% - 85px);
  }

  &__product-name {
    color: $primary;
    font-size: 12px;
    font-weight: 700;
    margin-bottom: 5px;
    text-decoration: underline;
  }

  &__product-code {
    color: $primary;
    font-size: 12px;
    font-weight: 400;
    margin-bottom: 5px;
  }

  &__product-price {
    color: $primary;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;

    &--strike {
      color: $brown;
      font-weight: 400;
      text-decoration: line-through;
    }

    &--sale {
      color: $red;
      font-size: 15px;
      font-weight: 700;
    }

    &:last-child {
      margin-bottom: 0;
    }
  }

  &__product-remove {
    position: absolute;
    width: 18px;
    height: 18px;
    right: 10px;
    top: 10px;
  }

  &__product-ico {
    width: 100%;
    height: 100%;
    fill: $red;
  }

  &__footer {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
    padding: 20px 15px;
  }

  &__btn {
    max-width: calc(50% - 5px);
    width: 100%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: $blue;
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    border-radius: 6px;
    padding: 12px 20px;

    &--clear {
      background: $red;
    }
  }

}
</style>