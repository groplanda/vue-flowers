<template lang="pug">
  .special.section
    ._container.container
      Loading(v-if="loading")
      ._list(v-else)
        ._item(v-for="stock in stocks" :key="stock.id")
          ._item-content
            ._item-title {{ stock.title }}
            ._item-intro(v-html="stock.description")
            router-link(:to="stock.link")._item-btn Подробнее

          ._item-image
            img(:src="stock.image ? '/storage/app/media' + stock.image : noImage", :alt="stock.title")._item-thumb

</template>
<script>
import Loading from '@vue/components/Preloader/Loading.vue';
import axios from "axios";
export default {
  name: "Special",
  components: {
    Loading
  },
  data() {
    return {
      stocks: [],
      loading: true,
      noImage: "/themes/vue-october/assets/images/no-image.jpg"
    }
  },
  methods: {
    fetchStocks() {
      axios.get("/api/stocks")
      .then(response => {
        this.stocks = response.data;
        this.loading = false;
      })
      .catch(e => {
        console.log(e);
      })
    }
  },
  created() {
    this.fetchStocks();
  }
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.special {
  padding: 50px 0;

  @media(max-width: 767px) {
    padding: 30px 0;
  }

  $root: &;

  &__item {
    background: #fff;
    margin-bottom: 60px;
    display: flex;
    border-radius: 15px;
    box-shadow: 0 10px 29px 0 $shadow;
    min-height: 320px;
    overflow: hidden;

    @media(max-width: 991px) {
      margin-bottom: 30px;
      min-height: 280px;
    }

    @media(max-width: 575px) {
      min-height: auto;
      flex-direction: column-reverse;
    }

    &:last-child {
      margin-bottom: 0;
    }
  }

  &__item-content {
    width: 100%;
    max-width: 320px;
    position: relative;
    padding: 40px;

    @media(max-width: 991px) {
      padding: 25px;
      max-width: 280px;
    }

    @media(max-width: 575px) {
      max-width: 100%;
    }
  }

  &__item-image {
    width: 100%;
    max-width: calc(100% - 320px);
    position: relative;
    @media(max-width: 991px) {
      max-width: calc(100% - 280px);
    }
    @media(max-width: 575px) {
      max-width: 100%;
      min-height: 200px;
    }
  }

  &__item-thumb {
    width: 100%;
    height: 100%;
    object-fit: cover;
    left: 0;
    top: 0;
    position: absolute;
  }

  &__item-title {
    color: $yellow;
    font-size: 40px;
    font-weight: 700;
    line-height: 1;
    margin-bottom: 15px;
    @media(max-width: 991px) {
      font-size: 30px;
    }
    @media(max-width: 575px) {
      font-size: 25px;
    }
  }

  &__item-intro {
    font-size: 16px;
    font-weight: 300;
    position: relative;
    height: 70px;
    overflow: hidden;
    @media(max-width: 991px) {
      font-size: 15px;
    }
    @media(max-width: 767px) {
      font-size: 14px;
    }
    @media(max-width: 575px) {
      height: 34px;
    }
  }

  &__item-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    padding: 12px 30px;
    font-size: 17px;
    font-weight: 500;
    margin-top: 20px;
    background-color: $yellow;
    border-radius: 25px;

    @media(max-width: 575px) {
      font-size: 15px;
    }
  }

}
</style>