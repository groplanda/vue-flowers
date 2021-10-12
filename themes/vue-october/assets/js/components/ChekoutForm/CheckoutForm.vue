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
            v-model="form.user_mail"
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
        ._group.-full
          label._radio
            input(type="radio" name="payment_type" value="0" checked v-model="form.user_payment")._radio-input
            span._radio-el
            span._radio-text Оплата при доставке

          label._radio
            input(type="radio" name="payment_type" value="1" v-model="form.user_payment")._radio-input
            span._radio-el
            span._radio-text Онлайн-оплата (Yandex Kassa)
            icon(name="yakassa" component="checkout")._radio-ico

          span._error( v-if="paymentErr") {{ paymentErr }}
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
    }
  },
  data() {
    return {
      form: {
        user_name: "",
        user_phone: "",
        user_mail: "",
        user_address: "",
        user_comment: "",
        user_payment: 0,
        user_sum: 0
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
      return checkErr('user_mail', this.errors);
    },

    commentErr() {
      return checkErr('user_comment', this.errors);
    },

    paymentErr() {
      return checkErr('user_payment', this.errors);
    },

    submitMsg () {
      return this.submitStatus;
    },

    calculateSum() {
      let result = 0;
      this.products.forEach(product => {
        result += Number(product.amount) * Number(product.sale_price ? product.sale_price : product.price);
      })
      return result;
    },
  },
  methods: {
    onCheckout() {
      this.setSubmitStatus(null);
      this.errors = [];

      this.setSubmitStatus("Заказ отправляется...");
      this.form.products = this.products;
      this.form.user_sum = this.calculateSum;

      axios.post('api/add-order', this.form)
        .then(response => {
          let message = ''
          const data = response.data;
          if(data.status === 'error') {
            this.onValidate(data)
            this.setSubmitStatus(null);

          } else if (data.status === 'redirect') {
            message = 'Подготовка формы оплаты...';
            this.clearProductData();
            setTimeout(() => {
              window.location.replace(data.message);
            }, 1500);

          } else if(data.status === 'success') {
            message = data.message;
            this.popup = true;
            this.clearProductData();
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

    clearProductData() {
      this.resetForm();
      if (localStorage.getItem('cart')) {
        localStorage.removeItem('cart');
        this.$store.dispatch("fillCart" , []);
        this.$emit("hideTotal");
      }
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
.checkout-form {
  width: 100%;
  max-width: calc(100% - 445px);

  @media(max-width: 1440px) {
    max-width: calc(100% - 370px);
  }

  @media(max-width: 1199px) {
    max-width: calc(100% - 340px);
  }

  @media(max-width: 767px) {
    max-width: 100%;
  }

  $root: &;

  &__inner {
    background: #f8f8f8;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 10px 29px 0 $shadow;

    @media(max-width: 767px) {
      padding: 12px;
      border-radius: 7px;
    }
  }

  &__row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -10px 30px -10px;

    @media(max-width: 991px) {
      margin: 0 0 30px 0;
    }
  }

  &__group {
    width: 100%;
    max-width: calc(50% - 20px);
    margin: 0 10px 20px;
    position: relative;

    &--full {
      max-width: calc(100% - 20px);
    }

    @media(max-width: 991px) {
      max-width: 100%;
    }
  }

  &__input {
    color: $primary;
    font-size: 16px;
    background: #f8f8f8;
    border: 2px solid $borderColor;
    border-radius: 10px;
    padding: 0 20px;
    width: 100%;
    height: 50px;
    outline: none;
    transition: all 0.18s linear;

    &--error {
      border-color: $red;
    }

    &::placeholder {
      color: $primary;
    }

    &--textarea {
      height: 140px;
      resize: none;
      padding: 12px 20px;
    }

    @media(max-width: 767px) {
      height: 40px;
      border-width: 1px;
    }
  }

  &__error {
    color: $red;
    font-size: 13px;
    line-height: 21px;
    font-weight: 500;
    @media(max-width: 767px) {
      font-size: 12px;
      line-height: 18px;
    }
  }

  &__btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 15px;
    font-weight: 500;
    padding: 15px 60px;
    background: $blue;
    border-radius: 7px;
    transition: background .2s ease;

    &:disabled, &[disabled] {
      background: $secondary;
      cursor: not-allowed;
    }
  }

  &__status {
    color: $primary;
    margin-top: 10px;
    font-size: 14px;
    line-height: 18px;
    font-weight: 500;
  }

  &__radio {
    display: flex;
    align-items: center;
    position: relative;
    cursor: pointer;
    margin-bottom: 20px;

    @media(max-width: 767px) {
      align-items: flex-start;
    }

    &:last-child {
      margin-bottom: 0;
    }
  }

  &__radio-input {
    position: absolute;
    z-index: -1;
    opacity: 0;

    &:checked ~ {
      #{$root} {
        &__radio-el {
          border-color: $blue;
        }
        &__radio-el::after {
          opacity: 1;
        }
      }
    }
  }

  &__radio-el {
    width: 20px;
    height: 20px;
    border-radius: 10px;
    border: 1px solid $secondary;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
    transition: all .2s ease;

    @media(max-width: 767px) {
      width: 16px;
      height: 16px;
      border-radius: 8px;
      margin-right: 7px;
      top: 4px;
      position: relative;
    }

    &::after {
      content: "";
      width: 10px;
      height: 10px;
      border-radius: 5px;
      background: $blue;
      opacity: 0;

      @media(max-width: 767px) {
        width: 8px;
        height: 8px;
        border-radius: 4px;
      }
    }
  }

  &__radio-text {
    font-size: 16px;
    line-height: 21px;

    @media(max-width: 767px) {
      max-width: calc(100% - 90px);
    }
  }

  &__radio-ico {
    margin-left: 10px;
    display: flex;
    width: 60px;
    height: auto;

    @media(max-width: 767px) {
      position: absolute;
      top: 0;
      right: 0;
    }
  }

}
</style>