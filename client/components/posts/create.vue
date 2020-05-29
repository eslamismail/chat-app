<template>
  <div>
    <a-modal
      :confirmLoading="loading"
      v-model="showModal"
      title="Create Post"
      centered
      @ok="handleOk"
      @cancel="handleCancel"
    >
      <form @submit.prevent="handleOk" id="post" class="justify-content-center">
        <div class="form-group row justify-content-center">
          <div class="col-md-8">
            <textarea
              name="body"
              id="body"
              placeholder="Please enter post body"
              class="form-control"
            ></textarea>
            <errors v-if="errors && errors.body" :errors="errors.body" />
          </div>
        </div>
        <div class="form-group row justify-content-center">
          <div class="col-md-8">
            <b-form-file
              accept="image/*"
              :capture="true"
              v-model="file"
              :state="Boolean(file)"
              placeholder="Choose a file or drop it here..."
              drop-placeholder="Drop file here..."
            ></b-form-file>
            <errors v-if="errors && errors.image" :errors="errors.image" />
          </div>
        </div>
      </form>
    </a-modal>
  </div>
</template>
<script>
import Errors from "~/components/errors";
export default {
  components: { Errors },
  name: "PostCreate",
  props: ["show", "complete", "errors", "loading"],
  data() {
    return {
      file: null,
      showModal: false
    };
  },
  methods: {
    handleOk() {
      let form = document.getElementById("post");
      form = new FormData(form);
      if (this.file) {
        form.append("image", this.file);
      }
      this.$emit("handeOk", form);
    },
    handleCancel(e) {
      e.preventDefault();
      this.$emit("handleCancel");
    }
  },
  watch: {
    complete(complete) {
      if (complete) {
        this.file = null;
        document.getElementById("body").value = null;
      }
    },
    errors(errors) {},
    show(show) {
      this.showModal = show;
    }
  }
};
</script>
