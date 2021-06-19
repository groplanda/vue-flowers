<template lang="pug">
  .product-tab
    ._heading
      ._heading-item(@click="changeTab" data-tab="0" :class="{ 'product-tab__heading-item--active': activeTab == 0 }") Описание
      ._heading-item(v-if="info.props && info.props.length" @click="changeTab" data-tab="1" :class="{ 'product-tab__heading-item--active': activeTab == 1 }") Параметры
      ._heading-item(@click="changeTab" data-tab="2" :class="{ 'product-tab__heading-item--active': activeTab == 2 }") Преимущества
    ._body
      ._body-item(v-if="activeTab == 0")
        ._code(v-if="info.code") Артикул {{ info.code }}
        ._descr(v-html="info.description")
      ._body-item(v-if="activeTab == 1")
        ._props
          ._props-item(v-for="(prop, index) in info.props" :key="index")
            ._props-label {{ prop.props_option }}
            ._props-val {{ prop.props_value }}

      ._body-item(v-if="activeTab == 2")
        ._advantages
          ._advantages-item(v-for="(adv, index) in advantages" :key="index")
            ._advantages-img
              img(:src="adv.ico ? '/storage/app/media' + adv.ico : noImage", :alt="adv.title")._advantages-thumb
            ._advantages-title {{ adv.title }}
            ._advantages-descr {{ adv.descr }}

</template>
<script>
export default {
  name: "ProductTab",
  props: {
    info: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      activeTab: 0,
      noImage: "/themes/vue-october/assets/images/no-image.jpg",
    }
  },
  methods: {
    changeTab(e) {
      const target = e.target;
      this.activeTab = target.dataset.tab;
    }
  },
  computed: {
    advantages() {
      return this.$store.getters.getSettings.advantages;
    }
  }
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.product-tab {
  margin: 60px 0;

  @media(max-width: 767px) {
    margin: 30px 0;
  }

  &__heading {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
  }

  &__heading-item {
    background: $blue;
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 18px 20px 25px;
    margin-bottom: -12px;
    border-radius: 15px 15px 0 0;
    transition: color .3s ease, background .3s ease;
    margin-right: 5px;
    cursor: pointer;

    @media(max-width: 767px) {
      font-size: 14px;
      padding: 12px 15px 18px;
    }

    @media(max-width: 575px) {
      font-size: 13px;
      padding: 10px 10px 20px;
    }

    &--active {
      color: $blue;
      background: #FFF;
    }

  }

  &__body {
    background: #fff;
    padding: 30px 20px;
    border-radius: 15px;
    box-shadow: 0 10px 29px 0 $shadow;
    position: relative;

    @media(max-width: 767px) {
      padding: 20px 15px;
    }
  }

  &__body-item {
    width: 100%;
  }

  &__code {
    font-size: 14px;
    color: $primary;
    margin: 15px 0;

    @media(max-width: 767px) {
      margin: 10px 0;
    }
  }

  &__descr {
    color: $primary;
    font-size: 16px;
    line-height: 21px;

    @media(max-width: 767px) {
      font-size: 14px;
      line-height: 18px;
    }

    p {
      margin-bottom: 15px;

      @media(max-width: 767px) {
        margin-bottom: 10px;
      }

      &:last-child {
        margin-bottom: 0;
      }
    }
  }

  &__props-item {
    width: 100%;
    height: 30px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;

    &::after {
      content: "";
      display: block;
      width: 100%;
      height: 1px;
      border-bottom: 1px dashed $secondary;
      position: absolute;
      left: 0;
      bottom: 9px;
    }
  }

  &__props-label, &__props-val {
    font-size: 16px;
    line-height: 21px;
    background: #FFF;
    z-index: 1;
    position: relative;

    @media(max-width: 767px) {
      font-size: 14px;
      line-height: 19px;
    }
  }

  &__props-label {
    color: $secondary;
    padding-right: 4px;
  }

  &__props-val {
    color: $primary;
    font-weight: 500;
    padding-left: 4px;
  }

  &__advantages {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -10px;
  }

  &__advantages-item {
    width: 100%;
    max-width: calc(25% - 20px);
    margin: 0 10px 20px 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;

    @media(max-width: 1199px) {
      max-width: calc(50% - 20px);
    }

    @media(max-width: 575px) {
      max-width: calc(100% - 20px);
    }
  }

  &__advantages-img {
    width: 90px;
    height: 90px;
    display: flex;
    margin-bottom: 10px;
    padding: 20px;
    border-radius: 45px;
    background: $blue;
  }

  &__advantages-title {
    font-size: 15px;
    color: $primary;
    font-weight: 500;
    margin-bottom: 10px;
  }

  &__advantages-descr {
    font-size: 14px;
    color: $secondary;
  }
}
</style>