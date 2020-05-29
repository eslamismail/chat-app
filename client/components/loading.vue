<template lang="html">
  <div class="loading-page" v-if="loading">
  <p>Loading</p>
    <b-spinner type="grow" label="Spinning"/>
    <b-spinner type="grow" label="Spinning"/>
    <b-spinner type="grow" label="Spinning"/>
  </div>
</template>

<script>
export default {
  data: () => ({
    loading: false
  }),
  methods: {
    start() {
      this.loading = true;
    },
    finish() {
      this.loading = false;
    }
  },
  created() {}
};
</script>

<style scoped>
.loading-page {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(255, 255, 255, 0.8);
  text-align: center;
  padding-top: 200px;
  font-size: 30px;
  font-family: sans-serif;
  z-index: 5000;
  overflow: hidden;
}
</style>
