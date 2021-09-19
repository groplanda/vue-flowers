<template lang="pug">
  .product-comment__item
    .product-comment__item-info
      .product-comment__item-date {{ prepareDate }}
      .product-comment__item-name {{ comment.title }}
    .product-comment__item-content
      .product-comment__item-rating
        icon(
          name="star"
          component="product"
          v-for="(item, index) in 5"
          :key="index"
          :class="{'product-comment__item-ico_active': item <= comment.star }").product-comment__item-ico

      .product-comment__item-descr(v-html="comment.description")
</template>
<script>
export default {
  name: "ProductCommentItem",
  data() {
    return {
      monthNames: [
        "Января", "Февраля", "Марта",
        "Апреля", "Мая", "Июня", "Июля",
        "Августа", "Сентября", "Октября",
        "Ноября", "Декабря"
      ]
    }
  },
  props: {
    comment: {
      type: Object,
      required: true
    }
  },
  computed: {
    prepareDate() {
      const date = new Date(this.comment.created_at);
      return date.getDate() + ' ' + this.monthNames[date.getMonth()] + ' ' + date.getFullYear();
    }
  }
}
</script>
