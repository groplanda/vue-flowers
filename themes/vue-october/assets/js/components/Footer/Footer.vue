<template lang="pug">
  footer.footer.section
    ._container.container
      ._top
        ._nav
          router-link(:to="link.url" v-for="(link, index) in footerLinks" :key="index")._nav-link {{ link.title }}

        ._right
          ._contacts
            icon(name="phone" component="header")._contacts-ico
            ._contact-city(v-if="settings.address") {{ settings.address }}
            a(:href="'tel:' + preparePhone(phone.val)" v-for="(phone, index) in settings.phone" :key="index")._contact-phone {{ phone.val }}
          ._social
            ._social-title Присоединяйтесь!
            ._social-list
              a(:href="settings.vk" v-if="settings.vk && settings.vk.length > 0" target="_blank")._social-link.-vk
                icon(name="vk" component="footer")._social-ico
              a(:href="settings.instagram" v-if="settings.instagram && settings.instagram.length > 0" target="_blank")._social-link.-instagram
                icon(name="instagram" component="footer")._social-ico
              a(:href="settings.youtube" v-if="settings.youtube && settings.youtube.length > 0" target="_blank")._social-link.-youtube
                icon(name="youtube" component="footer")._social-ico
              a(:href="'whatsapp://send?phone=+' + settings.whatsapp" v-if="settings.whatsapp && settings.whatsapp.length > 0")._social-link.-wa
                icon(name="whatsapp" component="footer")._social-ico
              a(:href="'viber://add?number=' + settings.viber" v-if="settings.viber && settings.viber.length > 0")._social-link.-viber
                icon(name="viber" component="footer")._social-ico
      ._bottom
        ContactForm(className="footer")
    ._copyright
      ._container.container
          ._copyright-text © 2018 - 2021 “{{ settings.siteName }}”
</template>
<script>
import ContactForm from '@vue/components/ContactForm/ContactForm';
import { formattedPhone } from '@vue/helpers/formatted.js';

export default {
  name: "Footer",
  components: {
    ContactForm
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
  data() {
    return {
      footerLinks: [
        { title: 'О компании', url: '/' },
        { title: 'Помощь', url: '/' },
        { title: 'Контакты', url: '/' },
        { title: 'Акции и скидки', url: '/' },
        { title: 'Оплата и доставка', url: '/' }
      ]
    }
  },
  methods: {
    preparePhone(phone) {
      return formattedPhone(phone);
    },
  }
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.footer {
  padding-top: 40px;

  @media(max-width: 991px) {
    padding-top: 30px;
  }

  &__top {
    padding-bottom: 30px;
    display: flex;
    flex-wrap: wrap;
  }

  &__nav {
    width: 100%;
    max-width: 50%;
    display: flex;
    flex-wrap: wrap;
    @media(max-width: 991px) {
      max-width: 100%;
      margin-bottom: 30px;
    }

    @media(max-width: 767px) {
      margin-bottom: 15px;
    }
  }

  &__nav-link {
    color: $secondary;
    font-size: 15px;
    font-weight: 300;
    width: 100%;
    max-width: 50%;
    padding-right: 15px;
    display: flex;
    margin-bottom: 10px;
    @media(max-width: 991px) {
      width: auto;
      max-width: none;
    }

    &:hover {
      color: $blue;
    }
  }

  &__right {
    width: 100%;
    max-width: 50%;
    display: flex;
    flex-wrap: wrap;
    @media(max-width: 991px) {
      max-width: 100%;
    }
  }

  &__contacts {
    padding: 0 20px 0 35px;
    width: 100%;
    max-width: 310px;
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

  &__social {
    width: 100%;
    max-width: calc(100% - 310px);

    @media(max-width: 575px) {
      max-width: 100%;
      margin-top: 15px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
  }

  &__social-title {
    color: #898989;
    font-size: 15px;
    font-weight: 300;
    margin-bottom: 8px;
  }

  &__social-list {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
  }

  &__social-link {
    width: 35px;
    height: 35px;
    margin-right: 8px;
    margin-bottom: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;

    &--vk {
      background: #2a5ab0;
    }

    &--instagram {
      background: #b55928;
    }

    &--youtube {
      background: #ff0000;
    }

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

  &__social-ico {
    width: 20px;
    height: 20px;
    fill: #fff;
    color: #FFF;
    margin: 0;
  }

  &__bottom {
    border-top: 1px solid $borderColor;
    padding: 40px 0 30px;
  }

  &__copyright {
    background: #fbf8ec;
    padding: 30px 0 40px;
    width: 100%;
    display: flex;
    justify-content: center;
    @media(max-width: 991px) {
      padding: 20px 0 30px;
    }
  }

  &__copyright-text {
    color: $secondary;
    font-size: 14px;
    font-weight: 300;
    text-align: center;
  }
}
</style>