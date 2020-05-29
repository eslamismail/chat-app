<template>
  <div>
    <b-img @click.prevent="showModal()" :src="image" thumbnail center alt="Responsive image"></b-img>
    <a-modal
      :visible="visible"
      @ok="handleOk"
      @cancel="handleCancel"
      class="full-page"
      :footer="null"
      :header="null"
    >
      <b-img :src="image" thumbnail center alt="Responsive image"></b-img>
    </a-modal>
  </div>
</template>
<script>
export default {
  props: ["image"],
  data() {
    return {
      visible: false
    };
  },
  methods: {
    showModal() {
      this.visible = true;
    },
    handleOk(e) {
      this.ModalText = "The modal will be closed after two seconds";
      this.confirmLoading = true;
      setTimeout(() => {
        this.visible = false;
        this.confirmLoading = false;
      }, 2000);
    },
    handleCancel(e) {
      this.visible = false;
    }
  }
};
</script>
<style >
.full-page {
  padding: 0;
  margin: 0;
  width: 100%;
  height: 100%;
  background: red;
}
</style>
