<template lang="pug">
   section.post.section
    ._container.container
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