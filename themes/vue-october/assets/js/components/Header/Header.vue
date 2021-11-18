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
            a(v-if="!isLogin" href="#!" @click.prevent="popup = true")._nav-link Войти/Зарегистрироваться
            router-link(v-else :to="{name: 'account' }")._nav-link Личный кабинет
          ._right
            ._person
              a(v-if="!isLogin" href="#!" @click.prevent="popup = true")._person-link
                icon(name="person" component="header")._person-ico
              router-link(v-else :to="{name: 'account' }")._person-link
                icon(name="person" component="header")._person-ico
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
          router-link(:to="{name: 'home' }")._logo-url
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
                a(:href="settings.instagram" v-if="settings.instagram && settings.instagram.length > 0" target="_blank")._messenger-link.-instagram
                  icon(name="instagram" component="footer")._messenger-ico
    HeaderNav(:categories="categories")
    MobileNav(:showMobileNav="showMobileNav" @closeNav="showMobileNav = false" :categories="categories" :navbar="navbar")
    Popup(v-if="popup" @close="closePopup")
      ._popup-tabs
        ._popup-tabs-heading(v-if="tabIndex === 2")
          ._popup-tabs-title Восстановление пароля
        ._popup-tabs-heading(v-else)
          ._popup-tabs-title(@click="tabIndex = 0" :class="{ 'header__popup-tabs-title_active': tabIndex === 0 }") Войти
          ._popup-tabs-title(@click="tabIndex = 1" :class="{ 'header__popup-tabs-title_active': tabIndex === 1 }") Зарегистрироваться
        ._popup-tabs-body
          Auth(v-if="tabIndex === 0" @close="closePopup" @forget="tabIndex = 2")
          Register(v-if="tabIndex === 1" @close="closePopup")
          ForgetPassword(v-if="tabIndex === 2" @close="closePopup" @remember="tabIndex = 0")
    Popup(v-if="popupLogout" @close="popupLogout = false")
      .success
        icon(name="checked" component="form")._ico
        ._message Вы успешно вышли <br>из личного кабинета


</template>
<script>
import MiniCart from './MiniCart';
import HeaderNav from './HeaderNav';
import MobileNav from './MobileNav';
import { formattedPhone } from '@vue/helpers/formatted.js';
import Popup from '@vue/components/Popup/Popup';
import Auth from '@vue/components/User/Auth';
import Register from '@vue/components/User/Register';
import ForgetPassword from '@vue/components/User/ForgetPassword';

export default {
  name: "Header",
  components: {
    MiniCart,
    HeaderNav,
    MobileNav,
    Popup,
    Auth,
    Register,
    ForgetPassword
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
    },
    isLogin() {
      return this.$store.getters.getUser;
    }
  },
  data() {
    return {
      showMiniCart: false,
      showMobileNav: false,
      popup: false,
      tabIndex: 0,
      popupLogout: false
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
    },
    closePopup() {
      this.popup = false;
    },
    failAuth() {
      if (this.$route.query.auth === "false") {
        this.popup = true;
        this.$router.replace({
          query: {}
        });
      }
    },
    logoutAuth() {
      if (this.$route.query.auth === "logout") {
        this.popupLogout = true;
        this.$router.replace({
          query: {}
        });
      }
    }
  },
  created() {
    this.fetchProductsByIds();
  },
  mounted() {
    if (this.$route.query) {
      this.failAuth();
    }
  },
  updated() {
    if (this.$route.query) {
      this.logoutAuth();
    }
  }
}
</script>