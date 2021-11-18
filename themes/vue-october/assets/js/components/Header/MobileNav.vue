<template lang="pug">
  .mobile-nav(:class="showClassName")
    ._inner
      button(type="button" @click="$emit('closeNav')")._close
        icon(name="close" component="header")._close-ico
      ._inner-scroll
        ._nav
          ._nav-item(v-for="category in categories" :key="category.id" data-js="nav-item")
            ._nav-link
              button(@click="toggleMenu"  :class="{'mobile-nav__nav-btn--disabled': category.childs && category.childs.length == 0}")._nav-btn
                icon(name="arrow-down" component="header")._nav-ico
              router-link(:to="{ name: 'category', params: { slug: category.slug }}")._nav-text {{ category.title }}
            ._nav-dropdown(v-if="category.childs && category.childs.length" data-js="dropdown-item")
              router-link(:to="{ name: 'category', params: { slug: subcat.slug }}" v-for="subcat in category.childs"  :key="subcat.id")._nav-sublink {{ subcat.title }}
        ._nav.-menu
          ._nav-item.-page(v-for="(link, idx) in navbar" :key="idx")
            router-link(:to="link.url")._nav-link
              button._nav-btn
                icon(name="arrow-down" component="header")._nav-ico
              span {{ link.title }}


</template>
<script>
export default {
  name: "MobileNav",
  props: {
    showMobileNav: {
      type: Boolean,
      default: false
    },
    categories: {
      type: Array,
      default() {
        return []
      }
    },
    navbar: {
      type: Array,
      default() {
        return []
      }
    }
  },
  computed: {
    showClassName() {
      return {
        'mobile-nav--show': this.showMobileNav
      }
    }
  },
  methods: {
    toggleMenu(e) {
      if(e && e.target) {
        const className = "mobile-nav__nav-item--opened";
        const parent = e.target.closest('[data-js="nav-item"]');
        const dropdown = parent.querySelector('[data-js="dropdown-item"]');
        if(dropdown) {
          parent.classList.toggle(className);
          if (parent.classList.contains(className)) {
            dropdown.style.height = dropdown.scrollHeight + 'px';
          } else {
            dropdown.style.height = 0;
          }
        }
      }
    }
  }
}
</script>