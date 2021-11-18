import axios from "axios";
import router from '@vue/router/routes';

function redirectUser() {
  router.push({ name: 'home', query: { auth: 'logout' } })
}

const user = {
  state: () => ({
    userdata: null,
    user: null,
    userOrders: []
  }),
  mutations: {
    SET_USERDATA(state, payload) {
      state.userdata = payload;
    },
    SET_USER(state, payload) {
      state.user = payload;
    },
    SET_USER_ORDERS(state, payload) {
      state.userOrders = payload;
    },
  },
  actions: {
    fetchUserdata( {commit} ) {
      axios.get('/api/user/userdata')
      .then(response => {
        const data = response.data;
        if (data.status === "success") {
          commit("SET_USERDATA", data.user)
        }
      })
      .catch(err => {
        console.log(err);
      })
    },
    isUserLogin( {commit} ) {
      axios.get('/api/user/isUserLogin')
      .then(response => {
        commit("SET_USER", response.data)
      })
      .catch(err => {
        console.log(err);
      })
    },
    fetchUserOrders( {commit} ) {
      axios.get("/api/user/orders")
      .then(response => {
        const data = response.data;
        if (response.status === 200 && data.status === "success") {
          commit("SET_USER_ORDERS", data.message)
        }
      })
      .catch(error => {
        if (error.response.status === 403) {
          console.log('403');
        }
        if (error.response.status === 500) {
          console.log('500');
        }
      })
    },
    onLogout( {commit} ) {
      axios.get("/api/user/logout")
      .then(response => {
        const data = response.data;
        if (response.status === 200 && data.status === "success") {
          commit("SET_USER", null);
          commit("SET_USERDATA", null);
          commit("SET_USER_ORDERS", []);
          redirectUser('logout');
        }
      })
      .catch(error => {
        if (error.response.status === 403) {
          console.log('403');
        }
        if (error.response.status === 500) {
          console.log('500');
        }
      })
    }
  },
  getters: {
    getUserData(state) {
      return state.userdata;
    },
    getUser(state) {
      return state.user;
    },
    getUserOrders(state) {
      return state.userOrders;
    }
  }
}
export default user;