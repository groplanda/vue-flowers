<template lang="pug">
  .header-nav.section
    ._container.container
      nav._nav
        ._nav-item(v-for="category in categories" :key="category.id")
          router-link(:to="{ name: 'category', params: { slug: category.slug }}")._nav-link {{ category.title }}
          ._nav-dropdown(v-if="category.childs && category.childs.length")
            router-link(
              :to="{ name: 'category', params: { slug: subcat.slug }}"
              v-for="subcat in category.childs"
              :key="subcat.id")._nav-sublink {{ subcat.title }}
      SearchForm

</template>
<script>
import SearchForm from './SearchForm.vue'
export default {
  name: "HeaderNav",
  components: {
    SearchForm
  },
  props: {
    categories: {
      type: Array,
      default() {
        return []
      }
    }
  }
}
</script>