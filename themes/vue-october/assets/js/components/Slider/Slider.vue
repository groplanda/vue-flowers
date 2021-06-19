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
<style lang="scss">
@import '@/scss/vars.scss';
.slider {
  padding: 30px 0;
  background: #FFF;

  $root: &;

  &__row {
    display: flex;
    flex-wrap: wrap;
  }

  &__slide {
    width: 100%;
    max-width: calc(100% - 320px);
    position: relative;

    @media(max-width: 991px) {
      max-width: calc(100% - 270px);
    }

    @media(max-width: 767px) {
      max-width: 100%;
    }
  }

  &__slide-item {
    display: flex;
    align-items: center;
    width: 100%;
    position: relative;
    min-height: 430px;
    border-radius: 15px;
    overflow: hidden;
    @media(max-width: 991px) {
      min-height: 420px;
    }

    @media(max-width: 767px) {
      min-height: 350px;
    }
  }

  &__slide-img {
    max-width: none;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    position: absolute;
    left: 0;
    top: 0;
  }

  &__slide-content {
    margin: 30px;
    background: rgba(255,255,255,.8);
    border-radius: 15px;
    padding: 30px 30px;
    max-width: 500px;
    position: relative;

    @media(max-width: 1199px) {
      margin: 20px;
      padding: 20px;
    }

    @media(max-width: 767px) {
      margin: 10px;
      padding: 10px 15px;
    }
  }

  &__slide-title {
    color: $primary;
    font-size: 40px;
    font-weight: 700;
    margin-bottom: 8px;

    @media(max-width: 1199px) {
      font-size: 35px;
    }

    @media(max-width: 767px) {
      font-size: 30px;
    }

    @media(max-width: 350px) {
      font-size: 20px;
    }
  }

  &__slide-descr {
    font-size: 16px;
    font-weight: 300;
    color: $primary;
    margin-bottom: 10px;
    @media(max-width: 1199px) {
      font-size: 15px;
    }
    @media(max-width: 767px) {
      font-size: 14px;
    }
  }

  &__slide-price {
    color: $red;
    font-size: 35px;
    font-weight: 700;
    margin-bottom: 5px;
    @media(max-width: 1199px) {
      font-size: 30px;
    }
    @media(max-width: 767px) {
      font-size: 25px;
    }
  }

  &__slide-link {
    color: #fff;
    font-size: 16px;
    font-weight: 500;
    background: $yellow;
    border-radius: 25px;
    padding: 12px 32px 13px;
    margin-top: 15px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    @media(max-width: 1199px) {
      font-size: 15px;
    }
  }

  &__arrow {
    position: absolute;
    bottom: 30px;
    width: 38px;
    height: 38px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #FFF;
    border-radius: 19px;
    z-index: 1;
    cursor: pointer;

    @media(max-width: 767px) {
      bottom: 15px;
    }

    &--prev {
      right: 80px;

      @media(max-width: 767px) {
        right: 65px;
      }

      #{$root} {
        &__arrow-ico {
          margin-right: 2px;
        }
      }
    }

    &--next {
      right: 30px;

      @media(max-width: 767px) {
        right: 15px;
      }

      #{$root} {
        &__arrow-ico {
          margin-left: 2px;
        }
      }
    }
  }

  &__arrow-ico {
    color: #FFF;
    width: 20px;
    height: 20px;
  }

  &__banner {
    width: 100%;
    max-width: 290px;
    margin-left: 30px;

    @media(max-width: 991px) {
      max-width: 250px;
      margin-left: 20px;
    }

    @media(max-width: 767px) {
      max-width: 100%;
      margin: 20px 0 0;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
    }
  }

  &__banner-item {
    border-radius: 15px;
    padding: 20px 23px 18px 30px;
    z-index: 5;
    margin-bottom: 30px;
    overflow: hidden;
    height: 200px;
    background: #ccc;
    position: relative;

    &:last-child {
      margin-bottom: 0;
    }

    @media(max-width: 1199px) {
      padding: 20px;
    }

    @media(max-width: 991px) {
      margin-bottom: 20px;
    }

    @media(max-width: 767px) {
      width: 100%;
      max-width: calc(50% - 10px);
    }

    @media(max-width: 575px) {
      max-width: 100%;
    }
  }

  &__banner-title {
    color: #fff;
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 5px;
    z-index: 1;
    position: relative;
    @media(max-width: 991px) {
      font-size: 20px;
    }
  }

  &__banner-descr {
    color: #fff;
    font-size: 16px;
    font-weight: 300;
    max-height: 62px;
    overflow: hidden;
    z-index: 1;
    position: relative;
    @media(max-width: 991px) {
      font-size: 14px;
    }
  }

  &__banner-price {
    margin-top: 5px;
    color: #fff;
    font-size: 35px;
    font-weight: 700;
    z-index: 1;
    position: relative;
  }

  &__banner-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    max-width: none;
    object-fit: cover;
  }

  &__banner-link {
    position: absolute;
    left: 0;
    top: 0;
    z-index: 3;
    width: 100%;
    height: 100%;
  }

  &__banner-btn {
    position: absolute;
    right: 20px;
    bottom: 20px;
    text-decoration: none;
    background: $blue;
    color: #fff;
    font-size: 20px;
    font-weight: 700;
    padding: 10px 50px 10px 25px;
    border-radius: 25px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    @media(max-width: 1199px) {
      font-size: 18px;
    }
  }

  &__banner-ico {
    width: 24px;
    height: 24px;
    border: 2px solid #FFF;
    border-radius: 12px;
    top: calc(50% - 10px);
    right: 10px;
    position: absolute;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  &__banner-arrow {
    width: 12px;
    height: 12px;
    color: #FFF;
    margin-left: 2px;
  }
}
</style>