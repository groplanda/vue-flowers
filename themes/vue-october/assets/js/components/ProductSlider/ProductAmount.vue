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
