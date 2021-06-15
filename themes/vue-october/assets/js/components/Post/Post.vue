<template lang="pug">
  section.post.section
    ._container.container
      h1._title Интернет-магазин цветов
      ._content
        p Если Вы хотите, чтобы Ваш сайт не просто занимал свою нишу в сети Интернет, но и хорошо воспринимался поисковыми системами, Вы можете прочитать рекомендации, которые дает Яндекс - «Чем отличается качественный сайт от некачественного с точки зрения Яндекса» .
        p Обращаем Ваше внимание, что текстовая информация на сайте должна быть индивидуальной, не скопированной с других Интернет-ресурсов, о чем указано в рекомендациях Яндекса:
        p «Мы стараемся не индексировать или не ранжировать высоко сайты, копирующие информацию с других ресурсов и не создающие оригинального контента или сервиса».
</template>
<script>
export default {
  name: "Post"
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.post {
  padding: 50px 0;
  @media(max-width: 767px) {
    padding: 30px 0;
  }

  &__title {
    font-size: 40px;
    font-weight: bold;
    margin-bottom: 30px;

    @media(max-width: 767px) {
      font-size: 30px;
    }

    @media(max-width: 575px) {
      font-size: 25px;
      margin-bottom: 20px;
    }
  }

  &__content, &__content p {
    color: $primary;
    font-size: 16px;
    margin-bottom: 15px;
    
    @media(max-width: 767px) {
      font-size: 14px;
    }
  }
}
</style>