import store from "../store";

const guard = async (to, from, next) => {
  const user = store.getters.getUser;
  if(user) {
    next();
    return;
  }
  next({ name: 'home', query: { auth: 'false' } });
}
export default guard;