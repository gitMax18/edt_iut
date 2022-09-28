<template>
    <RouterView></RouterView>
</template>

<script>
export default {
    name: "App",
};
</script>

<style lang="scss">
:root {
    font-size: 62.5%;
}
body {
    font-size: 1.6rem;
}
*,
::before,
::after {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}
p {
    @include font-family-regular;
}

h1,
h2,
h3,
h4,
h5,
h6 {
    @include font-family-bold;
}

body {
    background-color: $color-secondary;
}

.fc-event-main {
    padding: 3px;
}

.modal-title {
    font-size: 2rem;
    color: $color-primary;
}

.section-title {
    font-size: 2.5rem;
    color: $color-primary;
    border-bottom: 2px solid $color-primary;
    margin-bottom: 1.5rem;
}

.input-container {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
}

label {
    font-size: 1.5rem;
    margin-bottom: 1rem;
}

input,
option,
select {
    font-size: 1.5rem;
    padding: 0.5rem;
    width: 100%;
}

input[type="color"] {
    height: 5rem;
}

.section-form {
    height: 99vh;
    display: flex;
    justify-content: center;
    align-items: center;
    form {
        padding: 3rem;
        width: 50%;
        min-width: 35rem;
        background-color: white;
    }
}

.fc-theme-standard {
    max-height: 95vh !important;
}

.fc-button {
    background-color: $color-primary !important;
    border: 1px solid white !important;
    &-active {
        background-color: $color-primary-light !important;
        border: none !important;
        outline: none !important;
    }
    &:hover {
        background-color: $color-primary-light !important;
    }
}
</style>
