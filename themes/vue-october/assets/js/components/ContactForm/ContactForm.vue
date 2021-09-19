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
<style lang="scss">
@import '@/scss/vars.scss';
.contact-form {
  display: flex;
  flex-wrap: wrap;
  $root: &;

  &--footer {

    @media(max-width: 991px) {
      justify-content: center;
    }

    #{$root} {
      &__form-group {
        width: 100%;
        max-width: 33.333%;
        padding: 0 15px;
        margin-bottom: 0;

        @media(max-width: 991px) {
          max-width: 50%;
        }

        @media(max-width: 767px) {
          max-width: 100%;
          padding: 0;
          padding-bottom: 20px;
        }
      }
      &__btn {
        width: 100%;
        max-width: calc(33.333% - 30px);
        margin: 0 15px;

        @media(max-width: 991px) {
          margin: 20px 0 0;
          max-width: calc(100% - 30px);
        }

        @media(max-width: 767px) {
          margin: 0;
          max-width: 100%;
        }
      }
    }
  }

  &__form-group {
    width: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
    margin-bottom: 20px;
  }

  &__form-status-title {
    font-size: 14px;
    line-height: 19px;
    margin-bottom: 10px;

    @media(max-width: 767px) {
      font-size: 12px;
    }
  }

  &__form-status-list {
    display: flex;
    flex-wrap: wrap;
  }

  &__form-status {
    cursor: pointer;
    width: 24px;
    height: 24px;
    margin-right: 6px;
    position: relative;

    @media(max-width: 767px) {
      width: 21px;
      height: 21px;
      margin-right: 5px;
    }

    &:last-child {
      margin-right: 0;
    }
  }

  &__form-status-input {
    position: absolute;
    opacity: 0;
    z-index: -1;

    &:checked {
      #{$root} {
        &__form-status-ico {
          fill: $yellow;
        }
      }
    }
  }

  &__form-status-ico {
    fill: $secondary;
    width: 100%;
    height: 100%;

    &_active {
      fill: $yellow;
    }
  }

  &__error {
    color: $red;
    font-size: 12px;
    line-height: 21px;
    margin-top: 3px;
    display: block;

    @media (min-width: 768px) and (max-width: 992px) {
      font-size: 10px;
    }
  }

  &__form-input {
    font-size: 16px;
    color: $primary;
    border: 2px solid $borderColor;
    background: transparent;
    box-shadow: none;
    border-radius: 6px;
    padding: 0 20px;
    width: 100%;
    height: 51px;
    outline: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    transition: all 0.18s linear;

    &:hover, &:focus {
      color: $primary;
      border-color: transparent;
      background: $borderColor;
      box-shadow: none;
    }

    @media(max-width: 767px) {
      padding: 0 10px;
      font-size: 14px;
    }

    &--textarea {
      padding: 20px;
      height: 100px;

      @media(max-width: 767px) {
        padding: 10px;
        height: 75px;
      }
    }
  }

  &__btn {
    font-weight: 600;
    font-size: 15px;
    padding: 0 20px;
    height: 51px;
    transition: background 0.3s, color 0.3s;
    color: #fff;
    border: none;
    background: $yellow;
    border-radius: 6px;

    @media(max-width: 767px) {
      font-size: 14px;
      padding: 12px 20px;
    }

    &--offset {
      @media (min-width: 768px) and (max-width: 1199px) {
        margin-bottom: 23px !important;
      }
    }

    &:hover, &:focus {
      background: darken($color: $yellow, $amount: 10%);
    }
  }

  &__status {
    max-width: 100%;
    width: 100%;
    text-align: center;
    font-size: 14px;
    font-weight: 500;
    color: $red;
    margin-top: 10px;

    @media(max-width: 767px) {
      margin-top: 5px;
      font-size: 12px;
    }
  }
}
.success {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 60px 0;

  @media(max-width: 767px) {
    padding: 40px 0;
  }

  &__ico {
    width: 105px;
    height: 105px;
    margin-bottom: 20px;

    @media(max-width: 767px) {
      width: 90px;
      height: 90px;
    }
  }

  &__message {
    text-align: center;
    font-size: 20px;
    font-weight: 600;
    color: $primary;
    @media(max-width: 767px) {
      font-size: 18px;
    }
  }

}
</style>