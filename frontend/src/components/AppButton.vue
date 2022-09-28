<template>
    <button class="appButton" :class="{ warning: isWarning }" @click.prevent="handleClick">
        <slot />
    </button>
</template>

<script>
export default {
    name: "AppButton",
    props: {
        isWarning: {
            type: Boolean,
            default: false,
        },
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
    padding: 0.8rem 1.3rem;
    border-radius: $border-radius;
    margin: 0.5rem 0;
    outline: none;
    border: 1px solid transparent;
    background-color: $color-primary;
    color: white;
    cursor: pointer;
    width: 100%;
    font-size: 1.3rem;
    font-weight: bold;
    transition: box-shadow 0.2s ease;

    &:hover {
        background-color: white;
        color: $color-primary;
        border: 1px solid $color-primary;
        box-shadow: $color-primary 0px 1px 4px;
    }
}

.warning {
    background-color: red;
    &:hover {
        color: red;
        border: 1px solid red;
        box-shadow: red 0px 1px 4px;
    }
}
</style>
