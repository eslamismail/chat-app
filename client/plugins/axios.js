import axios from "axios";
const Cookie = process.client ? require("js-cookie") : undefined;
window.axios = axios;
export default ({ app, store, redirect }) => {
  axios.defaults.baseURL = process.env.apiUrl;

  let token = Cookie.get("token");
  axios.defaults.headers = {};
  axios.defaults.headers["X-Requested-With"] = "XMLHttpRequest";
  axios.defaults.headers["Accept"] = "application/json";
  axios.defaults.headers["Content-Type"] = "application/json";
  if (token) {
    axios.defaults.headers.authorization = `Bearer ${token}`;
  }
};
