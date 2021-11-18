<template lang="pug">
  section.post.section
    ._container.container
      ._heading
        ._title Восстановление пароля
      ._body
        ResetComponent
</template>
<script>
import ResetComponent from "@vue/components/User/ResetComponent"
export default {
  name: "ResetPassword",
  components: {
    ResetComponent
  }
}
</script>