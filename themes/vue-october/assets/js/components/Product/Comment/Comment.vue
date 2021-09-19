<template lang="pug">
  .product-comment
    ._list(v-if="comments.length")
      CommentItem(v-for="comment in comments" :key="comment.id" :comment="comment")
    ._no-comment(v-else) Отзывов пока нет
    button._button(type="button" @click="popup = true") Добавить отзыв
    Popup(v-if="popup" @close="closePopup")
      CommentForm
</template>
<script>
import CommentItem from "./CommentItem";
import Popup from '@vue/components/Popup/Popup';
import CommentForm from "./CommentForm";

export default {
  name: "ProductComment",
  data() {
    return {
      popup: false
    }
  },
  components: {
    CommentItem,
    CommentForm,
    Popup
  },
  props: {
    comments: {
      type: Array,
      default() {
        return []
      }
    }
  },
  methods: {
    closePopup() {
      this.popup = false;
    }
  },
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';

.product-comment {

  &__list {
    display: flex;
    flex-direction: column;
    margin-bottom: 30px;
  }

  &__no-comment {
    color: $primary;
    font-size: 22px;
    font-weight: 400;
    margin-bottom: 20px;
  }

  &__item {
    display: flex;
    flex-wrap: wrap;
    background: #FFF;
    margin-bottom: 20px;
    padding: 20px 30px;
    box-shadow: 7px 7px 30px rgba($color: #000000, $alpha: .1);
    border-radius: 10px;

    @media(max-width: 767px) {
      padding: 20px 10px;
    }

    &:last-child {
      margin-bottom: 0;
    }
  }

  &__item-info {
    flex: 0 0 200px;

    @media(max-width: 1199px) {
      flex: 0 0 165px;
    }

    @media(max-width: 767px) {
      flex: 1 1 auto;
      width: 100%;
      margin-bottom: 20px;
    }
  }

  &__item-date {
    color: $secondary;
    font-size: 14px;
    font-weight: 300;
    line-height: 19px;
    margin-bottom: 10px;
  }

  &__item-name {
    font-size: 18px;
    line-height: 24px;
    font-weight: 400;

    @media(max-width: 1199px) {
      font-size: 16px;
      line-height: 19px;
    }
  }

  &__item-content {
    flex: 1 1;
    @media(max-width: 767px) {
      flex: 1 1 auto;
      width: 100%;
    }
  }

  &__item-rating {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    margin-bottom: 20px;
    @media(max-width: 767px) {
      margin-bottom: 15px;
    }
  }

  &__item-ico {
    fill: $secondary;
    width: 20px;
    height: 20px;
    margin-right: 8px;

    @media(max-width: 767px) {
      width: 16px;
      height: 16px;
      margin-right: 5px;
    }

    &_active {
      fill: $yellow;
    }
  }

  &__item-descr {
    font-size: 14px;
    line-height: 20px;

    @media(max-width: 767px) {
      font-size: 12px;
    }
  }

  &__button {
    display: inline-flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 500;
    font-size: 15px;
    line-height: 1.2;
    padding: 10px 35px;
    color: #fff;
    border: none;
    background: $blue;
    box-shadow: 7px 7px 30px rgba($color: #000000, $alpha: .1);
    transition: all .18s linear;
    border-radius: 10px;

    @media(max-width: 767px) {
      font-size: 14px;
    }
  }

}
</style>