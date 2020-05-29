<template>
  <div class="row">
    <div class="col-md-12" v-if="singlePost">
      <div class="row shadow-lg m-4 p-4 justify-content-center">
        <div v-if="singlePost.image" class="col-11 col-sm-11 col-md-2 col-lg-2 col-xl-2 m-1">
          <post-image :image="fullImagePath(singlePost.image)" />
        </div>
        <div class="col-12 col-sm-12 col-md-9 col-lg-9 col-xl-9 m-2">
          <h5 class="card-text" v-if="singlePost.user">
            <nuxt-link :to="`/profile/${singlePost.user.id}`">
              <b-avatar :src="fullImagePath(singlePost.user.avatar)" variant="default"></b-avatar>
              {{(singlePost.user.name)}}
            </nuxt-link>
          </h5>
          <p v-if="!readMoreActivated">
            {{singlePost.body.slice(0, 200)}}
            <a
              @click.prevent="readMore()"
              href="#"
              v-if="singlePost.body.length > 200"
            >Read More ...</a>
          </p>
          <p v-if="readMoreActivated">
            {{longText}}
            <br />
            <a
              v-if="singlePost.body.length > 200"
              @click.prevent="readLess()"
              href="#"
            >Read Less ...</a>
          </p>
          <p>
            <post-comment :post="singlePost" />
          </p>
          <div class="d-flex justify-content-between">
            <button @click.prevent="likePost(singlePost.id)" :class="`btn ${likeClass()}`">
              <i class="far fa-thumbs-up"></i>
              Like
              <span
                v-if="singlePost && singlePost.likes"
                class="badge"
              >{{singlePost.likes.length}}</span>
            </button>
            <small class="text-muted">{{created_at}}</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import "~/assets/js/fontawnome";
import moment from "moment";
import PostImage from "~/components/posts/image";
import PostComment from "~/components/posts/comments";
export default {
  name: "PostIndex",
  props: ["post"],
  components: { PostImage, PostComment },
  data() {
    return {
      created_at: null,
      singlePost: this.post,
      readMoreActivated: false,
      longText: null
    };
  },
  async created() {
    this.solveDate();
  },
  methods: {
    fullImagePath(image) {
      let url = axios.defaults.baseURL.replace("/api", "");
      if (image) {
        return url + image;
      } else {
        return null;
      }
    },
    solveDate() {
      let start = moment().valueOf();
      let end = moment(this.post.created_at).valueOf();
      let x = setInterval(() => {
        let hour = 1000 * 60 * 60;
        if (start - end <= hour * 1) {
          this.created_at = `${moment(this.singlePost.created_at).fromNow()}`;
        } else if (start - end <= hour * 24) {
          this.created_at = `Today at  ${moment(this.post.created_at).format(
            "LT"
          )}`;
        } else {
          this.created_at = moment(this.post.created_at).format("LLLL");
          clearInterval(x);
        }
      }, 100);
    },
    async likePost(id) {
      await axios
        .post(`/posts/like/${id}`)
        .then(response => {
          this.$message.success(response.data.message);
        })
        .catch(error => {
          if (!error.response) {
            this.$message.error("no internet connection");
          } else if (error.response.status == 401) {
            this.$message.error(error.response.data.message);
            this.$router.push("/login");
          }
        });
    },
    likeClass() {
      if (this.singlePost && this.singlePost.likes) {
        for (let index = 0; index < this.singlePost.likes.length; index++) {
          if (this.singlePost.likes[index].user_id == $nuxt.user.id) {
            return "btn-primary";
          }
        }
        return "btn-outline-primary";
      }
    },
    readMore() {
      this.readMoreActivated = true;
      this.longText = this.post.body;
    },
    readLess() {
      this.readMoreActivated = false;
    }
  },
  watch: {
    post(post) {
      this.singlePost = post;
    }
  }
};
</script>
