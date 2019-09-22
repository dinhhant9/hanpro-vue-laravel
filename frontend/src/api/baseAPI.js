import axios from 'axios';
import store from '../store'

export const baseAPI = axios.create({
  baseURL: `http://localhost/public/api/auth/`,
  headers: {
    Authorization: 'Bearer ' + store.getters.token
  }
})