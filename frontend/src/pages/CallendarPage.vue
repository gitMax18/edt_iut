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
        <div class="right">
            <Callendar :formation="formation" v-if="formation && !isAddFormation && !isAddCourse && !isAddUser && !isShowMassiveImport" :isShowReporting="isShowReporting" />
            <AddFormation v-if="isAddFormation" />
            <AddCourse :formations="formations" v-if="isAddCourse" />
            <AddUser v-if="isAddUser" />
            <MassiveImport v-if="isShowMassiveImport" />
            <Loader v-if="isLoadingApi" />
        </div>
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
            this.isShowReporting = false;
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
    height: 100vh;
    overflow-y: auto;
}

.left {
    width: 20%;
    max-width: 25rem;
    border-right: 2px solid black;
}
.right {
    width: 80%;
    position: relative;
    padding: 0.5rem;
    flex-grow: 1;
    /* background: #283c86;
    background: -webkit-linear-gradient(to right, #45a247, #283c86);
    background: linear-gradient(180deg, #45a247, #283c86); */
    background-color: #0093e9;
    background-image: linear-gradient(160deg, #0093e9 0%, #80d0c7 100%);
}
</style>
