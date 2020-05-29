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
        <h1 class="animate__fadeInDown">My Posts</h1>
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
import { BIconFilePlus } from "bootstrap-vue";
import PostCreate from "~/components/posts/create.vue";
import PostIndex from "~/components/posts/view.vue";
export default {
  components: { BIconFilePlus, PostCreate, PostIndex },
  middleware: "authenticated",
  head() {
    return {
      title: "My Posts"
    };
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
  async beforeRouteLeave(to, from, next) {
    await Echo.leave(`post.${$nuxt.user.id}`);
    await Echo.leave("postsUpdated");
    next();
  },
  async created() {
    await this.getPosts();

    Echo.private(`post.${$nuxt.user.id}`).listen("PostOwner", e => {
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
          this.loading = false;
          this.showCreate = false;
          this.complete = true;
          this.errors = null;
          this.$message.success(response.data.message);
          this.$nuxt.$refs.loading.finish();
        })
        .catch(error => {
          this.loading = false;
          this.$nuxt.$refs.loading.finish();
          if (!error.response) {
            this.$message.error("no internet");
          } else if (error.response.status == 422) {
            this.errors = error.response.data.errors;
            this.$message.error(error.response.data.message);
          } else if (error.response.status == 401) {
            $nuxt.user = null;
            this.$message.error(error.response.data.message);
            this.$router.push({ path: "/login" });
          }
        });
    },
    async getPosts() {
      this.$nuxt.$refs.loading.start();
      axios
        .get("/posts")
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
          } else if (error.response.status == 429) {
            this.$router.push({ path: "/login" });
            this.$message.error(error.response.data.message);
          }
        });
    }
  }
};
</script>

<style scoped>
@import "~assets/css/album.css";
</style>

