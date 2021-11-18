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
        this.productPopup = false;
      }, 2500)
    }
  }
}
</script>