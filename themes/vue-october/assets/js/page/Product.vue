<template lang="pug">
  section.product.section
    Popup(v-if="popup" @close="popup = false")
      ._popup-title Заказать {{ product.title }}
      ContactForm(:showUserMail="true" :userSubject="'Заявка на товар: ' + product.title")
    transition(name="fade")
      ProductPopup(v-if="productPopup" :moving="isMoving")
    ._container.container
      ._heading
        h1._title {{ product.title }}
      ._row(data-sticky-container)
        ._body
          ._slider
            ._tags
              ._tag.-new(v-if="+product.is_new") New
              ._tag.-sale(v-if="+product.sale_price") {{ productPercent }}
              ._tag.-hit(v-if="+product.is_hit") Хит
            ._slider-current
              img(:src="currentImage ? currentImage : noImage" :zoomSrc="currentImage ? currentImage : noImage")._slider-image
            ._slider-additional(v-if="gallery && gallery.length")
              button._slider-nav.-prev(type="button" @click="prevBanner")
                icon(name="arrow-down" component="header")._slider-ico
              button._slider-nav.-next(type="button" @click="nextBanner")
                icon(name="arrow-down" component="header")._slider-ico
              Swiper(ref="addSlider" :options="sliderOptions")._swiper-container
                SwiperSlide._swiper(v-for="(image, index) in gallery" :key="index")
                  ._slider-thumb(
                    :class="{'product__slider-thumb--active': currentImage === image.path}"
                    @click="changeImage(image.path)")
                    img(:src="image.path", :alt="image.title")._slider-thumb-image

          ._content
            ProductTab(:info="product")

          ._bottom
            ._order
              ._order-row
                ProductPrice(v-if="product.price" :price="+product.price" :salePrice="+product.sale_price")
                ProductAmount(@changeAmount="changeAmount" @amount="amount")
              button(type="button" @click="addToCart")._add
                | В корзину
                icon(name="cart" component="header")._add-ico

            ._folders(v-if="product.tags && product.tags.length")
              ._folders-title Теги
              ._folders-list
                router-link(
                  :to="{ name: 'tag', params: { slug: tag.slug }}"
                  v-for="tag in product.tags"
                  :key="tag.slug")._folders-link {{ tag.title }}
            ._folders
              ._folders-title Находится в разделах
              ._folders-list
                router-link(
                  :to="{ name: 'category', params: { slug: category.slug }}"
                  v-for="category in product.categories"
                  :key="category.slug")._folders-link {{ category.title }}


        ._form(data-sticky-for="991" data-margin-top="20" data-sticky-class="product__form--sticky" data-js="sticky")
          ._form-title Сделать заказ
          ._form-tag
            | В наличие
            icon(name="check" component="product")._form-tag-ico
          template(v-if="+product.price !== 0")
            ProductPrice(:price="+product.price" :salePrice="+product.sale_price")
            ProductAmount(@changeAmount="changeAmount" :amount="amount")
            button(type="button" @click="addToCart")._add
              | В корзину
              icon(name="shopping-cart" component="product")._add-ico
          button(type="button" v-else @click="popup = true")._add
            | Сделать заказ
            icon(name="shopping-cart" component="product")._add-ico

</template>
<script>
const Sticky = require('sticky-js');
import setTitle from '@vue/helpers/setTitle.js';
import ProductPopup from '@vue/components/Product/ProductPopup';
import ProductAmount from '@vue/components/ProductSlider/ProductAmount';
import ProductPrice from '@vue/components/Product/ProductPrice';
import { Swiper, SwiperSlide, directive } from 'vue-awesome-swiper';
import Popup from '@vue/components/Popup/Popup';
import ContactForm from '@vue/components/ContactForm/ContactForm';
import ProductTab from '@vue/components/Product/ProductTab';

export default {
  name: "Product",
  components: {
    ProductPopup,
    ProductAmount,
    ProductPrice,
    Swiper,
    SwiperSlide,
    directive,
    Popup,
    ContactForm,
    ProductTab
  },
  data() {
    return {
      popup: false,
      isMoving: false,
      currentImage: "",
      amount: 1,
      productPopup: false,
      noImage: "/themes/vue-october/assets/images/no-image.jpg",
      sliderOptions: {
        slidesPerView: 2,
        spaceBetween: 10,
        loop: false,
        direction: 'vertical',
        breakpoints: {
          575: {
            slidesPerView: 3
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
  watch: {
    $route (to){
      this.id = to.params.id;
      this.$store.dispatch("fetchProductById", this.id);
    },
    product() {
      if (this.product && this.product.image) {
        this.changeImage('/storage/app/media' +  this.product.image);
      }
    }
  },
  computed: {
    product() {
      return this.$store.getters.getProduct;
    },
    gallery() {
      let result = [];
        if (this.product.gallery && this.product.gallery.length > 0) {

          if (this.product.image) {
            result.push({
              path: '/storage/app/media' +  this.product.image,
              title: this.product.title
            });
          }

          this.product.gallery.forEach(img => {
             result.push({
               path: img.path,
               title: img.title
             });
          });
        }
      return result;
    },
    swiper() {
      return this.$refs.addSlider.$swiper
    },
    productPercent() {
      let result = 0;
      if (+this.product.sale_price !== 0) {
        result = 100 - ((this.product.sale_price / this.product.price * 100).toFixed(0))
      }
      return "-" + result + "%";
    }
  },
  methods: {
    fetchProduct(id) {
      this.$store.dispatch("fetchProductById", id);
    },
    prevBanner() {
      this.swiper.slidePrev();
    },
    nextBanner() {
      this.swiper.slideNext();
    },
    changeImage(imgSrc) {
      this.currentImage = imgSrc;
    },
    changeAmount(val) {
      this.amount = val;
    },
    addToCart() {
      if( this.amount > 0 && this.product?.id) {
        this.$store.dispatch("addToCart", { id: this.product.id, amount: this.amount })
        this.amount = 1;
        this.productPopup = true;

        setTimeout(() => {
        this.isMoving = true
          setTimeout(() => {
            this.productPopup = false;
            this.isMoving = false
          }, 2500)
        }, 1000)
      }
    }
  },
  created() {
    this.id = this.$route.params.id;
    this.fetchProduct(this.id);
  },
  mounted() {
    const sticky = new Sticky('[data-js="sticky"]');
    sticky.update();

    if(window.innerWidth < 768) {
      this.sliderOptions.direction = 'horizontal';
    }

    window.addEventListener('resize', () => {
      window.innerWidth < 768 ? this.sliderOptions.direction = 'horizontal' : this.sliderOptions.direction = 'vertical';
    });
  },
  updated() {
    setTitle(this.product);
  }
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.product {
  padding: 50px 0;
  background: #fbf8ec;

  @media(max-width: 767px) {
    padding: 30px 0;
  }

  $root: &;

  &__heading {
    margin-bottom: 40px;

    @media(max-width: 767px) {
      margin-bottom: 20px;
    }
  }

  &__title {
    font-weight: bold;
    font-size: 40px;
    color: $primary;
    margin-bottom: 8px;

    @media(max-width: 1740px) {
      font-size: 35px;
    }

    @media(max-width: 767px) {
      font-size: 30px;
    }

    @media(max-width: 575px) {
      font-size: 25px;
      margin-bottom: 5px;
    }
  }

  &__row {
    position: relative;
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
  }

  &__body {
    width: 100%;
    max-width: calc(100% - 455px);
    margin-right: 50px;

    @media(max-width: 1740px) {
      max-width: calc(100% - 430px);
      margin-right: 25px;
    }

    @media(max-width: 1440px) {
      max-width: calc(100% - 455px);
      margin-right: 50px;
    }

    @media(max-width: 1199px) {
      max-width: calc(100% - 375px);
      margin-right: 25px;
    }

    @media(max-width: 991px) {
      max-width: 100%;
      margin-right: 0;
    }
  }

  &__slider {
    display: flex;
    flex-wrap: wrap;
    width: 100%;
    position: relative;

    @media(max-width: 767px) {
      overflow: hidden;
      flex-direction: column;
    }
  }

  &__tags {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    position: absolute;
    top: 30px;
    left: 20px;
    z-index: 1;
    @media(max-width: 767px) {
      top: 15px;
      left: 15px;
    }
  }

  &__tag {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #FFF;
    font-size: 13px;
    font-weight: 500;
    padding: 7px 15px;
    border-radius: 7px;
    text-align: center;
    margin-right: 9px;

    &--new {
      background: $green;
    }

    &--sale {
      background: $red;
    }

    &--hit {
      background: $blue;
    }

    &:last-child {
      margin-right: 0;
    }
  }

  &__slider-current {
    max-width: calc(100% - 160px);
    width: 100%;
    position: relative;
    overflow: hidden;
    border-radius: 8px;

    &::after {
      content: "";
      padding-top: 100%;
      display: block;
    }

    @media(max-width: 767px) {
      max-width: 100%;
    }
  }

  &__slider-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  &__slider-additional {
    padding: 40px 0;
    margin-left: 20px;
    width: 100%;
    max-width: 140px;
    max-height: 635px;
    position: relative;

    @media(max-width: 767px) {
      margin-left: 0;
      max-width: 100%;
      height: auto;
      overflow: hidden;
    }
  }

  &__slider-nav {
    width: 100%;
    height: 40px;
    background: $blue;
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    z-index: 3;
    left: 0;
    outline: none;

    &--prev {
      top: 0;
      border-radius: 7px 7px 0 0;

      #{$root} {
        &__slider-ico {
          transform: rotate(180deg);
        }
      }
    }

    &--next {
      bottom: 0;
      border-radius: 0 0 7px 7px;
    }

    @media(max-width: 767px) {
      display: none;
    }
  }

  &__slider-ico {
    width: 24px;
    height: 24px;
    color: #FFF;
  }

  &__swiper-container {
    width: 100%;
    height: 100%;
    display: flex;
  }

  &__swiper {
    height: auto !important;

    @media(max-width: 767px) {
      padding: 10px;
    }
  }

  &__slider-thumb {
    width: 100%;
    border-radius: 10px;
    overflow: hidden;
    position: relative;
    cursor: pointer;

    &::before {
      content: "";
      display: block;
      padding-top: 100%;
    }

    &::after {
      position: absolute;
      content: "";
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: $green;
      opacity: 0;
      pointer-events: none;
      z-index: 1;
    }

    &--active::after {
      opacity: .3;
    }
  }

  &__slider-thumb-image {
    position: absolute;
    left: 0;
    top: 0;
    max-width: none;
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: 50% 50%;
  }

  &__bottom {
    width: 100%;
    max-width: 100%;
    @media(max-width: 1440px) {
      order: 3;
    }
  }

  &__order {
    display: none;
    margin: 30px 0;

    @media(max-width: 991px) {
      display: block;
      background: #fff;
      padding: 30px 20px;
      border-radius: 15px;
      box-shadow: 0 10px 29px 0 $shadow;
    }
  }

  &__order-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  &__folders {
    margin-bottom: 30px;

    @media(max-width: 767px) {
      margin-bottom: 20px;
    }

    &:last-child {
      margin-bottom: 0;
    }
  }

  &__folders-title {
    color: $primary;
    font-weight: 400;
    font-size: 22px;
    margin-bottom: 20px;

    @media(max-width: 767px) {
      font-size: 18px;
      margin-bottom: 15px;
    }
  }

  &__folders-list {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
  }

  &__folders-link {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 500;
    line-height: 1.2;
    color: #fff;
    background: $blue;
    box-shadow: 7px 7px 30px $shadow;
    margin: 0 10px 10px 0;
    padding: 10px 20px 9px;
    border-radius: 10px;

    @media(max-width: 767px) {
      padding: 5px 10px;
      border-radius: 5px;
      margin: 0 5px 5px 0;
      font-size: 13px;
    }
  }

  &__form {
    width: 100%;
    max-width: 405px;
    padding: 50px 50px 50px;
    box-shadow: 7px 7px 30px $shadow;
    background: #FFF;
    width: 100%;
    border-radius: 20px;

    @media(max-width: 1199px) {
      max-width: 350px;
      padding: 40px 30px 30px;
    }

    @media(max-width: 991px) {
      display: none;
    }
  }

  &__form-title {
    color: $primary;
    font-weight: 700;
    font-size: 30px;
    margin-bottom: 20px;

    @media(max-width: 1199px) {
      font-size: 25px;
      margin-bottom: 15px;
    }
  }

  &__form-tag {
    background: rgba(41, 194, 158, 0.15);
    border-radius: 7px;
    font-weight: 500;
    font-size: 13px;
    color: #29c29e;
    padding: 7px 14px 7px 37px;
    display: inline-flex;
    position: relative;
    margin-bottom: 30px;
    @media(max-width: 1199px) {
      margin-bottom: 20px;
    }
  }

  &__form-tag-ico {
    color: #29C29E;
    width: 12px;
    height: 12px;
    position: absolute;
    left: 14px;
    top: calc(50% - 6px);
  }

  &__add {
    margin-top: 30px;
    width: 100%;
    display: inline-flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 500;
    font-size: 15px;
    line-height: 1.2;
    padding: 20px 30px;
    color: #fff;
    border: none;
    background: $blue;
    box-shadow: 7px 7px 30px $shadow;
    transition: all 0.18s linear;
    border-radius: 10px;

    &:hover, &:focus {
      background: darken($color: $blue, $amount: 10%);
    }

    @media(max-width: 991px) {
      max-width: 300px;
    }
  }

  &__add-ico {
    fill: #FFF;
    width: 21px;
    height: 21px;
  }

  &__popup-title {
    color: $primary;
    font-weight: 700;
    font-size: 25px;
    margin-bottom: 20px;

    @media(max-width: 1199px) {
      font-size: 20px;
      margin-bottom: 15px;
    }

    @media(max-width: 575px) {
      font-size: 16px;
    }

  }
}
</style>