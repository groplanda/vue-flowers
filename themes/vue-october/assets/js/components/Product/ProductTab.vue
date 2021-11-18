<template lang="pug">
  .product-tab
    ._heading
      ._heading-item(@click="changeTab" data-tab="0" :class="{ 'product-tab__heading-item--active': activeTab == 0 }") Описание
      ._heading-item(v-if="info.props && info.props.length" @click="changeTab" data-tab="1" :class="{ 'product-tab__heading-item--active': activeTab == 1 }") Параметры
      ._heading-item(@click="changeTab" data-tab="2" :class="{ 'product-tab__heading-item--active': activeTab == 2 }") Преимущества
      ._heading-item(@click="changeTab" data-tab="3" :class="{ 'product-tab__heading-item--active': activeTab == 3 }") Отзывы
    ._body
      ._body-item(v-if="activeTab == 0")
        ._code(v-if="info.code") Артикул {{ info.code }}
        ._descr(v-html="info.description")
      ._body-item(v-if="activeTab == 1")
        ._props
          ._props-item(v-for="(prop, index) in info.props" :key="index")
            ._props-label {{ prop.props_option }}
            ._props-val {{ prop.props_value }}

      ._body-item(v-if="activeTab == 2")
        ._advantages
          ._advantages-item(v-for="(adv, index) in advantages" :key="index")
            ._advantages-img
              img(:src="adv.ico ? '/storage/app/media' + adv.ico : noImage", :alt="adv.title")._advantages-thumb
            ._advantages-title {{ adv.title }}
            ._advantages-descr {{ adv.descr }}

      ._body-item(v-if="activeTab == 3")
        ProductComment(:comments="info.comments")

</template>
<script>
import ProductComment from './Comment/Comment';

export default {
  name: "ProductTab",
  props: {
    info: {
      type: Object,
      required: true
    }
  },
  components: {
    ProductComment
  },
  data() {
    return {
      activeTab: 0,
      noImage: "/themes/vue-october/assets/images/no-image.jpg",
    }
  },
  methods: {
    changeTab(e) {
      const target = e.target;
      this.activeTab = target.dataset.tab;
    }
  },
  computed: {
    advantages() {
      return this.$store.getters.getSettings.advantages;
    }
  }
}
</script>