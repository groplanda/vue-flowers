<template lang="pug">
  header.header
    ._top
      ._container.container
        ._top-row
          button(type="button" @click="showMobileNav = true")._menu
            icon(name="menu" component="header")._menu-ico
            ._menu-text меню
          ._nav
            router-link(:to="link.url" v-for="(link, idx) in navbar" :key="idx" :class="link.class")._nav-link {{ link.title }}
          ._cart
            ._cart-heading(@click="showMiniCart = !showMiniCart")
              ._cart-val
                icon(name="cart" component="header")._cart-ico
                ._cart-count {{ cart }}
              ._cart-sum {{ cart ? totalPrice + ' ₽' : 'Корзина пуста!' }}
            MiniCart(
              v-if="showMiniCart"
              @deleteProduct="deleteProduct"
              @closeMiniCart="showMiniCart = false"
              :products="products"
              :cart="cart"
              )
    ._center
      ._container.container
        ._center-row
          router-link(:to="{name: 'home' }")
            img(:src="'/storage/app/media' + settings.siteLogo" :alt="settings.siteName")._logo
          ._content
            ._link-list
              router-link(:to="{ name: 'stocks' }")._link-item.-gift
                icon(name="gift" component="header")._link-ico
                span Акции и скидки
              router-link(to="/post/delivery")._link-item.-delivery
                icon(name="pay" component="header")._link-ico
                span Оплата и доставка
            ._contacts
              icon(name="phone" component="header")._contacts-ico
              ._contact-city(v-if="settings.address") {{ settings.address }}
              a(:href="'tel:' + preparePhone(phone.val)" v-for="(phone, index) in settings.phone" :key="index")._contact-phone {{ phone.val }}
              ._messenger(v-if="settings.whatsapp || settings.viber")
                a(:href="'whatsapp://send?phone=+' + settings.whatsapp" v-if="settings.whatsapp && settings.whatsapp.length > 0")._messenger-link.-wa
                  icon(name="whatsapp" component="footer")._messenger-ico
                a(:href="'viber://add?number=' + settings.viber" v-if="settings.viber && settings.viber.length > 0")._messenger-link.-viber
                  icon(name="viber" component="footer")._messenger-ico
    HeaderNav(:categories="categories")
    MobileNav(:showMobileNav="showMobileNav" @closeNav="showMobileNav = false" :categories="categories" :navbar="navbar")

</template>
<script>
import MiniCart from './MiniCart';
import HeaderNav from './HeaderNav';
import MobileNav from './MobileNav';
import { formattedPhone } from '@vue/helpers/formatted.js';

export default {
  name: "Header",
  components: {
    MiniCart,
    HeaderNav,
    MobileNav
  },
  props: {
    settings: {
      type: Object,
      default() {
        return {}
      }
    },
    navbar: {
      type: Array,
      default() {
        return []
      }
    }
  },
  watch: {
    cart() {
      this.fetchProductsByIds();
    },
    $route() {
      this.showMiniCart = false;
      this.showMobileNav = false;
    }
  },
  computed: {
    categories() {
      return this.$store.getters.getCategories;
    },
    products() {
      return this.$store.getters.getCartProducts;
    },
    cart() {
      const cart = this.$store.getters.getCart;
      if (cart.length > 0) {
        return cart.reduce((sum, el) => sum + el.amount, 0);
      }
      return 0;
    },
    totalPrice() {
      let result = 0;
      this.products.forEach(product => {
        if (+product.sale_price !== 0) {
          result += +product.sale_price * +product.amount;
        } else {
          result += +product.price * +product.amount;
        }
      });
      return result.toLocaleString('ru');
    }
  },
  data() {
    return {
      showMiniCart: false,
      showMobileNav: false,
    }
  },
  methods: {
    deleteProduct(id) {
      const productIndex = this.products.findIndex(el => el.id === id);
      if (productIndex !== -1) {
        this.products.splice(productIndex, 1);
      }
    },
    fetchProductsByIds() {
      const cart = this.$store.getters.getCart;
      if (cart.length > 0) {
        this.$store.dispatch("fetchProductsByIds", cart);
      }
    },
    preparePhone(phone) {
      return formattedPhone(phone);
    }
  },
  created() {
    this.fetchProductsByIds();
  }
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.header {

  $root: &;

  &__top {
    width: 100%;
    background: #fbf8ec;
    display: flex;
    justify-content: center;
  }

  &__top-row {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
  }

  &__menu {
    display: none;
    @media(max-width: 767px) {
      display: flex;
      align-items: center;

    }
  }

  &__menu-ico {
    width: 18px;
    height: 12px;
    color: $blue;
  }

  &__menu-text {
    color: $blue;
    font-size: 14px;
    font-weight: 500;
    line-height: 14px;
    margin: 1px 0 0 10px;
  }

  &__nav {
    width: 100%;
    max-width: 610px;
    padding: 17px 0;
    display: flex;
    align-items: center;
    flex-wrap: wrap;

    @media(max-width: 991px) {
      max-width: calc(100% - 180px);
    }

    @media(max-width: 767px) {
      display: none;
    }
  }

  &__nav-link {
    font-size: 15px;
    text-decoration: none;
    display: block;
    color: $primary;
    font-weight: 300;
    margin-right: 13px;

    @media(max-width: 991px) {
      font-size: 14px;
      margin-right: 10px;
    }

    &--tablet-link {
      display: none;
      @media(max-width: 991px) {
        display: block;
      }
    }

    &:hover {
      color: $blue;
    }

    &:last-child {
      margin: 0;
    }
  }

  &__cart {
    width: 100%;
    max-width: 180px;
    position: relative;

    @media(max-width: 767px) {
      max-width: 40px;
    }
  }

  &__cart-heading {
    position: relative;
    display: flex;
    align-items: center;
    padding: 15px 0 15px 50px;
    cursor: pointer;
    @media(max-width: 767px) {
      padding: 0;
      width: 40px;
      height: 45px;
    }
  }

  &__cart-val {
    position: absolute;
    left: 5px;
    top: calc(50% - 13px);

    @media(max-width: 767px) {
      left: 0;
    }
  }

  &__cart-ico {
    width: 26px;
    height: 26px;
    fill: $blue;

    @media(max-width: 767px) {
      width: 20px;
      height: 20px;
    }
  }

  &__cart-count {
    color: #fff;
    font-size: 10px;
    font-weight: 300;
    line-height: 1;
    border-radius: 50%;
    background: $red;
    width: 18px;
    height: 18px;
    text-align: center;
    position: absolute;
    left: 15px;
    top: -2px;
    display: inline-flex;
    align-items: center;
    justify-content: center;

    @media(max-width: 767px) {
      left: 10px;
    }
  }

  &__cart-sum {
    color: $primary;
    font-size: 18px;
    line-height: 1;
    font-weight: 600;
    cursor: pointer;
    @media(max-width: 991px) {
      font-size: 16px;
    }
    @media(max-width: 767px) {
      display: none;
    }
  }

  &__center {
    padding: 10px 0 15px;
    display: flex;
    justify-content: center;
  }

  &__center-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
  }

  &__logo {
    display: flex;
    width: 100%;
    max-width: 300px;
    margin-right: 20px;

    @media(max-width: 1199px) {
      max-width: 200px;
    }

    @media(max-width: 575px) {
      margin: 0 auto;
    }
  }

  &__content {
    width: 100%;
    max-width: 790px;
    display: flex;
    justify-content: space-between;
    align-items: center;

    @media(max-width: 1199px) {
      max-width: 690px;
    }

    @media(max-width: 991px) {
      max-width: calc(100% - 220px);
    }

    @media(max-width: 575px) {
       max-width: 100%;
    }
  }

  &__link-list {
    display: flex;
    flex-wrap: wrap;

    @media(max-width: 991px) {
      display: none;
    }
  }

  &__link-item {
    display: flex;
    align-items: center;
    padding: 13px 25px;
    font-size: 16px;
    font-weight: 600;
    line-height: 1.2;

    &--gift {
      background: #fff6f7;
      color: $red;

      #{$root} {
        &__link-ico {
          fill: $red;
        }
      }
    }

    &--delivery {
      background: #fff6f1;
      color: $brown;
      margin-left: 10px;

      #{$root} {
        &__link-ico {
          fill: $brown;
        }
      }
    }

    @media(max-width: 1199px) {
      font-size: 15px;
    }
  }

  &__link-ico {
    width: 20px;
    height: 20px;
    margin-right: 10px;

    @media(max-width: 1199px) {
      margin-right: 5px;
    }
  }

  &__contacts {
    padding: 0 0 0 35px;
    width: 100%;
    max-width: 240px;
    margin: 6px 0 0 10px;
    position: relative;
    @media(max-width: 991px) {
      margin-left: auto;
      padding-left: 30px;
    }
    @media(max-width: 575px) {
      margin: 15px 0 0;
      padding: 0;
      text-align: center;
      max-width: 100%;
    }
  }

  &__contacts-ico {
    fill: $secondary;
    width: 20px;
    height: 20px;
    position: absolute;
    top: 2px;
    left: 0;
    @media(max-width: 991px) {
      width: 18px;
      height: 18px;
    }
    @media(max-width: 575px) {
      display: none;
    }
  }

  &__contact-city {
    color: $primary;
    font-size: 15px;
    font-weight: 300;
    margin-bottom: 5px;
    @media(max-width: 991px) {
      font-size: 14px;
    }
  }

  &__contact-phone {
    display: block;
    color: $primary;
    font-size: 20px;
    font-weight: 700;
    @media(max-width: 991px) {
      font-size: 18px;
    }
  }

  &__messenger {
    margin-top: 5px;
    display: flex;
    flex-wrap: wrap;
    @media(max-width: 767px) {
      justify-content: center;
    }
  }

  &__messenger-link {
    width: 34px;
    height: 34px;
    margin-right: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 17px;

    &--wa {
      background: #25D366;
    }

    &--viber {
      background: #665CAC;
    }

    &:last-child {
      margin-right: 0;
    }
  }

  &__messenger-ico {
    width: 16px;
    height: 16px;
    fill: #fff;
    color: #FFF;
    margin: 0;
  }
}
</style>