import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    token: '',
    isLogin: false
  },
  plugins: [createPersistedState()],
  getters: {
    token: state => state.token,
    isLogin: state => state.isLogin
  },
  mutations: {
    logOut(state) {
      state.isLogin = false;
      state.token = '';
    },
    logIn(state, token) {
      state.isLogin = true;
      state.token = token;
    },
  },
  actions: {

  }
})
