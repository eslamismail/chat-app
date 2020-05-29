<template>
  <main role="main" v-if="user">
    <section class="jumbotron">
      <div class="container">
        <div class="row shadow-lg p-5 m-2">
          <div class="col-md-12">
            <h1>
              <b-avatar :src="fullImagePath(user.avatar)" variant="default"></b-avatar>
            </h1>
            <p>Name: {{user.name}}</p>
            <p>Email: {{user.email}}</p>
            <p>Posts: {{user.posts.length}}</p>
          </div>
        </div>
      </div>
    </section>
    <div class="container">
      <post-index v-for="(item, index) in user.posts" :post="item" :key="index" />
    </div>
  </main>
</template>
<script>
import PostIndex from "~/components/posts/view.vue";
export default {
  middleware: "authenticated",
  components: { PostIndex },
  data() {
    return {
      user: null
    };
  },
  head() {
    return {
      title: `${this.user ? this.user.name : ""} Profile`
    };
  },
  async created() {
    await this.getUser(this.$route.params.id);
  },
  async beforeRouteLeave(to, from, next) {
    await Echo.leave("postsUpdated");
    await Echo.leave(`post.${this.user.id}`);
    next();
  },
  methods: {
    async getUser(id) {
      await axios
        .get(`/user/${id}`)
        .then(response => {
          this.user = response.data;
          Echo.private(`post.${this.user.id}`).listen("PostOwner", e => {
            this.posts.unshift(e.post);
          });

          Echo.join("postsUpdated")
            .listen("LikePost", e => {
              let array = [];
              this.user.posts.forEach(item => {
                if (item.id == e.post.id) {
                  array.push(e.post);
                } else {
                  array.push(item);
                }
              });
              this.user.posts = array;
            })
            .listen("CommentAdded", e => {
              let array = [];
              this.user.posts.forEach(item => {
                if (item.id == e.post.id) {
                  array.push(e.post);
                } else {
                  array.push(item);
                }
              });
              this.user.posts = array;
            });
        })
        .catch(error => {
          console.log(error);
        });
    },
    fullImagePath(image) {
      let url = axios.defaults.baseURL.replace("/api", "");
      if (image) {
        return url + image;
      } else {
        return null;
      }
    }
  }
};
</script>
<style scoped>
@import "~assets/css/album.css";
</style>

