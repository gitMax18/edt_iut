<template>
    <div class="main-container">
        <AsideLeft class="left" />
        <Callendar class="right" />
    </div>
</template>

<script>
import Callendar from "./components/Callendar.vue";
import AsideLeft from "./components/AsideLeft.vue";
export default {
    name: "App",
    components: {
        Callendar,
        AsideLeft,
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

.main-container {
    display: flex;
}

.left {
    width: 20%;
}
.right {
    width: 80%;
}
</style>
