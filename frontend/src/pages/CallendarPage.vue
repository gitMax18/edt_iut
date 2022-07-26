<template>
    <div class="main-container">
        <AsideLeft class="left" @handleSelectFormation="handleSelectFormation" :formations="formations" />
        <Callendar class="right" :formation="formation" v-if="formation" />
        <Loader v-if="isLoadingApi" />
    </div>
</template>

<script>
import Callendar from "../components/Callendar.vue";
import AsideLeft from "../components/AsideLeft.vue";
import useFetch from "../mixins/useFetch.vue";
import Loader from "../components/Loader.vue";
export default {
    name: "App",
    mixins: [useFetch],
    components: {
        Callendar,
        AsideLeft,
        Loader,
    },
    data() {
        return {
            formations: {},
            formation: null,
        };
    },
    methods: {
        handleSelectFormation(formation) {
            this.formation = formation;
        },
        formatFormations(formationsArray) {
            formationsArray.forEach((formation) => {
                if (!this.formations.hasOwnProperty(formation.sector)) {
                    this.formations[formation.sector] = {
                        isShow: false,
                        formations: [],
                    };
                }
                this.formations[formation.sector].formations.push(formation);
            });
        },
    },
    async mounted() {
        await this.fetchApi("formation");
        if (this.isFetchError) {
            console.log(this.errorMessageApi);
            return;
        }
        // console.log(this.dataApi);
        this.formatFormations(this.dataApi.formations);
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
    border-right: 2px solid black;
}
.right {
    width: 80%;
}
</style>
