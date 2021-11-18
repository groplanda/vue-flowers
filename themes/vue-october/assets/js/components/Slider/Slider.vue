<template lang="pug">
  .slider.section
    ._container.container
      ._row
        ._slide
          Swiper(ref="BannerSlider" :options="sliderOptions")._swiper
            SwiperSlide(v-for="banner in banners" :key="banner.id")
              ._slide-item
                img(:src="'/storage/app/media' + banner.image", :alt="banner.title")._slide-img
                ._slide-content
                  ._slide-title {{ banner.title }}
                  ._slide-descr(v-html="banner.description")
                  ._slide-price {{ banner.price }}
                  router-link(:to="banner.link")._slide-link Подробнее
          button._arrow.-prev(type="button" @click="prevBanner")
            icon(name="arrow-prev" component="banner")._arrow-ico
          button._arrow.-next(type="button" @click="nextBanner")
            icon(name="arrow-next" component="banner")._arrow-ico
        ._banner
          ._banner-item(v-for="(ad, index) in ads" :key="index")
            template(v-if="+ad.banner_type === 1")
              ._banner-title(v-if="ad.title") {{ ad.title }}
              ._banner-descr(v-if="ad.descr") {{ ad.descr }}
              ._banner-price(v-if="ad.tag") {{ ad.tag }}
              router-link(:to="ad.link" v-if="ad.link")._banner-link
              img(:src="'/storage/app/media' + ad.image", :alt="ad.title")._banner-img
            template(v-else)
              img(:src="'/storage/app/media' + ad.image", :alt="ad.title")._banner-img
              router-link(:to="ad.link" v-if="ad.link")._banner-btn {{ ad.tag }}
                ._banner-ico
                  icon(name="arrow-next" component="banner")._banner-arrow
</template>
<script>
import { Swiper, SwiperSlide, directive } from 'vue-awesome-swiper';
import axios from "axios";
export default {
  name: 'Slider',
  components: {
    Swiper,
    SwiperSlide,
  },
  data() {
    return {
      banners: [],
      sliderOptions: {
        loop: true
      }
    }
  },
  directives: {
    swiper: directive
  },
  computed: {
    swiper() {
      return this.$refs.BannerSlider.$swiper
    },
    ads() {
      return this.$store.getters.getSettings.banners;
    }
  },
  methods: {
    prevBanner() {
      this.swiper.slidePrev();
    },
    nextBanner() {
      this.swiper.slideNext();
    },
    fetchSlides() {
      axios.get("/api/slider")
      .then(response => {
        this.banners = response.data;
      })
      .catch(e => {
        console.log(e);
      })
    }
  },
  created() {
    this.fetchSlides();
  }
}
</script>
