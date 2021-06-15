import axios from 'axios';

const navbar = {
  state: () => ({
    navbar: []
  }),
  mutations: {
    SET_NAVBAR(state, payload) {
      state.navbar = payload;
    }
  },
  actions: {
    fetchNavbar( {commit} ) {
      axios.get('/api/navbar')
      .then(response => {
        const navbar = response.data.map(el => {
          return {
            title: el.title,
            items: el.items,
            url: el.url,
            code: el.code,
            ref: el.reference
          }
        })
        commit("SET_NAVBAR", navbar)
      })
      .catch(err => {
        console.log(err);
      })
    },
  },
  getters: {
    getNavbar(state) {
      return state.navbar;
    }
  }
}
export default navbar;