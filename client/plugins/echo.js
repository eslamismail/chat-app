require("dotenv").config();
const Cookie = process.client ? require("js-cookie") : undefined;
let token = Cookie.get("token");

import Echo from "laravel-echo";

window.Pusher = require("pusher-js");
window.Echo = new Echo({
  broadcaster: "pusher",
  key: process.env.MIX_PUSHER_APP_KEY,
  wsHost: window.location.hostname,
  wsPort: 6001,
  encrypted: false,
  disableStats: true,
  authEndpoint: `${process.env.apiUrl}/broadcasting/auth`,
  auth: { headers: { Authorization: "Bearer " + token } }
});
