<template>
    <div class="main-container">
        <AsideLeft class="left" @handleSelectFormation="handleSelectFormation" />
        <Callendar class="right" :formation="formation" :sector="sector" />
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
    data() {
        return {
            formation: "BUT 1",
            sector: "MMI",
        };
    },
    methods: {
        handleSelectFormation(sector, formation) {
            this.sector = sector;
            this.formation = formation;
            console.log(this.sector, this.formation);
        },
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
