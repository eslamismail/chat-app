<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow p-3">
    <nuxt-link to="/" class="navbar-brand">Post App</nuxt-link>
    <button
      class="navbar-toggler"
      type="button"
      data-toggle="collapse"
      data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent"
      aria-expanded="false"
      aria-label="Toggle navigation"
    >
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li :class="`nav-item  ${is_active('index')}`">
          <nuxt-link to="/" class="nav-link">
            Home
            <span class="sr-only">(current)</span>
          </nuxt-link>
        </li>
        <li class="nav-item" :class="`nav-item  ${is_active('posts')}`">
          <nuxt-link to="/posts" class="nav-link">
            posts
            <span class="sr-only">(current)</span>
          </nuxt-link>
        </li>
        <li class="nav-item" :class="`nav-item  ${is_active('profile')}`">
          <nuxt-link to="/profile" class="nav-link">
            profile
            <span class="sr-only">(current)</span>
          </nuxt-link>
        </li>
      </ul>
      <ul class="navbar-nav mr-6">
        <li class="nav-item dropdown">
          <a
            class="nav-link dropdown-toggle"
            href="#"
            id="navbarDropdownMenuLink"
            role="button"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false"
            v-if="$nuxt.user"
          >{{$nuxt.user.name}}</a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a
              href="#"
              @click.prevent="logout"
              :disabled="disabled ? true : false"
              :class="`dropdown-item ${disabled}`"
            >
              logout
              <span class="sr-only">(current)</span>
            </a>
          </div>
        </li>
      </ul>
    </div>
  </nav>
</template>
<script>
import axios from "axios";
const Cookie = require("js-cookie");
export default {
  name: "Nav",
  data() {
    return {
      disabled: null
    };
  },
  created() {},
  methods: {
    is_active(className) {
      if (className == $nuxt.$route.name) {
        return "active";
      } else {
        return "";
      }
    },
    async logout() {
      this.disabled = "disabled";
      this.$nuxt.$refs.loading.start();
      await axios
        .post("logout")
        .then(response => {
          this.$nuxt.$refs.loading.finish();
          Echo.leave(`post`);
          Echo.leave(`post.${$nuxt.user.id}`);
          Cookie.remove("token");
          this.$router.push({ path: "/login" });
          this.$message.success("Successfully Logout");
          axios.defaults.headers.authorization = null;
          Echo.connector.pusher.config.auth.headers["Authorization"] = null;
        })
        .catch(error => {
          this.$nuxt.$refs.loading.finish();
          if (!error.response) {
            this.$message.error("netwrok error");
          } else if (error.response.status == 401) {
            this.$message.error(error.response.data.message);
          } else {
            this.$message.error(error.response.statusText);
          }
        });
    }
  }
};
</script>
<style scope>
ul > li > a {
  text-transform: capitalize;
}
.mr-6 {
  margin-right: 6rem !important;
}
</style>
