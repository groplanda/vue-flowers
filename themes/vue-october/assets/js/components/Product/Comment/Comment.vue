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