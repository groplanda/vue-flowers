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
<style lang="scss">
@import '@/scss/vars.scss';
.header-nav {
  background: $blue;
  padding: 10px 0;

  $root: &;

  &__container {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
  }

  &__nav {
    display: flex;
    align-items: center;
    width: 100%;
    max-width: calc(100% - 310px);

    @media(max-width: 991px) {
      max-width: calc(100% - 240px);
    }

    @media(max-width: 767px) {
      display: none;
    }
  }

  &__nav-item {
    position: relative;

    &:hover {
      #{$root} {
        &__nav-link {
          color: $blue;
          background: #FFF;
        }
        &__nav-dropdown {
          opacity: 1;
          pointer-events: all;
        }
      }
    }
  }

  &__nav-link {
    display: block;
    padding: 14px 19px;
    color: #FFF;
    font-size: 17px;
    line-height: 1;
    font-weight: 600;
    transition: all .3s ease;
    border-radius: 30px;

    @media(max-width: 1199px) {
      padding: 14px;
      font-size: 15px;
    }
    @media(max-width: 991px) {
      font-size: 14px;
    }
  }

  &__nav-dropdown {
    z-index: 200;
    border-radius: 12px;
    background: #fff;
    box-shadow: 0 10px 29px 0 $shadow;
    padding: 12px 0;
    position: absolute;
    width: 210px;
    top: 100%;
    left: 0;
    opacity: 0;
    pointer-events: none;
  }

  &__nav-sublink {
    color: $blue;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    display: block;
    padding: 8px 10px;

    &:hover {
      color: $primary;
    }
  }
}
</style>