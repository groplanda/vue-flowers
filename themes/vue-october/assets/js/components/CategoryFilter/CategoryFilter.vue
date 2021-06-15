<template lang="pug">
  .filters
    ._sorting
      ._sorting-body(:class="{ 'filters__sorting-body--open': showFilterDropdown }")
        ._sorting-result(@click="showFilterDropdown = !showFilterDropdown")
          ._sorting-text {{ resultText }}
          icon(name="filter-arrow" component="filter")._sorting-arrow
        ._sorting-dropdown
          ._sorting-item(v-for="item in filterVars" :key="item.key" @click="onSorting(item)") {{ item.title }}


</template>
<script>
export default {
  name: "CategoryFilter",
  data() {
    return {
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
    }
  }
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';

.filters {
  position: relative;
  display: flex;
  align-items: center;
  margin-bottom: 40px;
  z-index: 2;

  @media(max-width: 767px) {
    margin-bottom: 20px;
  }

  $root: &;

  &__sorting {
    width: 100%;
    max-width: 220px;

    @media(max-width: 575px) {
      max-width: 100%;
    }
  }

  &__sorting-body {
    position: relative;
    cursor: pointer;

    &:hover, &:focus {
      #{$root} {
        &__sorting-result {
          background: #F8F8F8;
        }
      }
    }

    &--open {
      #{$root} {
        &__sorting-dropdown {
          opacity: 1;
          pointer-events: all;
          z-index: 10;
        }
        &__sorting-arrow {
          transform: rotate(180deg);
        }
      }
    }
  }

  &__sorting-result {
    display: flex;
    align-items: center;
    height: 60px;
    background: #FFF;
    border: 2px solid #F8F8F8;
    transition: all .3s ease;
    padding: 0 40px 0 20px;

    @media(max-width: 575px) {
      height: 40px;
      padding: 0 30px 0 15px;
    }
  }

  &__sorting-text {
    font-weight: 500;
    font-size: 15px;
    color: $primary;
    white-space: nowrap;
    @media(max-width: 575px) {
      font-size: 14px;
    }
  }

  &__sorting-arrow {
    position: absolute;
    right: 15px;
    top: calc(50% - 5px);
    width: 10px;
    height: 10px;
    color: $blue;
    transition: transform .3s ease;
  }

  &__sorting-dropdown {
    padding: 20px 0;
    background: $blue;
    box-shadow: 7px 7px 30px $shadow;
    border-radius: 10px;
    margin-top: 20px;
    width: 100%;
    position: absolute;
    top: 100%;
    left: 0;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s;
    @media(max-width: 575px) {
      padding: 15px 0;
      border-radius: 15px;
      margin-top: 10px;
    }
  }

  &__sorting-item {
    position: relative;
    padding: 10px 30px 10px 30px;
    color: #FFF;
    opacity: .8;
    font-weight: 500;
    font-size: 14px;
    transition: opacity 0.3s;

    @media(max-width: 575px) {
      padding: 8px 20px;
    }

    &:hover {
      opacity: 1;
    }
  }
}
</style>