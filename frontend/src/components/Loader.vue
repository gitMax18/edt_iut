<template>
    <div class="container loading-text">
        <h2>Chargement...</h2>
    </div>
</template>

<script>
export default {
    name: "Loader",
};
</script>

<style lang="scss" scoped>
.container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1000;
}

.loading-text {
    @include font-family-light;
}
</style>
