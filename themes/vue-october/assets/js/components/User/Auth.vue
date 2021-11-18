<template lang="pug">
  form.contact-form(@submit.prevent="onAuth")
    ._form-group
      input(type="text" v-model="form.login" placeholder="Ваш email*")._form-input
      span._error.-user( v-if="loginErr") {{ loginErr }}
    ._form-group
      a(href="#!" @click.prevent="$emit('forget')")._form-uri Я забыл пароль
      input(type="password" v-model="form.password" placeholder="Ваш пароль*")._form-input
      span._error.-user( v-if="passwordErr") {{ passwordErr }}
    button(type="submit")._btn.-full Войти
    ._status {{ submitStatus }}

</template>
<script>
import { onValidate, checkErr } from '@vue/helpers/validate.js';
import axios from "axios";

export default {
  name: "Auth",
  data() {
    return {
      form: {
        login: "",
        password: ""
      },
      submitStatus: null,
      errors: [],
    }
  },
  methods: {
    onAuth() {
      this.setSubmitStatus(null);
      this.errors = [];

      axios.post('/api/user/auth', this.form)
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
    loginErr() {
      return checkErr('login', this.errors);
    },
    passwordErr() {
      return checkErr('password', this.errors);
    },
  }
}
</script>
