<template lang="pug">
  .load
    ._loading

</template>
<script>
export default {
  name: "LoadingComponent"
}
</script>
<style lang="scss">
.load {
  position: relative;
  width: 100%;
  min-height: 400px;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;

  &__loading {
    display: inline-block;
    width: 75px;
    height: 75px;

    &::after {
      content: " ";
      display: block;
      width: 64px;
      height: 64px;
      margin: 8px;
      border-radius: 50%;
      border: 6px solid #FF7A5C;
      border-color: #FF7A5C transparent #FF7A5C transparent;
      animation: lds-dual-ring 1.2s linear infinite;
    }

  }
}
</style>