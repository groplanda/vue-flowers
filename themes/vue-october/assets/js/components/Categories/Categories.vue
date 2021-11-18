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