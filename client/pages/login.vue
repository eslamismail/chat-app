<template>
  <div class="mh-100 container justify-content-center">
    <div class="justify-content-center row">
      <div
        class="card shadow col-xl-4 col-lg-6 col-md-6 col-sm-10 col-12 p-3 mb-5 bg-white rounded"
      >
        <div class="text-center">
          <h3 class="uppercase">Login</h3>
        </div>
        <div class="card-body">
          <form @submit.prevent="login" class="col-md-12" id="login" autocomplete="off">
            <div class="form-group row">
              <div class="col-md-12">
                <input
                  type="text"
                  name="email"
                  id="email"
                  placeholder="please enter your email"
                  class="form-control"
                />
                <span
                  class="text-danger"
                  v-for="(item, index) in formError.email"
                  :key="index"
                >{{item}}</span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <input
                  type="password"
                  name="password"
                  id="password"
                  class="form-control"
                  placeholder="please enter your password "
                />
                <span
                  class="text-danger"
                  v-for="(item, index) in formError.password"
                  :key="index"
                >{{item}}</span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <button class="btn btn-primary">Login</button>
                <nuxt-link to="/register" class="btn btn-link">Don't have account</nuxt-link>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
const Cookie = require("js-cookie");
import Vue from "vue";
export default {
  layout: "simple",
  head() {
    return {
      title: "User Login"
    };
  },
  data() {
    return {
      formError: {
        email: [],
        password: []
      }
    };
  },
  created() {
    Cookie.remove("token");
  },
  mounted() {},
  methods: {
    async login() {
      let form = document.getElementById("login");
      form = new FormData(form);
      this.formError.email = [];
      this.formError.password = [];
      this.$nuxt.$refs.loading.start();
      await axios
        .post("login", form)
        .then(async res => {
          this.$message.success("Welcom back!");
          this.$nuxt.$refs.loading.finish();
          Cookie.set("token", res.data.access_token);
          Echo.connector.pusher.config.auth.headers[
            "Authorization"
          ] = `Bearer ${res.data.access_token}`;
          axios.defaults.headers.authorization = `Bearer ${res.data.access_token}`;
          await this.getUser();
          this.$router.push({ path: "/" });
        })
        .catch(err => {
          this.$nuxt.$refs.loading.finish();
          if (!err.response) {
            this.$message.error("network error");
          } else if (err.response.status == 422) {
            let errors = err.response.data.errors;
            this.$message.error(err.response.data.message);
            this.formError = errors;
          } else {
            this.$message.error(err.response.data.message);
          }
        });
    },
    async getUser() {
      this.$nuxt.$refs.loading.start();
      $nuxt.user = await axios
        .get("/user")
        .then(res => {
          this.$nuxt.$refs.loading.finish();
          return res.data;
        })
        .catch(err => {
          console.log(err);
          this.$nuxt.$refs.loading.finish();
          return null;
        });
    }
  }
};
</script>

<style scoped>
.container {
  height: 100%;
  min-height: 100%;
}
html,
body {
  height: 100%;
  background: #fbfbfb;
}
.card {
  margin: 5em auto 5em auto !important;
}
span {
  margin: 0;
  padding: 0;
  display: block;
  font-size: 13px;
}
</style>
