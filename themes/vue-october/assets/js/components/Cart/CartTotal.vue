<template lang="pug">
  .cart-total(:class="{ 'cart-total--checkout': className === 'checkout' }")
    ._title Расчет
    icon(name="calc" component="cart")._title-ico
    ._item
      ._item-label Сумма:
      ._item-val
        ._item-price {{ (price.sum).toLocaleString('ru') }}
        ._item-curency руб.
    ._item(v-if="price.sale > 0")
      ._item-label Скидка:
      ._item-val
        ._item-price {{ (price.sale).toLocaleString('ru') }}
        ._item-curency руб.
    ._item
      ._item-label.-total Итого:
      ._item-val.-total
        ._item-price {{ (price.total).toLocaleString('ru') }}
        ._item-curency руб.

    button(v-if="showBtn" type="button" @click="$router.push({ name: 'checkout' })")._order Сделать заказ
    button(v-else type="button" @click="$router.push({ name: 'cart' })")._order Назад в корзину

</template>
<script>
export default {
  name: "CartTotal",
  props: {
    price: {
      type: Object,
      required: true
    },
    showBtn: {
      type: Boolean,
      default: true
    },
    className: {
      type: String,
      default: "default"
    }
  }
}
</script>
