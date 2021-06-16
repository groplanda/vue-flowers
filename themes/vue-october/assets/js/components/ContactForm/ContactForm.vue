<template lang="pug">
  form.contact-form(
      :class="{ 'contact-form--footer': className === 'footer' }"
      @submit.prevent="onSubmit")
    ._form-group
      input(type="text" v-model="form.user_name" placeholder="Ваше имя*")._form-input
      span._error( v-if="nameErr") {{ nameErr }}
    ._form-group
      input(type="tel" v-model="form.user_phone" placeholder="+7 (999) 999-99-99" v-mask="'+7 (###) ###-##-##'")._form-input
      span._error( v-if="phoneErr") {{ phoneErr }}
    button(type="submit" :class="{'contact-form__btn--offset': className === 'footer' && (nameErr || phoneErr)}")._btn Отправить
    ._status {{ submitStatus }}
</template>
<script>
import { onValidate, checkErr } from '@vue/helpers/validate.js';
import axios from "axios";

export default {
  name: "ContactForm",
  props: {
    className: {
      type: String,
      default: ""
    }
  },
  data() {
    return {
      form: {
        user_name: "",
        user_phone: "",
      },
      submitStatus: null,
      errors: []
    }
  },
  computed: {
    nameErr() {
      return checkErr('user_name', this.errors);
    },

    phoneErr() {
      return checkErr('user_phone', this.errors);
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
    position: relative;
    margin-bottom: 20px;
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
    color: $primary;
    margin-top: 10px;

    @media(max-width: 767px) {
      margin-top: 5px;
      font-size: 12px;
    }
  }

}
</style>