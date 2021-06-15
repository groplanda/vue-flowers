<template lang="pug">
   section.post.section
    ._container.-center.container.container--main
      ._heading
        ._title 404
      ._body
        p Такой страницы не существует. Вернитесь на&nbsp;
          router-link(:to="{name: 'home'}") главную
</template>
<script>
export default {
  name: "Notfound"
}
</script>
<style lang="scss">

</style>