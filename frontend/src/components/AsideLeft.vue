<template>
    <div class="aside-left-container">
        <div class="auth-container">
            <AppButton message="Connexion" @handleClick="handleClickLogin" />
            <AppButton message="S'enregistrer" @handleClick="handleClickRegister" />
        </div>
        <div class="aside-item" @click="handleClickPlanning">Emplois du temps</div>
        <template v-if="isPlanningShow">
            <div v-for="(value, key) in formations" :key="key" class="course-container">
                <div class="course-sector" @click="handleShowSector(key)">{{ key }}</div>
                <ul class="course-list" v-if="value.isShow">
                    <li class="course-item" v-for="(formation, index) in value.formations" @click="handleClickFormation(formation)" :key="index">
                        {{ formation.name }}
                    </li>
                </ul>
            </div>
        </template>
        <div class="aside-item" @click="handleClickIndisponibility">Mes indisponibilitées</div>
        <div class="aside-item" @click="handleClickPreferences">Mes préférences</div>
        <div class="aside-item" @click="handleClickReporting">Reporting</div>
        <div class="aside-item" @click="handleClickAddFormation">Ajouter formation</div>
        <div class="aside-item" @click="handleClickAddCourse">Ajouter cour</div>
    </div>
</template>

<script>
import AppButton from "./AppButton.vue";
import useFetch from "../mixins/useFetch.vue";
export default {
    name: "asideLeft",
    mixins: [useFetch],
    emits: ["handleSelectFormation", "handleAddFormation", "handleAddCourse", "handleShowReporting"],
    props: {
        formations: {
            type: Object,
        },
    },
    components: {
        AppButton,
    },
    data() {
        return {
            isPlanningShow: false,
        };
    },
    methods: {
        handleClickLogin() {
            console.log("login");
        },
        handleClickRegister() {
            console.log("register");
        },
        handleClickPlanning() {
            this.isPlanningShow = !this.isPlanningShow;
        },
        handleClickFormation(formation) {
            this.$emit("handleSelectFormation", formation);
        },
        handleClickIndisponibility() {
            console.log("indisponibility");
        },
        handleClickPreferences() {
            console.log("preference");
        },
        handleClickReporting() {
            this.$emit("handleShowReporting");
        },
        handleShowSector(formationKey) {
            this.formations[formationKey].isShow = !this.formations[formationKey].isShow;
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
        handleClickAddFormation() {
            this.$emit("handleAddFormation");
        },
        handleClickAddCourse() {
            this.$emit("handleAddCourse");
        },
    },
};
</script>

<style lang="scss" scoped>


.aside-left-container {
    padding: 1rem;
}
.auth-container {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
}

.aside-item {
    @include font-family-bold;
    position: relative;
    width: fit-content;
    font-weight: bold;
    color: $color-primary;
    margin: 0.5rem;
    cursor: pointer;
    &:hover {
        color: $color-primary-dark;
    }
}

.course-container {
    margin-left: 1rem;
}

.course-list {
    @include font-family-regular;
    list-style-type: circle;
    padding-left: 2rem;
}

.course-sector {
    @include font-family-bold;
    color: $color-primary;
    cursor: pointer;
    &:hover {
        color: $color-primary-dark;
    }
}
.course-item {
    @include font-family-regular;
    color: $color-primary-dark;
    cursor: pointer;
    &:hover {
        color: $color-primary;
    }
}
</style>
