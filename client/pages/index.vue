<template>
  <main role="main">
    <PostCreate
      @handeOk="handeOk"
      :errors="errors"
      @handleCancel="handleCancel"
      :show="showCreate"
      :complete="complete"
      :loading="loading"
    />
    <section class="jumbotron text-center">
      <div class="container">
        <h1>All Posts</h1>
        <p>
          <button class="btn btn-primary" @click="open">
            Create post
            <b-icon-file-plus></b-icon-file-plus>
          </button>
        </p>
      </div>
    </section>

    <div class="album py-5" v-if="posts.length > 0">
      <div class="container">
        <post-index v-for="(item, index) in posts" :post="item" :key="index" />
      </div>
    </div>
  </main>
</template>

<script>
const jquery = require("~/assets/js/jquery.js");
require("~/assets/js/bootstrap.js");
import { BIconFilePlus } from "bootstrap-vue";
import axios from "axios";
import PostIndex from "~/components/posts/view.vue";
import PostCreate from "~/components/posts/create.vue";
export default {
  middleware: "authenticated",
  head() {
    return {
      title: "All Posts"
    };
  },
  async beforeRouteLeave(to, from, next) {
    await Echo.leave(`post`);
    await Echo.leave("postsUpdated");
    next();
  },
  data() {
    return {
      posts: [],
      showCreate: false,
      errors: null,
      complete: false,
      loading: false
    };
  },
  methods: {
    open() {
      this.showCreate = true;
      this.complete = false;
    },
    handleCancel() {
      this.showCreate = false;
      this.complete = true;
    },
    handeOk(data) {
      this.loading = true;
      this.$nuxt.$refs.loading.start();
      axios
        .post("/posts", data)
        .then(response => {
          this.$nuxt.$refs.loading.finish();
          this.loading = false;
          this.showCreate = false;
          this.errors = null;
          this.complete = true;
          this.$message.success(response.data.message);
        })
        .catch(error => {
          this.$nuxt.$refs.loading.finish();
          this.loading = false;
          if (!error.response) {
            this.$message.error("no internet");
          } else if (error.response.status == 422) {
            this.errors = error.response.data.errors;
            this.$message.error(error.response.data.message);
          } else if (error.response.status == 401) {
            this.$router.push({ path: "/login" });
            this.$message.error(error.response.data.message);
          }
        });
    },
    async getPosts() {
      this.$nuxt.$refs.loading.start();
      axios
        .get("/posts/all")
        .then(response => {
          this.$nuxt.$refs.loading.finish();
          this.posts = response.data;
        })
        .catch(error => {
          this.$nuxt.$refs.loading.finish();
          if (!error.response) {
            this.getPosts();
          } else if (error.response.status == 401) {
            $nuxt.user = null;
            this.$router.push({ path: "/login" });
            this.$message.error(error.response.data.message);
          }
        });
    }
  },
  components: { BIconFilePlus, PostIndex, PostCreate },
  async created() {
    await this.getPosts();
  },
  async mounted() {
    await Echo.leave(`post`);
    await Echo.join("post").listen("PostCreate", e => {
      this.posts.unshift(e.post);
    });
    Echo.join("postsUpdated")
      .listen("LikePost", e => {
        let array = [];
        this.posts.forEach(item => {
          if (item.id == e.post.id) {
            array.push(e.post);
          } else {
            array.push(item);
          }
        });
        this.posts = array;
      })
      .listen("CommentAdded", e => {
        let array = [];
        this.posts.forEach(item => {
          if (item.id == e.post.id) {
            array.push(e.post);
          } else {
            array.push(item);
          }
        });
        this.posts = array;
      });
  }
};
</script>

<style scoped>
@import "~assets/css/album.css";
</style>

