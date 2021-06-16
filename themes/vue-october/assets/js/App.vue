<template lang="pug">
  .app
    Header(:settings="settings")
    main.main
      router-view
    Footer(:settings="settings")

</template>

<script>
import Header from '@vue/components/Header/Header';
import Footer from '@vue/components/Footer/Footer.vue';

export default {
  name: "App",
  components: {
    Header,
    Footer
  },
  watch: {
    $route() {
      this.$el.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      })
    }
  },
  computed: {
    settings() {
      return this.$store.getters.getSettings;
    },
    navbar() {
      return this.$store.getters.getNavbar;
    }
  },
  created() {
    this.$store.dispatch("fetchCategories");
    //this.$store.dispatch("fetchNavbar");
    this.$store.dispatch("fetchSettings");
    if (localStorage.getItem('cart')) {
      this.$store.dispatch("fillCart" , JSON.parse(localStorage.getItem('cart')));
    }
  }
}
</script>
