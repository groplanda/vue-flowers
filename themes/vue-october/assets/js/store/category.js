import axios from "axios";

const category = {
  state: () => ({
    categories: []
  }),
  mutations: {
    SET_CATEGORIES(state, payload) {
      state.categories = payload;
    }
  },
  actions: {
    fetchCategories( {commit} ) {
      axios.get('/api/categories')
      .then(response => {
        const data = response.data;
        let categories = [];

        data.forEach(el => {

          if(!el.parent_id && el.parent_id === null) {
            let childs = [];
            childs = data.filter(cat => cat.parent_id === el.id);
            const category = {
              id: el.id,
              title: el.title,
              image: el.image,
              is_quiz: el.is_quiz,
              products_count: el.products_count,
              slug: el.slug,
              sort_order: el.sort_order,
              childs: childs ? childs : []
            }

            categories.push(category)
          }

        });

        commit("SET_CATEGORIES", categories);
      })
      .catch(err => {
        console.log(err);
      })
    },
  },
  getters: {
    getCategories(state) {
      return state.categories;
    }
  }
}
export default category;