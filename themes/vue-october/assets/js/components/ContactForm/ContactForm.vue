<template lang="pug">
  form.contact-form(
      :class="{ 'contact-form--footer': className === 'footer' }"
      @submit.prevent="onSubmit")
    ._form-group
      input(type="text" v-model="form.user_name" placeholder="Ваше имя*")._form-input
      span._error( v-if="nameErr") {{ nameErr }}
    ._form-group
      input(type="hidden" v-model="form.user_subject")._form-input
      input(type="tel" v-model="form.user_phone" placeholder="+7 (999) 999-99-99*" v-mask="'+7 (###) ###-##-##'")._form-input
      span._error( v-if="phoneErr") {{ phoneErr }}
    ._form-group(v-if="showUserMail")
      input(type="text" v-model="form.user_mail" placeholder="Ваш E-mail")._form-input
      span._error( v-if="mailErr") {{ mailErr }}
    ._form-group(v-if="userSubject === 'Заявка: Рассчитать стоимость заказа'")
      textarea(type="text" v-model="form.user_message" placeholder="Напишите что хотите рассчитать")._form-input.-textarea
      span._error( v-if="messageErr") {{ messageErr }}
    button(type="submit" :class="{'contact-form__btn--offset': className === 'footer' && (nameErr || phoneErr)}")._btn Отправить
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
  name: "ContactForm",
  components: {
    Popup
  },
  props: {
    className: {
      type: String,
      default: ""
    },
    userSubject: {
      type: String,
      default: "Заявка из контакной формы"
    },
    showUserMail: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      form: {
        user_name: "",
        user_phone: "",
        user_subject: this.userSubject,
        user_mail: "",
        user_message: ""
      },
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
    mailErr() {
      return checkErr('user_mail', this.errors);
    },
    messageErr() {
      return checkErr('user_message', this.errors);
    },
    submitMsg () {
      return this.submitStatus;
    }
  },
  methods: {
    onSubmit() {
      this.setSubmitStatus(null);
      this.errors = [];

      this.setSubmitStatus("Отправка формы...");

      axios.post('/api/send-message', this.form)
        .then(response => {
          let message = ''
          const data = response.data;
          if(data.status === 'error') {
            this.onValidate(data)
            this.setSubmitStatus(null);

          } else if(data.status === 'success') {
            message = data.message;
            this.resetForm();
            this.popup = true;
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
    }
  }
}
</script>