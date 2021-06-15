const cart = {
  state: () => ({
    cart: [],
  }),
  mutations: {
    SET_CART(state, payload) {
      const index = state.cart.findIndex(el => el.id === payload.id);
      if (index === -1) {
        state.cart.push(payload);
      } else {
        state.cart[index].amount = Number(state.cart[index].amount) + Number(payload.amount);
      }
    },
    FILL_CART(state, payload) {
      state.cart = payload;
    },
    UPDATE_CART(state, payload) {
      state.cart  = state.cart.map(product => {
        if(payload.id == product.id) {
          product.amount = payload.amount;
        }
        return product
      })
    },
    DELETE_PRODUCT(state, payload) {
      const productIndex = state.cart.findIndex(el => el.id === payload);
      if (productIndex !== -1) {
        state.cart.splice(productIndex, 1);
      }
    }
  },
  actions: {
    fillCart({commit}, data) {
      commit("FILL_CART", data);
    },
    addToCart({commit}, data) {
      if (localStorage.getItem('cart')) {
        const cart = JSON.parse(localStorage.getItem('cart'));
        const index = cart.findIndex(el => el.id === data.id);
        if (index === -1) {
          cart.push(data);
        } else {
          cart[index].amount = Number(cart[index].amount) + Number(data.amount);
        }
        localStorage.setItem('cart', JSON.stringify(cart));
      } else {
        localStorage.setItem('cart', JSON.stringify([data]));
      }
      commit("SET_CART", data);
    },
    updateCart( {commit, dispatch} , data) {
      dispatch("setLoading", true);
      if (localStorage.getItem('cart')) {
        const cart = JSON.parse(localStorage.getItem('cart'));
        const result = cart.map(product => {
          if(data.id == product.id) {
            product.amount = data.amount;
          }
          return product
        })
        localStorage.setItem('cart', JSON.stringify(result));
      }
      commit("UPDATE_CART", data);
      dispatch("setLoading", false);
    },
    deleteProduct({commit, dispatch}, data) {
      dispatch("setLoading", true);
      if (localStorage.getItem('cart')) {
        const cart = JSON.parse(localStorage.getItem('cart'));
        const productIndex = cart.findIndex(el => el.id === data);
        if (productIndex !== -1) {
          cart.splice(productIndex, 1);
          localStorage.setItem('cart', JSON.stringify(cart));
        }
      }
      commit("DELETE_PRODUCT", data);
      dispatch("setLoading", false);
    }
  },
  getters: {
    getCart(state) {
      return state.cart;
    }
  }
}
export default cart;