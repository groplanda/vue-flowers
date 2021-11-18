<template lang="pug">
  form.checkout-form(@submit.prevent="onCheckout")
    ._inner
      ._row
        ._group
          input(
            type="text"
            v-model="form.user_name"
            placeholder="ФИО*"
            :class="{ 'checkout-form__input--error': nameErr }")._input
          span._error( v-if="nameErr") {{ nameErr }}
        ._group
          input(
            type="tel"
            v-model="form.user_phone"
            placeholder="Телефон*"
            v-mask="'+7 (###) ###-##-##'"
            :class="{ 'checkout-form__input--error': phoneErr }")._input
          span._error( v-if="phoneErr") {{ phoneErr }}
        ._group
          input(
            type="text"
            v-model="form.user_email"
            placeholder="E-mail"
            :class="{ 'checkout-form__input--error': emailErr }")._input
          span._error( v-if="emailErr") {{ emailErr }}
        ._group
          input(
            type="text"
            v-model="form.user_address"
            placeholder="Адрес"
            :class="{ 'checkout-form__input--error': addressErr }")._input
          span._error( v-if="addressErr") {{ addressErr }}
        ._group.-full
          textarea(
            v-model="form.user_comment"
            placeholder="Комментарий"
            :class="{ 'checkout-form__input--error': commentErr }")._input.-textarea
          span._error( v-if="commentErr") {{ commentErr }}
      button._btn(type="submit" :disabled="this.products.length == 0") Заказать
      ._status {{ submitStatus }}

    Popup(v-if="popup" @close="closePopup")
      .success
        icon(name="checked" component="form")._ico
        ._message {{ submitStatus }}

</template>
<script>
import { onValidate, checkErr } from '@vue/helpers/validate.js';
import Popup from '@vue/components/Popup/Popup';
import axios from "axios";
export default {
  name: "CheckoutForm",
  components: {
    Popup
  },
  props: {
    products: {
      type: Array,
      required: true
    },
    total: {
      type: Number,
      requred: true
    }
  },
  data() {
    return {
      form: {
        user_id: null,
        user_name: "",
        user_phone: "",
        user_email: "",
        user_address: "",
        user_comment: ""
      },
      cloneProducts: [],
      submitStatus: null,
      errors: [],
      popup: false
    }
  },
  computed: {
    nameErr() {
      return checkErr('user_name', this.errors);
    },

    phoneErr() {
      return checkErr('user_phone', this.errors);
    },

    addressErr() {
      return checkErr('user_address', this.errors);
    },

    emailErr() {
      return checkErr('user_email', this.errors);
    },

    commentErr() {
      return checkErr('user_comment', this.errors);
    },

    submitMsg () {
      return this.submitStatus;
    },

    isLogin() {
      return this.$store.getters.getUser;
    },

    userData() {
      return this.$store.getters.getUserData;
    }

  },
  watch: {
    userData() {
      const user = this.userData;
      if (user) {
        this.fillUserData(user);
      }
    }
  },
  methods: {
    onCheckout() {
      this.setSubmitStatus(null);
      this.errors = [];

      this.setSubmitStatus("Заказ отправляется...");
      this.form.products = this.products;
      this.form.total = this.total;

      axios.post('api/add-order', this.form)
        .then(response => {
          let message = ''
          const data = response.data;
          if(data.status === 'error') {
            this.onValidate(data)
            this.setSubmitStatus(null);

          } else if(data.status === 'success') {
            message = data.message;
            this.popup = true;
            this.resetForm();

            if (localStorage.getItem('cart')) {
              localStorage.removeItem('cart');
              this.$store.dispatch("fillCart" , []);
              this.$emit("hideTotal");
            }
          }

          this.setSubmitStatus(message);

        })
        .catch(error => {
          console.log(error);
        })
    },

    resetForm() {
      this.errors = [];
      this.setSubmitStatus(null);
      Object.keys(this.form).forEach(key => this.form[key] = '');
    },

    onValidate(response) {
      this.errors = onValidate(response, this.form);
    },

    setSubmitStatus(status) {
      this.submitStatus = status;
    },

    closePopup() {
      this.popup = false;
      this.setSubmitStatus(null);
    },

    fillUserData(userData) {
      const USER_KEYS = ['id', 'name', 'email', 'phone', 'address'];
      USER_KEYS.forEach(key => {
        if (userData[key]) {
          this.form[`user_${key}`] = userData[key];
        }
      })
    },
  },
  created() {
    if (this.isLogin) {
      this.$store.dispatch("fetchUserdata");
    }
  }
}
</script>