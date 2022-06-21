<template>
    <button class="appButton" @click="handleClick">{{ message }}</button>
</template>

<script>
export default {
    name: "AppButton",
    props: {
        message: String,
    },
    methods: {
        handleClick() {
            this.$emit("handleClick");
        },
    },
};
</script>

<style lang="scss" scoped>
.appButton {
    display: block;
    padding: 1rem 1.5rem;
    border-radius: 1rem;
    margin: 0.5rem;
    outline: none;
    border: none;
    background-color: $color-primary;
    color: white;
    cursor: pointer;
    &:hover {
        background-color: $color-primary-dark;
    }
}
</style>
