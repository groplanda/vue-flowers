<template lang="pug">
  section.category.section
    ProductPopup(v-if="productPopup")
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
        this.productPopup = false;
      }, 2000)
    },
    sortedProducts(key) {
      this.$emit("sortedProducts", key, this.pageNumber * this.size);
    }
  }
}
</script>
<style lang="scss">
@import '@/scss/vars.scss';
.category {
  padding: 40px 0 100px;
  background: #fbf8ec;

  @media(max-width: 1199px) {
    padding: 30px 0 80px;
  }

  @media(max-width: 767px) {
    padding: 20px 0 40px;
  }

  &__heading {
    padding-bottom: 50px;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    @media(max-width: 1199px) {
      padding-bottom: 40px;
    }
    @media(max-width: 767px) {
      padding-bottom: 30px;
    }

    &--space {
      justify-content: space-between;
    }
  }

  &__total {
    margin-top: -40px;
    color: #41445C;
    font-size: 14px;
    font-weight: 500;
    position: relative;
    @media(max-width: 1199px) {
      margin-top: -30px;
    }
    @media(max-width: 991px) {
      margin-top: -20px;
    }
    @media(max-width: 575px) {
      font-size: 13px;
      margin-top: -15px;
    }
  }

  &__title {
    color: #41445C;
    font-size: 50px;
    font-weight: bold;
    @media(max-width: 1199px) {
      font-size: 40px;
    }
    @media(max-width: 767px) {
      font-size: 30px;
    }
    @media(max-width: 575px) {
      font-size: 25px;
    }

    @media(max-width: 350px) {
      font-size: 20px;
    }
  }

  &__search-val {
    color: $secondary;
    margin-left: 15px;
    font-weight: 400;
    @media(max-width: 575px) {
      margin-left: 10px;
    }
  }

  &__row {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -20px;
    @media(max-width: 1199px) {
      margin: 0 -10px;
    }
    @media(max-width: 575px) {
      margin: 0;
      flex-direction: column;
    }
  }

  &__item {
    width: 100%;
    max-width: calc(25% - 40px);
    margin: 20px;

    @media(max-width: 1440px) {
      max-width: calc(33.333% - 40px);
    }

    @media(max-width: 1199px) {
      max-width: calc(33.333% - 20px);
      margin: 10px;
    }

    @media(max-width: 991px) {
      max-width: calc(50% - 20px);
    }

    @media(max-width: 575px) {
      max-width: 100%;
      margin: 10px 0;
    }
  }

  &__pagination {
    display: flex;
    align-items: center;
    justify-content: flex-end;
  }

  &__pagination-list {
    margin-left: auto;
    margin-right: 0;
    display: flex;
    align-items: center;
    justify-content: flex-end;
  }

  &__pagination-item {
    background: $blue;
    box-shadow: 7px 7px 30px $shadow;
    color: #FFF;
    font-size: 18px;
    font-weight: 500;
    margin-right: 10px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border-radius: 7px;

    &:last-child {
      margin-right: 0;
    }

    @media(max-width: 767px) {
      width: 30px;
      height: 30px;
      font-size: 14px;
    }

    &--active {
      color: $secondary;
    }
  }
}
</style>