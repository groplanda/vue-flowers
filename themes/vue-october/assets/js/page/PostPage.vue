<template lang="pug">
  .page
    Post(:title="post.title" :content="post.content" v-if="post")
    Loading(v-else)
</template>
<script>
import setTitle from '@vue/helpers/setTitle.js';
import Post from '@vue/components/Post/Post.vue';
import Loading from '@vue/components/Preloader/Loading.vue';

export default {
  name: "PostPage",
  components: {
    Post,
    Loading
  },
  watch: {
    $route (to){
      const slug = to.params.slug;
      this.$store.dispatch("fetchPost", slug);
    }
  },
  computed: {
    post() {
      return this.$store.getters.getPost;
    }
  },
  methods: {
    fetchPost(slug) {
      this.$store.dispatch("fetchPost", slug);
    }
  },
  created() {
    const slug = this.$route.params.slug
    this.fetchPost(slug);
  },
  updated() {
    setTitle(this.post);
  }
}
</script>