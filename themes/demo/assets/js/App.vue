<template lang="pug">
  .app
    header.header
      ._header-nav
        router-link(to="/")._header-link Home
        router-link(to="/about")._header-link About
    router-view

</template>

<script>


export default {
  name: "App",
}
</script>
<style lang="scss">
body {
  background: #f4f4f4;
  font-family: "Helvetica Neue", Arial, sans-serif;
  width: 100%;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}
.header {
  width: 100%;
  height: 70px;
  background: #42b983;
  display: flex;
  justify-content: center;
  align-items: center;
  position: fixed;
  left: 0;
  top: 0;

  &__header-nav {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  &__header-link {
    font-size: 16px;
    font-weight: 600;
    color: #d6ffec;
    text-align: center;
    margin: 0 20px;
    text-decoration: none;
  }
}
.container {
  text-align: center;
}
h1 {
  font-size: 50px;
  font-weight: 800;
  margin: 0 0 40px 0;
}
</style>
