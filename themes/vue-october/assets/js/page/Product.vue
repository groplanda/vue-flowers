<template lang="pug">
  section.product.section
    Popup(v-if="popup" @close="popup = false")
      ._popup-title Заказать {{ product.title }}
      ContactForm

    transition(name="fade")
      ProductPopup(v-if="productPopup")
    ._container.container
      ._heading
        h1._title {{ product.title }}
      ._row(data-sticky-container)
        ._body
          ._slider
            ._tags
              ._tag.-new(v-if="+product.is_new") New
              ._tag.-sale(v-if="+product.sale_price") Sale
            ._slider-current
              img(:src="currentImage ? currentImage : noImage" :zoomSrc="currentImage ? currentImage : noImage")._slider-image
            Swiper(ref="addSlider" :options="sliderOptions" v-if="product.gallery && product.gallery.length")._slider-additional
              SwiperSlide._swiper
                ._slider-thumb(
                  :class="{'product__slider-thumb--active': currentImage === '/storage/app/media' + product.image}"
                  @click="changeImage('/storage/app/media' + product.image)")
                  img(:src="'/storage/app/media' + product.image", :alt="product.title")._slider-thumb-image
              SwiperSlide._swiper(v-for="image in product.gallery" :key="image.file_name")
                ._slider-thumb(
                  :class="{'product__slider-thumb--active': currentImage === image.path}"
                  @click="changeImage(image.path)")
                  img(:src="image.path", :alt="image.title")._slider-thumb-image

          ._content
            ._categories-list
              router-link(
                :to="{ name: 'category', params: { slug: category.slug }}"
                v-for="category in product.categories"
                :key="category.slug")._categories-link {{ category.title }}
            ._code(v-if="product.code") Артикул {{ product.code }}

            ._props.-full(v-if="product.props && product.props.length")
              ._props-row(v-for="(prop, index) in product.props" :key="index")
                ._props-label {{ prop.props_option }}
                ._props-val  {{ prop.props_value }}

            ._entry.-desktop(v-if="product.description")
              ._entry-title Описание
              ._entry-descr(v-html="product.description")

          ._bottom
            ._order
              ._order-row
                ProductPrice(v-if="product.price" :price="+product.price" :salePrice="+product.sale_price")
                ProductAmount(@changeAmount="changeAmount" @amount="amount")
              button(type="button" @click="addToCart")._add
                | В корзину
                icon(name="cart" component="header")._add-ico

            ._props.-mobile(v-if="product.props && product.props.length")
              ._props-row(v-for="(prop, index) in product.props" :key="index")
                ._props-label {{ prop.props_option }}
                ._props-val  {{ prop.props_value }}

            ._entry.-mobile(v-if="product.description")
              ._entry-title Описание
              ._entry-descr(v-html="product.description")
            ._folders
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
          template(v-if="product.price")
            ProductPrice(:price="+product.price" :salePrice="+product.sale_price")
            ProductAmount(@changeAmount="changeAmount" :amount="amount")
            button(type="button" @click="addToCart")._add
              | В корзину
              icon(name="shopping-cart" component="product")._add-ico
          button(type="button" v-else @click="popup = true")._add
            | Сделать заказ
            icon(name="plus" component="product")._add-ico

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
    ContactForm
  },
  data() {
    return {
      popup: false,
      currentImage: "",
      amount: 1,
      productPopup: false,
      noImage: "/themes/vue-october/assets/images/no-image.jpg",
      sliderOptions: {
        slidesPerView: 3,
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
    swiper() {
      return this.$refs.addSlider.$swiper
    }
  },
  methods: {
    fetchProduct(id) {
      this.$store.dispatch("fetchProductById", id);
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
          this.productPopup = false;
        }, 2000)
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
    margin-left: 20px;
    width: 100%;
    max-width: 140px;
  }


  &__swiper {
    height: auto !important;
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

  &__categories-list {
    display: flex;
    flex-wrap: wrap;
  }

  &__categories-link {
    font-weight: normal;
    font-size: 15px;
    line-height: 18px;
    color: $primary;
    opacity: 0.5;
    margin-right: 10px;

    &:last-child {
      margin-right: 0;
    }

    @media(max-width: 767px) {
      font-size: 14px;
      margin-right: 5px;
    }
  }

  &__code {
    font-weight: 400;
    font-size: 13px;
    color: $primary;
    margin: 20px 0;

    @media(max-width: 767px) {
      margin: 15px 0;
    }
  }

  &__props {

    &--full {
      @media(max-width: 1440px) {
        display: none;
      }
    }

    &--mobile {
      display: none;
      @media(max-width: 1440px) {
        margin-top: 30px;
        display: block;
      }
    }
  }

  &__props-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    margin-bottom: 30px;
    @media(max-width: 767px) {
      margin-bottom: 20px;
    }
  }

  &__props-label {
    font-size: 15px;
    color: $primary;
    opacity: 0.6;
    padding-right: 25px;
    min-width: 100px;
  }

  &__props-val {
    font-weight: 500;
    font-size: 15px;
    color: $primary;
  }

  &__entry {
    width: 100%;
    max-width: 100%;

    &--desktop {
      @media(max-width: 1740px) {
        display: none;
      }
    }

    &--mobile {
      display: none;
      @media(max-width: 1740px) {
        display: block;
      }
    }
  }

  &__entry-title {
    color: $primary;
    font-weight: 600;
    font-size: 20px;
    margin-bottom: 30px;

    @media(max-width: 767px) {
      font-size: 18px;
      margin-bottom: 20px;
    }
  }

  &__entry-descr , &__entry-descr p {
    font-weight: normal;
    font-size: 16px;
    color: $primary;
    line-height: 1.7;

    p {
      margin-bottom: 15px;
    }

    @media(max-width: 767px) {
      font-size: 14px;
    }
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
    background: $primary;
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