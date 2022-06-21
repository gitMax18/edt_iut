<template>
    <div class="aside-left-container">
        <div class="auth-container">
            <AppButton message="Connexion" @handleClick="handleClickLogin" />
            <AppButton message="S'enregistrer" @handleClick="handleClickRegister" />
        </div>
        <div class="aside-item" @click="handleClickPlanning">Emplois du temps</div>
        <template v-if="isPlanningShow">
            <div v-for="(course, index) in courses" :key="index" class="course-container">
                <div class="course-sector" @click="course.isPlanningShow = !course.isPlanningShow">{{ course.sector }}</div>
                <ul class="course-list" v-if="course.isPlanningShow">
                    <li class="course-item" @click="handleClickFormation({ formation, sector: course.sector })" v-for="(formation, index) in course.formations" :key="index">
                        {{ formation }}
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
export default {
    name: "asideLeft",
    components: {
        AppButton,
    },
    data() {
        return {
            isPlanningShow: false,
            courses: [
                {
                    isShow: false,
                    sector: "MMI",
                    formations: ["BUT 1", "BUT 2", "BUT 3", "LP MIAW", "LP CAN"],
                },
                {
                    isShow: false,
                    sector: "GEA",
                    formations: ["BUT 1", "BUT 2", "BUT 3", "LP RC", "LP CD", "LP CSP"],
                },
            ],
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
        handleClickFormation(formationData) {
            console.log(formationData.sector + "/" + formationData.formation);
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
