<template lang="pug">
  .advantages.section
    ._container.container
      ._row
        ._item(v-for="(adv, index) in advantages" :key="index")
          ._item-img
            img(:src="adv.ico ? '/storage/app/media' + adv.ico : noImage", :alt="adv.title")._item-thumb
          ._item-title {{ adv.title }}
          ._item-descr {{ adv.descr }}


</template>
<script>
export default {
  name: "Advantages",
  computed: {
    advantages() {
      return this.$store.getters.getSettings.advantages;
    }
  },
  data() {
    return {
      noImage: "/themes/vue-october/assets/images/no-image.jpg",
    }
  }
}
</script>