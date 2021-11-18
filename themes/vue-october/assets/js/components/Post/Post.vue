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
      required: ""
    }
  }
}
</script>
