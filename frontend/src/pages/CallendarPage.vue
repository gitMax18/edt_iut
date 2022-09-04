<template>
    <div class="main-container">
        <AsideLeft
            class="left"
            @handleSelectFormation="handleSelectFormation"
            :formations="formations"
            :isAddFormation="isAddFormation"
            @handleAddFormation="handleAddFormation"
            @handleAddCourse="handleAddCourse"
        />
        <Callendar class="right" :formation="formation" v-if="formation && !isAddFormation && !isAddCourse" />
        <AddFormation class="right" v-if="isAddFormation" />
        <AddCourse class="right" :formations="formations" v-if="isAddCourse" />
        <Loader v-if="isLoadingApi" />
    </div>
</template>

<script>
import Callendar from "../components/Callendar.vue";
import AsideLeft from "../components/AsideLeft.vue";
import useFetch from "../mixins/useFetch.vue";
import Loader from "../components/Loader.vue";
import AddFormation from "../components/AddFormation.vue";
import AddCourse from "../components/AddCourse.vue";
export default {
    name: "App",
    mixins: [useFetch],
    components: {
        Callendar,
        AsideLeft,
        Loader,
        AddFormation,
        AddCourse,
    },
    data() {
        return {
            formations: {},
            formation: null,
            isAddFormation: false,
            isAddCourse: false,
        };
    },
    methods: {
        handleSelectFormation(formation) {
            this.isAddCourse = false;
            this.isAddFormation = false;
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
        handleAddFormation() {
            if (this.isAddCourse) this.isAddCourse = false;
            this.isAddFormation = true;
        },
        handleAddCourse() {
            if (this.isAddFormation) this.isAddFormation = false;
            this.isAddCourse = true;
        },
    },
    async mounted() {
        await this.fetchApi("formation");
        if (this.isFetchError) {
            console.log(this.errorMessageApi);
            return;
        }
        this.formatFormations(this.dataApi.formations);
    },
};
</script>

<style lang="css" scoped>
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
