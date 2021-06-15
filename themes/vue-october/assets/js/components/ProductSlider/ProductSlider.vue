<template lang="pug">
  section.product-slider.section
    ._container.container
      ProductPopup(v-if="productPopup" :moving="isMoving")
      ._heading
        ._title {{ title }}
        ._controls
          button._controls-btn.-prev(@click="swiper.slidePrev()")
            icon(name="arrow-prev" component="banner")._controls-ico
          button._controls-btn.-next(@click="swiper.slideNext()")
            icon(name="arrow-next" component="banner")._controls-ico
      ._main
        Swiper(ref="productSlider" :options="sliderOptions")._swiper
          SwiperSlide(v-for="(product, idx) in products" :key="idx")._item
            ProductSliderItem(:product="product" @showPopup="showPopup")


</template>
<script>
import { Swiper, SwiperSlide, directive } from 'vue-awesome-swiper';
import ProductSliderItem from './ProductSliderItem.vue';
import ProductPopup from '@vue/components/Product/ProductPopup';

export default {
  name: "ProductSlider",
  components: {
    Swiper,
    SwiperSlide,
    ProductSliderItem,
    ProductPopup
  },
  props: {
    title: {
      type: String,
      required: true,
    },
    products: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      productPopup: false,
      isMoving: false,
      sliderOptions: {
        slidesPerView: 1,
        spaceBetween: 10,
        loop: false,
        breakpoints: {
          575: {
            slidesPerView: 2
          },
          767: {
            slidesPerView: 3
          },
          1199: {
            slidesPerView: 4,
            spaceBetween: 15
          }
        }
      }
    }
  },
  directives: {
    swiper: directive
  },
  computed: {
    swiper() {
      return this.$refs.productSlider.$swiper
    }
  },
  methods: {
    showPopup(event) {
      this.productPopup = event;
      setTimeout(() => {
        this.isMoving = true
        setTimeout(() => {
          this.productPopup = false;
          this.isMoving = false
        }, 2500)
      }, 1000)
    }
  }
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.product-slider {
  padding-bottom: 50px;

  @media(max-width: 767px) {
    padding-bottom: 30px;
  }

  $root: &;

  &__heading {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 30px;

    @media(max-width: 767px) {
      margin-bottom: 20px;
    }
  }

  &__title {
    font-weight: bold;
    font-size: 40px;
    color: $blue;

    @media(max-width: 1199px) {
      font-size: 35px;
    }

    @media(max-width: 767px) {
      font-size: 30px;
    }

    @media(max-width: 575px) {
      font-size: 25px;
    }

    @media(max-width: 480px) {
      font-size: 20px;
      padding-right: 20px;
    }
  }

  &__controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #FFF;
    box-shadow: 0 10px 15px 0$shadow;
    border-radius: 20px;
    margin-left: auto;
    padding: 10px 14px;
  }

  &__controls-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 24px;
    width: 24px;
    border-radius: 12px;
    border: 2px solid $primary;

    &--prev {
      margin-right: 10px;

      #{$root} {
        &__controls-ico {
          margin-right: 2px;
        }
      }
    }

    &--next {
      #{$root} {
        &__controls-ico {
          margin-left: 2px;
        }
      }
    }
  }

  &__controls-ico {
    width: 12px;
    height: 12px;
    color: $primary;
  }

  &__item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 5px 10px 30px 10px;
  }
}
</style>