<template lang="pug">
  transition(name="fade" appear)
    .preloader
      ._loading

</template>
<script>
export default {
  name: "Preloader"
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.preloader {
  position: absolute;
  width: 100%;
  height: 100%;
  left: 0;
  top: 0;
  z-index: 1030;
  display: flex;
  align-items: center;
  justify-content: center;
  background: $primary;

  &__loading {
    display: inline-block;
    width: 80px;
    height: 80px;

    &::after {
      content: " ";
      display: block;
      width: 64px;
      height: 64px;
      margin: 8px;
      border-radius: 50%;
      border: 6px solid $red;
      border-color: $red transparent $red transparent;
      animation: lds-dual-ring 1.2s linear infinite;
    }

  }
}
@keyframes lds-dual-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>