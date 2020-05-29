<template>
  <div>
    <span v-for="(item, index) in errors" :key="index" class="text-danger form-error">{{item}}</span>
  </div>
</template>

<script>
export default {
  props: ["errors"],
  created() {}
};
</script>
<style scoped>
.form-error {
  margin: 0;
  padding: 0;
  display: block;
}
</style>
