<style scoped>
.left-icon {
  background-color: #f04e23;
  padding: 0.375rem 0.625rem;
  border-radius: 3.75rem 0 0 3.75rem;
}

img {
  height: 3rem;
}

.content {
  background-color: #ffffff;
  width: 100%;
  padding: 0 1rem;
  border-radius: 0 3.75rem 3.75rem 0;
  text-align: left;
}
</style>

<template>
  <div class="d-flex color-body-text">
    <div class="left-icon d-flex align-items-center">
      <img src="/img/logos/logo-round-white.png" />
    </div>
    <div class="content d-flex align-items-center">
      <slot></slot>
    </div>
  </div>
</template>
