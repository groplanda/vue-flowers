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
              template(v-if="+product.price !== 0")
                ._order-row
                  ProductPrice(:price="+product.price" :salePrice="+product.sale_price")
                  ProductAmount(@changeAmount="changeAmount" @amount="amount")
                button(type="button" @click="addToCart")._add
                  | В корзину
                  icon(name="cart" component="header")._add-ico
              button(type="button" v-else @click="popup = true")._add.-form
                | Сделать заказ
                icon(name="shopping-cart" component="product")._add-ico

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
