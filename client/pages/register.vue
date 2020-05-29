<template>
  <div class="mh-100 container justify-content-center">
    <div class="row justify-content-center">
      <div
        class="card shadow col-xl-4 col-lg-5 col-md-6 col-sm-10 col-12 p-3 mb-5 bg-white rounded"
      >
        <div class="text-center">
          <h3 class="uppercase">Register</h3>
        </div>
        <div class="card-body">
          <form @submit.prevent="login" class="col-md-12" id="register" autocomplete="off">
            <div class="form-group row">
              <div class="col-md-12">
                <input
                  type="text"
                  name="name"
                  placeholder="please enter your name"
                  id="name"
                  class="form-control"
                />
                <span
                  class="text-danger"
                  v-for="(item, index) in formError.name"
                  :key="index"
                >{{item}}</span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <b-form-file
                  accept="image/*"
                  :capture="true"
                  v-model="file"
                  :state="Boolean(file)"
                  placeholder="Choose a file or drop it here..."
                  drop-placeholder="Drop file here..."
                ></b-form-file>
                <span
                  class="text-danger"
                  v-for="(item, index) in formError.avatar"
                  :key="index"
                >{{item}}</span>
              </div>
            </div>
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
                <input
                  type="password"
                  name="password_confirmation"
                  id="password_confirmation"
                  class="form-control"
                  placeholder="please confirm your password "
                />
                <span
                  class="text-danger"
                  v-for="(item, index) in formError.password_confirmation"
                  :key="index"
                >{{item}}</span>
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-12">
                <button class="btn btn-primary uppercase">register</button>
                <nuxt-link to="/login" class="btn btn-link">already have account</nuxt-link>
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
export default {
  layout: "simple",
  head() {
    return {
      title: "User register"
    };
  },
  data() {
    return {
      file: null,
      formError: {
        name: [],
        email: [],
        password: [],
        password_confirmation: [],
        avatar: []
      }
    };
  },
  mounted() {},
  methods: {
    async login() {
      let form = document.getElementById("register");
      form = new FormData(form);
      if (this.file) {
        form.append("avatar", this.file);
      }
      this.$nuxt.$refs.loading.start();
      this.formError.name = [];
      this.formError.password = [];
      this.formError.email = [];
      this.formError.password_confirmation = [];
      this.formError.avatar = [];
      await axios
        .post("/signup", form)
        .then(async response => {
          this.$nuxt.$refs.loading.finish();
          this.$message.success(response.data.message);
          await Cookie.set("token", response.data.access_token);
          Echo.connector.pusher.config.auth.headers[
            "Authorization"
          ] = `Bearer ${response.data.access_token}`;
          axios.defaults.headers.authorization = `Bearer ${response.data.access_token}`;
          await this.getUser();
          this.$router.push({ path: "/" });
        })
        .catch(err => {
          console.log(err);
          this.$nuxt.$refs.loading.finish();
          if (!err.response) {
            this.$message.error("internet error");
          } else if (err.response.status == 422) {
            let errors = err.response.data.errors;
            this.formError = errors;
            this.$message.error(err.response.data.message);
          }
        });
    },
    async getUser() {
      this.$message.success("please wait afrew seconds while loading data");
      this.$nuxt.$refs.loading.start();
      $nuxt.user = await axios
        .get("/user")
        .then(res => {
          this.$nuxt.$refs.loading.finish();
          return res.data;
        })
        .catch(err => {
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
