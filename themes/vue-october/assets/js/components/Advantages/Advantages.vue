<template lang="pug">
  .advantages.section
    ._container.container
      ._row
        ._item(v-for="(adv, index) in advantages" :key="index")
          ._item-img
            img(:src="adv.ico ? '/storage/app/media' + adv.ico : noImage", :alt="adv.title")._item-thumb
          ._item-title {{ adv.title }}
          ._item-descr {{ adv.descr }}


</template>
<script>
export default {
  name: "Advantages",
  computed: {
    advantages() {
      return this.$store.getters.getSettings.advantages;
    }
  },
  data() {
    return {
      noImage: "/themes/vue-october/assets/images/no-image.jpg",
    }
  }
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.advantages {
  background: $blue;
  padding: 30px 0 20px;

  $root: &;

  &__row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -10px;
    @media(max-width: 575px) {
      margin: 0;
    }
  }

  &__item {
    width: 100%;
    max-width: calc(25% - 20px);
    margin: 0 10px;
    position: relative;
    padding: 0 10px 0 60px;

    @media(max-width: 1199px) {
      padding: 0 10px 0 40px;
    }

    @media(max-width: 767px) {
      max-width: calc(50% - 20px);
      margin: 0 10px 20px 10px;

      &:last-child {
        margin-bottom: 0;
      }
    }

    @media(max-width: 575px) {
      max-width: 100%;
      margin: 0 0 20px;
    }
  }

  &__item-img {
    width: 40px;
    height: 40px;
    position: absolute;
    left: 0;
    top: calc(50% - 20px);

    @media(max-width: 1199px) {
      width: 30px;
      height: 30px;
      top: 10px;
    }
  }

  &__item-thumb {
    width: 100%;
    height: 100%;
    fill: #FFF;
  }

  &__item-title {
    color: #fff;
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 5px;
    @media(max-width: 1199px) {
      font-size: 18px;
    }
  }

  &__item-descr {
    color: #fff;
    font-size: 16px;
    font-weight: 400;
    @media(max-width: 1199px) {
      font-size: 14px;
    }
  }
}
</style>