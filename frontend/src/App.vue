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
</style>
