<template lang="pug">
  .mobile-nav(:class="showClassName")
    ._inner
      button(type="button" @click="$emit('closeNav')")._close
        icon(name="close" component="header")._close-ico
      ._inner-scroll
        ._nav
          ._nav-item(v-for="category in categories" :key="category.id" data-js="nav-item")
            ._nav-link
              button(@click="toggleMenu")._nav-btn
                icon(name="arrow-down" component="header")._nav-ico
              router-link(:to="{ name: 'category', params: { slug: category.slug }}")._nav-text {{ category.title }}
            ._nav-dropdown(v-if="category.childs && category.childs.length" data-js="dropdown-item")
              router-link(to="#!" v-for="subcat in category.childs"  :key="subcat.id")._nav-sublink {{ subcat.title }}
        ._nav.-menu
          ._nav-item.-page(v-for="(page, index) in mobilepages" :key="index")
            router-link(to="#!")._nav-link
              button._nav-btn
                icon(name="arrow-down" component="header")._nav-ico
              span {{ page.title }}


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
    }
  },
  data() {
    return {
      mobilepages: [
        { title: 'О компании', url: '/' },
        { title: 'Помощь', url: '/' },
        { title: 'Контакты', url: '/' },
        { title: 'Акции и скидки', url: '/' },
        { title: 'Оплата и доставка', url: '/' }
      ]
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
<style lang="scss">
@import '@/scss/vars.scss';
.mobile-nav {
  width: 100%;
  max-width: 300px;
  height: 100vh;
  transition: all .3s ease;
  background: #FFF;
  position: fixed;
  left: 0;
  top: 0;
  box-shadow: none;
  overflow-y: auto;
  z-index: 100;
  display: none;
  transform: translateX(-100%);
  transition: transform .3s ease, box-shadow .2s ease .4s;

  &--show {
    transform: translateX(0);
    box-shadow: 0 0 0 3000px rgb(0, 0, 0, .5);
  }

  $root: &;

  @media(max-width: 767px) {
    display: block;
  }

  &__inner {
    width: 100%;
    position: relative;
    padding: 45px 20px 30px;
  }

  &__close {
    width: 40px;
    height: 40px;
    position: absolute;
    right: 10px;
    top: 15px;
  }

  &__close-ico {
    width: 100%;
    height: 100%;
    fill: $blue;
  }

  &__nav {
    &--menu {
      margin-top: 20px;
      padding-top: 20px;
      border-top: 2px solid #f3f3f3;
    }
  }

  &__nav-item {
    margin-bottom: 20px;

    &:last-child {
      margin-bottom: 0;
    }

    &--opened {
      #{$root} {
        &__nav-ico {
          transform: rotate(180deg);
        }
        &__nav-dropdown {
          margin-top: 15px;
          overflow: auto;
        }
      }
    }

    &--page {
      #{$root} {
        &__nav-ico {
          color: $secondary;
          transform: rotate(-q90deg);
        }
        &__nav-link {
          font-size: 15px;
          color: $secondary;
          font-weight: 300;
        }
      }
    }
  }

  &__nav-btn {
    width: 20px;
    height: 20px;
    left: 0;
    top: 0;
    position: absolute;
    cursor: pointer;
    z-index: 3;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  &__nav-ico {
    width: 100%;
    height: 100%;
    color: $blue;
    transition: transform .3s ease;
    pointer-events: none;
  }

  &__nav-link {
    padding-left: 35px;
    position: relative;
    display: flex;
    align-items: center;
  }

  &__nav-text {
    color: $blue;
    font-size: 16px;
    font-weight: 600;
  }

  &__nav-dropdown {
    display: flex;
    flex-direction: column;
    margin-top: 0;
    padding-left: 35px;
    height: 0;
    overflow: hidden;
    transition: margin-top .3s ease, height .3s ease;
  }

  &__nav-sublink {
    width: 100%;
    display: flex;
    padding: 5px 0;
    color: $primary;
    font-size: 15px;
    font-weight: 600;
  }
}
</style>