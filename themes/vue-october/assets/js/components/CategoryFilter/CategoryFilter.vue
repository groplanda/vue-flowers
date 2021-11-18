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
      button._btn(type="button" @click="openPopup('Рассчитать стоимость заказа')") Рассчитать стоимость заказа
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
  }
}
</script>