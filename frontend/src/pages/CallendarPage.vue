<template>
    <div class="main-container">
        <AsideLeft
            class="left"
            @handleSelectFormation="handleSelectFormation"
            :formations="formations"
            :isAddFormation="isAddFormation"
            @handleAddFormation="handleAddFormation"
            @handleAddCourse="handleAddCourse"
            @handleAddUser="handleAddUser"
            @handleShowReporting="handleShowReporting"
            @handleMassiveImport="handleMassiveImport"
        />
        <Callendar class="right" :formation="formation" v-if="formation && !isAddFormation && !isAddCourse && !isShowMassiveImport" :isShowReporting="isShowReporting" />
        <AddFormation class="right" v-if="isAddFormation" />
        <AddCourse class="right" :formations="formations" v-if="isAddCourse" />
        <AddUser class="right" v-if="isAddUser" />
        <MassiveImport class="right" v-if="isShowMassiveImport" />
        <Loader v-if="isLoadingApi" />
    </div>
</template>

<script>
import Callendar from "../components/Callendar.vue";
import AsideLeft from "../components/AsideLeft.vue";
import useFetch from "../mixins/useFetch.vue";
import useToast from "../mixins/useToast.vue";
import Loader from "../components/Loader.vue";
import AddFormation from "../components/AddFormation.vue";
import AddCourse from "../components/AddCourse.vue";
import MassiveImport from "../components/MassiveImport.vue";
import AddUser from "../components/AddUser.vue";
export default {
    name: "App",
    mixins: [useFetch, useToast],
    components: {
        Callendar,
        AsideLeft,
        Loader,
        AddFormation,
        AddCourse,
        MassiveImport,
        AddUser,
    },
    data() {
        return {
            formations: {},
            formation: null,
            isAddFormation: false,
            isAddCourse: false,
            isAddUser: false,
            isShowReporting: false,
            isShowMassiveImport: false,
        };
    },
    methods: {
        handleSelectFormation(formation) {
            this.isAddCourse = false;
            this.isAddFormation = false;
            this.isShowMassiveImport = false;
            this.isAddUser = false;
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
            this.isShowMassiveImport = false;
            this.isAddCourse = false;
            this.isAddUser = false;
            this.isAddFormation = true;
        },
        handleAddCourse() {
            this.isAddFormation = false;
            this.isShowMassiveImport = false;
            this.isAddUser = false;
            this.isAddCourse = true;
        },
        handleAddUser() {
            this.isAddFormation = false;
            this.isShowMassiveImport = false;
            this.isAddCourse = false;
            this.isAddUser = true;
        },
        handleShowReporting() {
            this.isShowReporting = !this.isShowReporting;
        },
        handleMassiveImport() {
            this.isAddFormation = false;
            this.isAddCourse = false;
            this.isAddUser = false;
            this.isShowMassiveImport = true;
        },
    },

    async mounted() {
        await this.fetchApi("formation");

        if (this.isFetchError) {
            this.toast.error(this.errorMessageApi);
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
