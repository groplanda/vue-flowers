<template lang="pug">
  form.contact-form(@submit.prevent="onSubmit")
    .filters__popup-title Оставить отзыв
      input(type="hidden" v-model="form.product_id" placeholder="Заголовок отзыва*")._form-input
    ._form-group
      input(type="text" v-model="form.title" placeholder="Заголовок отзыва*")._form-input
      span._error( v-if="titleErr") {{ titleErr }}
    ._form-group
      textarea(type="text" v-model="form.description" placeholder="Текст отзыва*")._form-input.-textarea
      span._error( v-if="descriptionErr") {{ descriptionErr }}
    ._form-group
      ._form-status-title Оценка*
      ._form-status-list
        label(
          v-for="(item, index) in 5"
          :key="index"
          )._form-status
          input(type="radio" name="status" @click="form.star = index + 1")._form-status-input
          icon(name="star" component="product" :class="{'contact-form__form-status-ico_active': item <= form.star }")._form-status-ico
      span._error( v-if="starErr") {{ starErr }}
    button(type="submit" :class="{'contact-form__btn--offset': (titleErr || descriptionErr)}")._btn Оставить отзыв
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
  name: "CommentForm",
  components: {
    Popup
  },
  data() {
    return {
      form: {
        title: "",
        description: "",
        star: 5,
        product_id: null
      },
      submitStatus: null,
      errors: [],
      popup: false
    }
  },
  computed: {
    titleErr() {
      return checkErr('title', this.errors);
    },
    descriptionErr() {
      return checkErr('description', this.errors);
    },
    starErr() {
      return checkErr('star', this.errors);
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

      this.setSubmitStatus("Отправка...");

      axios.post('/api/add-comment', this.form)
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
  },
  mounted() {
    this.form.product_id = this.$route.params.id;
  }
}
</script>