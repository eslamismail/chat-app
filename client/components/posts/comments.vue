<template>
  <div>
    <span class="text-muted">
      <a href="#" @click.prevent="showModal" class="text-muted">{{post.comments.length}} Comments</a>
    </span>
    <a-modal :footer="null" title :visible="visible" @ok="handleOk" @cancel="handleCancel">
      <form autocomplete="off" @submit.prevent="addComment">
        <div class="row form-group">
          <div class="col-10 col-md-10 col-lg-10 col-xl-10">
            <input
              type="text"
              name="comment"
              class="form-control"
              placeholder="leave comment please"
            />
            <errors v-if="errors && errors.comment" :errors="errors.comment" />
            <errors v-if="errors && errors.image" :errors="errors.image" />
          </div>
          <div class="col-2 col-md-2 col-lg-2 col-xl-2">
            <i class="fas fa-camera scall100" @click.prevent="uploadFile()"></i>
            <input type="file" accept="image/*" name="image" id="image" class="form-control d-none" />
          </div>
        </div>
      </form>
      <div class="row borderd" v-for="(item, index) in post.comments" :key="index">
        <div class="col-12">
          <h5 class="card-text" v-if="item.user">
            <nuxt-link :to="`/profile/${item.user.id}`">
              <b-avatar :src="fullImagePath(item.user.avatar)" variant="default"></b-avatar>
              {{(item.user.name)}}
            </nuxt-link>
          </h5>
          <p>{{item.text}}</p>
          <small class="text-muted">{{solveDate(item.created_at)}}</small>
        </div>
        <div v-if="item.image" class="col-12">
          <b-img :src="fullImagePath(item.image)" thumbnail center alt="Responsive image"></b-img>
        </div>
      </div>
    </a-modal>
  </div>
</template>
<script>
import moment from "moment";
import Errors from "~/components/errors";
export default {
  components: { Errors },
  props: ["post"],
  data() {
    return {
      visible: false,
      errors: {}
    };
  },

  methods: {
    handleOk() {
      this.visible = false;
    },
    solveDate(date) {
      return `${moment(date).fromNow()}`;
    },
    fullImagePath(image) {
      let url = axios.defaults.baseURL.replace("/api", "");
      if (image) {
        return url + image;
      } else {
        return null;
      }
    },
    showModal() {
      this.visible = true;
    },
    handleCancel(e) {
      this.visible = false;
    },
    addComment() {
      event.preventDefault();

      let form = event.target;
      let formData = new FormData(form);
      this.errors = null;

      axios
        .post(`/posts/${this.post.id}/comments`, formData)
        .then(response => {
          this.$message.success(response.data.message);
          form.reset();
        })
        .catch(error => {
          console.log(error);
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

    uploadFile() {
      document.getElementById("image").click();
    }
  }
};
</script>
<style  scoped>
.scall100 {
  font-size: 258%;
  cursor: pointer;
}
.borderd {
  border: 1px solid #a9a9a9;
  margin: 2px;
  box-shadow: 0 1rem 1rem rgba(0, 0, 0, 0.175);
  padding: 5px;
}
</style>
