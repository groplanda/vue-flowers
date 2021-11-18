<template lang="pug">
  section.category.section
    ProductPopup(v-if="productPopup" :moving="isMoving")
    ._container.container
      ._heading
        ._title {{ category.title }}
      Loading(v-if="showLoading")
      template(v-else)
        CategoryFilter(@sortedProducts="sortedProducts")
        ._row(v-if="category.products && category.products.length > 0")
          ._item(v-for="product in category.products" :key="product.id")
            ProductSliderItem(:product="product"  @showPopup="showPopup")
        ._empty(v-else) Товаров пока нет!
        ._pagination(v-if="pageCount > 1")
          ._pagination-list
            button(
              :class="{ 'category__pagination-item--active': index === pageNumber }"
              type="button"
              v-for="(page, index) in pageCount"
              @click="changePage(index)"
              :key="index")._pagination-item {{ page }}

</template>
<script>
import ProductSliderItem from '@vue/components/ProductSlider/ProductSliderItem';
import CategoryFilter from '@vue/components/CategoryFilter/CategoryFilter';
import Loading from '@vue/components/Preloader/Loading.vue';
import ProductPopup from '@vue/components/Product/ProductPopup';

export default {
  name: "CategoryComponent",
  components: {
    ProductSliderItem,
    CategoryFilter,
    Loading,
    ProductPopup
  },
  props: {
    category: {
      type: Object,
      required: true
    },
    showLoading: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      productPopup: false,
      isMoving: false,
      pageNumber: 0,
      size: 20
    }
  },
  watch: {
    $route (){
      this.pageNumber = 0;
    }
  },
  computed: {
    pageCount() {
      let result = 0;
      if(this.category.products && this.category.products_count) {
        result = Math.ceil(this.category.products_count / this.size);
      }
      return result;
    }
  },
  methods: {
    changePage(page) {
      this.pageNumber = page;
      this.$emit("changePage", page * this.size);
      this.$el.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      })
    },
    showPopup(event) {
      this.productPopup = event;
      setTimeout(() => {
        this.isMoving = true
        setTimeout(() => {
          this.productPopup = false;
          this.isMoving = false
        }, 2500)
      }, 1000)
    },
    sortedProducts(key) {
      this.$emit("sortedProducts", key, this.pageNumber * this.size);
    }
  }
}
</script>
