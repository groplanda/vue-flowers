<template lang="pug">
  form.contact-form(@submit.prevent="onAuth")
    ._form-group
      input(type="text" v-model="form.name" placeholder="Ваше имя*")._form-input
      span._error.-user( v-if="nameErr") {{ nameErr }}
    ._form-group
      input(type="text" v-model="form.email" placeholder="Ваш email*")._form-input
      span._error.-user( v-if="emailErr") {{ emailErr }}
    ._form-group
      input(type="password" v-model="form.password" placeholder="Ваш пароль*")._form-input
      span._error.-user( v-if="passwordErr") {{ passwordErr }}
    ._form-group
      input(type="password" v-model="form.password_confirmation" placeholder="Повторите пароль*")._form-input
      span._error.-user( v-if="passwordConfirmationErr") {{ passwordConfirmationErr }}
    ._form-group
      label._form-checkbox
        input(type="checkbox" v-model="form.is_subscribe")._form-checkbox-input
        span._form-checkbox-elem
        span._form-checkbox-text Подписаться на рассылку

    button(type="submit")._btn.-full Зарегистрироваться
    ._status {{ submitStatus }}

</template>
<script>
import { onValidate, checkErr } from '@vue/helpers/validate.js';
import axios from "axios";

export default {
  name: "Register",
  data() {
    return {
      form: {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        is_subscribe: 0

      },
      submitStatus: null,
      errors: [],
    }
  },
  methods: {
    onAuth() {
      this.setSubmitStatus(null);
      this.errors = [];

      axios.post('/api/user/register', this.form)
        .then(response => {
          const data = response.data;
          if (data.status === 'error') {
            this.onValidate(data)
            this.setSubmitStatus(null);
            return;
          }

          if (data.status === 'success') {
            axios.get('/api/user/isUserLogin')
            .then(response => {
              if (response.data) {
                this.$store.commit("SET_USER", response.data);
                this.$router.push({ name: 'account' }).catch(() => {});
                this.$emit('close');
              }
            })
          }
        })
        .catch(error => {
          console.log(error);
        })

    },
    onValidate(response) {
      this.errors = onValidate(response, this.form);
    },
    resetForm() {
      this.errors = [];
      this.setSubmitStatus(null);
      Object.keys(this.form).forEach(key => this.form[key] = '');
    },
    setSubmitStatus(status) {
      this.submitStatus = status;
    },
  },
  computed: {
    nameErr() {
      return checkErr('name', this.errors);
    },
    emailErr() {
      return checkErr('email', this.errors);
    },
    passwordErr() {
      return checkErr('password', this.errors);
    },
    passwordConfirmationErr() {
      return checkErr('password_confirmation', this.errors);
    },
  }
}
</script>
