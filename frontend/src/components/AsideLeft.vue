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
    </div>
</template>

<script>
import AppButton from "./AppButton.vue";
import { formationMMI, formationGEA } from "../etc";
import useFetch from "../mixins/useFetch.vue";
export default {
    name: "asideLeft",
    mixins: [useFetch],
    emits: ["handleSelectFormation"],
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
            // courses: [
            //     {
            //         isShow: false,
            //         sector: "MMI",
            //         formations: formationMMI,
            //     },
            //     {
            //         isShow: false,
            //         sector: "GEA",
            //         formations: formationGEA,
            //     },
            // ],
            // formations: {},
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
            console.log("reporting");
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
    },
    // async mounted() {
    //     await this.fetchApi("formation");
    //     if (this.isFetchError) {
    //         console.log(this.errorMessageApi);
    //         return;
    //     }
    //     this.formatFormations(this.dataApi.formations);
    // },
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
    list-style-type: circle;
    padding-left: 2rem;
}

.course-sector {
    color: $color-primary;
    cursor: pointer;
    font-weight: bold;
    &:hover {
        color: $color-primary-dark;
    }
}
.course-item {
    color: $color-primary;
    cursor: pointer;
    &:hover {
        color: $color-primary-dark;
    }
}
</style>
