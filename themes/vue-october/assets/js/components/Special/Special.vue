<template lang="pug">
  .special.section
    ._container.container
      Loading(v-if="loading")
      ._list(v-else)
        ._item(v-for="stock in stocks" :key="stock.id")
          ._item-content
            ._item-title {{ stock.title }}
            ._item-intro(v-html="stock.description")
            router-link(:to="stock.link")._item-btn Подробнее

          ._item-image
            img(:src="stock.image ? '/storage/app/media' + stock.image : noImage", :alt="stock.title")._item-thumb

</template>
<script>
import Loading from '@vue/components/Preloader/Loading.vue';
import axios from "axios";
export default {
  name: "Special",
  components: {
    Loading
  },
  data() {
    return {
      stocks: [],
      loading: true,
      noImage: "/themes/vue-october/assets/images/no-image.jpg"
    }
  },
  methods: {
    fetchStocks() {
      axios.get("/api/stocks")
      .then(response => {
        this.stocks = response.data;
        this.loading = false;
      })
      .catch(e => {
        console.log(e);
      })
    }
  },
  created() {
    this.fetchStocks();
  }
}
</script>
