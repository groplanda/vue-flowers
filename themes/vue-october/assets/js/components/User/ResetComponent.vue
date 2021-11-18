<template lang="pug">
  form.contact-form.-reset(@submit.prevent="onReset")
    ._form-group
      input(type="text" v-model="form.code" placeholder="Код восстановления*")._form-input
      span._error.-user( v-if="codeErr") {{ codeErr }}
    ._form-group
      input(type="password" v-model="form.password" placeholder="Новый пароль*")._form-input
      span._error.-user( v-if="passwordErr") {{ passwordErr }}
    button(type="submit")._btn.-full Изменить пароль
    ._status {{ submitStatus }}

</template>
<script>
import { onValidate, checkErr } from '@vue/helpers/validate.js';
import axios from "axios";

export default {
  name: "ResetComponent",
  data() {
    return {
      form: {
        code: "",
        password: ""
      },
      submitStatus: null,
      errors: [],
    }
  },
  methods: {
    onReset() {
      this.setSubmitStatus(null);
      this.errors = [];

      axios.post('/api/user/restore-password', this.form)
        .then(response => {
          const data = response.data;
          if (data.status === 'error') {
            this.onValidate(data)
            this.setSubmitStatus(null);
            return;
          }

          if (data.status === 'success') {
            this.resetForm();
            this.setSubmitStatus(data.message);

            setTimeout(() => {
              this.$router.push({ name: 'home' });
            }, 3000)
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
    resetCode() {
      if (this.$route.query.reset) {
        this.form.code = this.$route.query.reset;
        return;
      }
      this.$router.push({ name: 'home' });
    }
  },
  computed: {
    codeErr() {
      return checkErr('code', this.errors);
    },
    passwordErr() {
      return checkErr('password', this.errors);
    },
  },
  mounted() {
    if (this.$route.query) {
      this.resetCode();
    }
  }
}
</script>
