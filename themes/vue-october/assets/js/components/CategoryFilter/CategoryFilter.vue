<template lang="pug">
  .filters
    ._sorting
      ._sorting-body(:class="{ 'filters__sorting-body--open': showFilterDropdown }")
        ._sorting-result(@click="showFilterDropdown = !showFilterDropdown")
          ._sorting-text {{ resultText }}
          icon(name="filter-arrow" component="filter")._sorting-arrow
        ._sorting-dropdown
          ._sorting-item(v-for="item in filterVars" :key="item.key" @click="onSorting(item)") {{ item.title }}
    ._buttons
      button._btn(type="button" @click="openPopup('Получить прайс-лист')") Прайс-лист
      button._btn(type="button" data-modal="openQuiz") Рассчитать стоимость заказа
      button._btn(type="button" @click="openPopup('Консультация специалиста')") Консультация специалиста

    Popup(v-if="popup" @close="popup = false")
      ._popup-title {{ selectedBtn }}
      ContactForm(:showUserMail="true" :userSubject="'Заявка: ' + selectedBtn")


</template>
<script>
import ContactForm from '@vue/components/ContactForm/ContactForm';
import Popup from '@vue/components/Popup/Popup';

export default {
  name: "CategoryFilter",
  components: {
    ContactForm,
    Popup
  },
  data() {
    return {
      popup: false,
      selectedBtn: "",
      resultText: "Сортировка",
      showFilterDropdown: false,
      filterVars: [
        { title: 'Убывание цены', key: 'prive_low' },
        { title: 'Возрастание цены', key: 'prive_up' },
        { title: 'Вначале новые', key: 'fist_new' },
        { title: 'Вначале старые', key: 'fist_old' }
      ]
    }
  },
  methods: {
    onSorting(item) {
      this.resultText = item.title
      this.showFilterDropdown = false;
      this.$emit("sortedProducts", item.key);
    },
    openPopup(val) {
      this.selectedBtn = val;
      this.popup = true;
    }
  },
  mounted() {
     const quizBtn = this.$el.querySelector('[data-modal="openQuiz"]');

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
        id: '61c99986d56678004f0ab6ba',
        autoOpen: false,
        autoOpenFreq: 'once',
        openOnExit: false,
        disableOnMobile: false
      }
    );
    quizBtn.addEventListener("click", () => {
      // eslint-disable-next-line no-undef
      Marquiz.showModal('61c99986d56678004f0ab6ba');
    })

  }
}
</script>