<template lang="pug">
  footer.footer.section
    ._container.container
      ._top
        ._nav(v-if="navbar")
          router-link(:to="nav.url" v-for="(nav, index) in navbar" :key="index")._nav-link {{ nav.title }}

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
  methods: {
    preparePhone(phone) {
      return formattedPhone(phone);
    },
  }
}
</script>