<template>
    <div class="section-form">
        <form>
            <h1 class="section-title">Ajouter un cours</h1>
            <div class="input-container">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" v-model="name" required />
            </div>
            <div class="input-container">
                <label for="hours">Nombre d'heures</label>
                <input type="number" name="hours" id="hours" v-model="hours" required />
            </div>
            <div class="input-container">
                <label for="teacher">Professeur</label>
                <select name="teacher" id="teacher" v-model="teacher">
                    <option value="">Choisissez un professeur</option>
                    <option v-for="user in appUsers" :key="user.id" :value="user.id">{{ user.firstname + " " + user.lastname }}</option>
                </select>
            </div>
            <div class="input-container">
                <label for="teacher">Type de cour</label>
                <select name="teacher" id="teacher" v-model="teacher">
                    <option v-for="(type, index) in courseType" :key="index" :value="type">{{ type }}</option>
                </select>
            </div>
            <div class="input-container">
                <label for="responsable">Formation</label>
                <select name="formation" id="formation" v-model="formation">
                    <optgroup v-for="(sector, key) in formations" :key="sector" :label="key">
                        <option v-for="course in sector.formations" :key="course.id" :value="course.id">{{ course.name }}</option>
                    </optgroup>
                </select>
            </div>
            <div class="colors-container">
                <div class="input-container">
                    <label for="backgroundColor">Couleur du fond</label>
                    <input type="color" name="backgroundColor" id="backgroundColor" v-model="backgroundColor" />
                </div>
                <div class="input-container">
                    <label for="borderColor">Couleur de la bordure</label>
                    <input type="color" name="borderColor" id="borderColor" v-model="borderColor" required />
                </div>
                <div class="input-container">
                    <label for="textColor">Couleur du texte</label>
                    <input type="color" name="textColor" id="textColor" v-model="textColor" required />
                </div>
            </div>

            <AppButton @handleClick="handleClick">Valider</AppButton>
        </form>
    </div>
</template>

<script>
import useFetch from "../mixins/useFetch.vue";
import useToast from "../mixins/useToast.vue";
import { courseType } from "@/etc.js";
import AppButton from "./AppButton.vue";
export default {
    name: "AddCourse",
    mixins: [useFetch, useToast],
    components: {
        AppButton,
    },
    props: {
        formations: Object,
    },
    data() {
        return {
            name: "",
            hours: "",
            teacher: null,
            formation: null,
            textColor: "#ffffff",
            borderColor: "#000000",
            backgroundColor: "#f1f1f1",
            appUsers: [],
            courseType: courseType,
        };
    },
    methods: {
        async handleClick(e) {
            await this.fetchApi("course/create", "POST", {
                name: this.name,
                hours: this.hours,
                teachers: this.teacher,
                formation: this.formation,
                backgroundColor: this.backgroundColor,
                borderColor: this.borderColor,
                textColor: this.textColor,
            });

            if (this.isFetchError) {
                this.toast.error(this.errorMessageApi);
                return;
            }
            console.log("data", this.dataApi);
            this.toast.success(this.dataApi.message);
        },
    },
    async mounted() {
        await this.fetchApi("user", "GET");

        if (this.isFetchError) {
            console.log(this.errorMessageApi);
            return;
        }
        this.appUsers = this.dataApi.users;
        console.log(this.dataApi.message);
    },
};
</script>

<style lang="scss" scoped>
.colors-container {
    display: flex;
    justify-content: space-evenly;
    align-items: center;
}
</style>
