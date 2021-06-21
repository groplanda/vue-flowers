<template lang="pug">
  .categories.section
    ._container.container
      ._row
        ._item(v-for="cat in categories" :key="cat.id")
          ._item-link(v-if="+cat.is_quiz" data-js="quiz")
          router-link._item-link(v-else :to="{ name: 'category', params: { slug: cat.slug }}")
          ._item-image
            img(:src="cat.image ? '/storage/app/media' + cat.image : noImage", :alt="cat.title")._item-thumb
          ._item-content
            ._item-title {{ cat.title }}
            icon(name="arrow-down" component="header")._item-ico
</template>
<script>
export default {
  name: "Categories",
  data() {
    return {
      noImage: "/themes/vue-october/assets/images/no-image.jpg"
    }
  },
  computed: {
    categories() {
      return this.$store.getters.getCategories;
    }
  },
  updated() {
    const quizLinks = this.$el.querySelectorAll('[data-js="quiz"]');

    (function(w, d, s, o){
      var j = d.createElement(s); j.async = true; j.src = '//script.marquiz.ru/v2.js';j.onload = function() {
        // eslint-disable-next-line no-undef
        if (document.readyState !== 'loading') Marquiz.init(o);
        else document.addEventListener("DOMContentLoaded", function() {
          // eslint-disable-next-line no-undef
          Marquiz.init(o);
        });
      };
      d.head.insertBefore(j, d.head.firstElementChild);
    })(window, document, 'script', {
        host: '//quiz.marquiz.ru',
        region: 'eu',
        id: '60408b5fd967520046fe1811',
        autoOpen: false,
        autoOpenFreq: 'once',
        openOnExit: false,
        disableOnMobile: false
      }
    );

    if(quizLinks && quizLinks.length) {
      for(let i = 0; i < quizLinks.length; i++) {
        quizLinks[i].addEventListener("click", () => {
          // eslint-disable-next-line no-undef
          Marquiz.showModal('60408b5fd967520046fe1811');
        })
      }
    }

  }
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.categories {
  padding: 40px 0;

  $root: &;

  &__container {
    width: 100%;
    padding: 0 10px;
  }

  &__row {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin: 0 -10px;
    @media(max-width: 767px) {
      margin: 0 -5px;
    }
  }

  &__item {
    width: 100%;
    max-width: calc(25% - 20px);
    margin: 0 10px 20px 10px;
    background: #fff;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 29px 0 $shadow;
    transition: background .3s ease;
    position: relative;

    @media(max-width: 991px) {
      max-width: calc(33.333% - 20px);
    }

    @media(max-width: 767px) {
      max-width: calc(50% - 10px);
      margin: 0 5px 10px 5px;
    }

    &:hover {
      background: $blue;
      #{$root} {
        &__item-content {
          color: #FFF;
        }
        &__item-ico {
          color: #FFF;
        }
      }
    }
  }

  &__item-image {
    width: 100%;
    position: relative;

    &::after {
      content: "";
      display: block;
      padding-top: 69%;
    }
  }

  &__item-link {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    cursor: pointer;
  }

  &__item-thumb {
    width: 100%;
    height: 100%;
    left: 0;
    top: 0;
    object-fit: cover;
    position: absolute;
  }

  &__item-content {
    color: $primary;
    font-size: 18px;
    font-weight: 700;
    line-height: 1.25;
    padding: 22px 50px 22px 25px;
    display: flex;
    align-items: center;
    position: relative;
    transition: color .3 ease;

    @media(max-width: 991px) {
      font-size: 18px;
      padding: 20px 35px 20px 20px;
    }

    @media(max-width: 767px) {
      font-size: 16px;
      padding: 15px 40px 15px 20px;
    }

    @media(max-width: 575px) {
      font-size: 14px;
    }
  }

  &__item-ico {
    position: absolute;
    right: 15px;
    top: calc(50% - 15px);
    width: 30px;
    height: 30px;
    color: $blue;
    transform: rotate(-90deg);

    @media(max-width: 767px) {
      width: 26px;
      height: 26px;
      top: calc(50% - 13px);
    }

    @media(max-width: 575px) {
      width: 20px;
      height: 20px;
      top: calc(50% - 10px);
    }
  }
}
</style>