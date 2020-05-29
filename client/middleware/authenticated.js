import axios from "axios";
import { Messsage, message } from "ant-design-vue";
export default async function({ store, redirect }) {
  // If the user is not authenticated
  if (!$nuxt.user) {
    await axios
      .get("/user")
      .then(res => {
        $nuxt.user = res.data;
      })
      .catch(err => {
        if (!err.response) {
          message.error("no internet");
          $nuxt.user = null;
          redirect("/login");
        } else if (err.response.status == 401) {
          redirect("/login");
          $nuxt.user = null;
        } else {
          console.log(err);
        }
      });
  }
}
