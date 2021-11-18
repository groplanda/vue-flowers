<template lang="pug">
  form.contact-form(@submit.prevent="onForget")
    ._form-group
      input(type="text" v-model="form.email" placeholder="Ваш email*")._form-input
      span._error.-user( v-if="emailErr") {{ emailErr }}
    button(type="submit")._btn.-full Сбросить пароль
    ._status(v-html="submitStatus")
    a(href="#!" @click.prevent="$emit('remember')")._form-link Я вспомнил пароль
</template>
<script>
import { onValidate, checkErr } from '@vue/helpers/validate.js';
import axios from "axios";

export default {
  name: "ForgetPassword",
  data() {
    return {
      form: {
        email: "",
      },
      submitStatus: null,
      errors: [],
    }
  },
  methods: {
    onForget() {
      this.setSubmitStatus(null);
      this.errors = [];

      axios.post("/api/user/reset-password", { email: this.form.email })
    .then(response => {
      const data = response.data;

      if (data.status === "error") {
        this.onValidate(data)
        this.setSubmitStatus(null);
        return;
      }

      if (data.status === "success") {
        this.resetForm();
        this.setSubmitStatus(data.message);
      }
    })
    .catch(function (error) {
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
    emailErr() {
      return checkErr('email', this.errors);
    },
  }
}
</script>
