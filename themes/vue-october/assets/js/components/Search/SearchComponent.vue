<template lang="pug">
  section.category.section
    ProductPopup(v-if="productPopup")
    ._container.container.container--main
      ._heading.-space
        ._title Поиск по:
          span._search-val {{ search }}

        SearchFilter(@onSearch="onSearch")
      ._total(v-if="+total > 0") Найдено: {{ total }} позиций

      Loading(v-if="showLoading")
      template(v-else)
        ._row(v-if="products && products.length > 0")
          ._item(v-for="product in products" :key="product.id")
            ProductSliderItem(:product="product"  @showPopup="showPopup")
        ._empty(v-else) Ничего не удалось найти!

</template>
<script>
import ProductSliderItem from '@vue/components/ProductSlider/ProductSliderItem';
import Loading from '@vue/components/Preloader/Loading';
import SearchFilter from './SearchFilter';
import ProductPopup from '@vue/components/Product/ProductPopup';

export default {
  name: "SearchComponent",
  components: {
    ProductSliderItem,
    Loading,
    SearchFilter,
    ProductPopup
  },
  props: {
    products: {
      type: Array,
      required: true
    },
    showLoading: {
      type: Boolean,
      default: false
    },
    search: {
      type: String,
      required: true
    }
  },
  data() {
    return {
      productPopup: false,
    }
  },
  computed: {
    total() {
      return this.products.length;
    }
  },
  methods: {
    showPopup(event) {
      this.productPopup = event;
      setTimeout(() => {
        this.productPopup = false;
      }, 2000)
    },
    onSearch(val) {
      this.$emit('inputSearch', val);
    }
  }
}
</script>
