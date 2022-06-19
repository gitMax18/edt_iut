<template>
    <Callendar />
</template>

<script>
import Callendar from "./components/Callendar.vue";
export default {
    name: "App",
    components: {
        Callendar,
    },
};
</script>

<style lang="css">
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
</style>
