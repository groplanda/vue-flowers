<template lang="pug">
.product-amount
  button(
    type="button"
    @click="changeAmount"
    data-js-action="minus"
    :disabled="currentAmount === 1"
    :class="{ 'product-item__amount-btn--disabled': currentAmount === 1 }")._btn
    icon(name="minus" component="product")._btn-ico
  input(type="number" v-model="currentAmount" readonly)._val
  button(
    @click="changeAmount"
    data-js-action="plus"
    type="button")._btn
    icon(name="plus" component="product")._btn-ico

</template>
<script>
export default {
  name: "ProductAmount",
  props: {
    amount: {
      type: Number,
      default: 1
    }
  },
  watch: {
    amount() {
      this.currentAmount = this.amount;
    }
  },
  data() {
    return {
      currentAmount: this.amount
    }
  },
  methods: {
    changeAmount(e) {
      if (e.target.dataset.jsAction === "plus" && this.currentAmount < 100) {
        this.currentAmount = this.currentAmount + 1;
      } else if (e.target.dataset.jsAction === "minus" && this.currentAmount > 1) {
        this.currentAmount = this.currentAmount - 1;
      }
      this.$emit("changeAmount", this.currentAmount);
    }
  }
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.product-amount {
  display: inline-flex;
  align-items: center;
  background: #F8F8F8;
  border-radius: 8px;

  $root: &;

  &__btn {
    width: 36px;
    height: 36px;
    outline: none;
    align-items: center;
    display: inline-flex;
    justify-content: center;
    -webkit-appearance: none;
    transition: all 0.18s linear;
    border-radius: 8px;
    background: #FFF;

    &--disabled {
      cursor: not-allowed;
      #{$root} {
        &__btn-ico {
          fill: #999999;
        }
      }
    }
  }


  &__btn-ico {
    fill: $red;
    width: 12px;
    height: 12px;
    pointer-events: none;
  }

  &__val {
    font-size: 15px;
    font-weight: 500;
    text-align: center;
    width: 50px;
    height: 36px;
    -webkit-appearance: none;
    -moz-appearance: textfield;
    outline: none;

    &::-webkit-outer-spin-button,
    &::-webkit-inner-spin-button {
    -webkit-appearance: none;
    }
  }
}
</style>