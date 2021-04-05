<template lang="pug">
  .container
    h1 Home page
    p {{ message }}
    icon(name="react" component="app")

</template>
<script>
export default {
  name: "Home",
  data() {
    return {
      message: 'HELLO FROM VUE.JS'
    }
  },
}
</script>
<style lang="scss">

</style>