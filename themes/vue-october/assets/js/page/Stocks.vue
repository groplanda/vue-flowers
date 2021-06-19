<template lang="pug">
  section.post.section
    ._container.container
      h1._title {{ data.title }}
    Special

</template>
<script>
import setTitle from '@vue/helpers/setTitle.js';
import Special from '@vue/components/Special/Special';

export default {
  name: "PostPage",
  components: {
    Special
  },
  data() {
    return {
      data: {
        title: 'Акции и скидки'
      }
    }
  },
  updated() {
    setTitle(this.data);
  }
}
</script>