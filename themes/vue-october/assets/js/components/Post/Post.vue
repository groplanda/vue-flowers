<template lang="pug">
  section.post.section
    ._container.container
      h1._title(v-if="title") {{ title }}
      ._content(v-html="content")

</template>
<script>
export default {
  name: "Post",
  props: {
    content: {
      type: String,
      default: ""
    },
    title: {
      type: String,
      required: true
    }
  }
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.post {
  padding: 50px 0;
  @media(max-width: 767px) {
    padding: 30px 0;
  }

  &__title {
    font-size: 40px;
    font-weight: bold;
    margin-bottom: 30px;

    @media(max-width: 767px) {
      font-size: 30px;
    }

    @media(max-width: 575px) {
      font-size: 25px;
      margin-bottom: 20px;
    }
  }

  &__content, &__content p {
    color: $primary;
    font-size: 16px;
    margin-bottom: 15px;

    @media(max-width: 767px) {
      font-size: 14px;
    }
  }
  &__content ul {
    padding-left: 20px;
  }
}
</style>