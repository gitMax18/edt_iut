<template>
    <form>
        <div class="input-container">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" v-model="name" />
        </div>
        <div class="input-container">
            <label for="hours">Nombre d'heures</label>
            <input type="number" name="hours" id="hours" v-model="hours" />
        </div>
        <div class="input-container">
            <label for="teacher">Professeur</label>
            <select name="teacher" id="teacher" v-model="teacher">
                <option value="">Choisissez un professeur</option>
                <option v-for="user in appUsers" :key="user.id" :value="user.id">{{ user.firstname + " " + user.lastname }}</option>
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
        <div class="input-container">
            <label for="backgroundColor">Couleur du fond</label>
            <input type="color" name="backgroundColor" id="backgroundColor" v-model="backgroundColor" />
        </div>
        <div class="input-container">
            <label for="borderColor">Couleur de la bordure</label>
            <input type="color" name="borderColor" id="borderColor" v-model="borderColor" />
        </div>
        <div class="input-container">
            <label for="textColor">Couleur du texte</label>
            <input type="color" name="textColor" id="textColor" v-model="textColor" />
        </div>
        <button @click.prevent="handleClick">Valider</button>
    </form>
</template>

<script>
import useFetch from "../mixins/useFetch.vue";
import { useToast } from "vue-toastification";
export default {
    name: "AddCourse",
    mixins: [useFetch],
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
            toast: useToast(),
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

            if (this.isErrorMessageApi) {
                this.toast(this.errorMessageApi);
                return;
            }
            console.log(this.dataApi);
            this.toast.success(this.dataApi);
        },
    },
    async mounted() {
        await this.fetchApi("user", "GET");

        if (this.isFetchError) {
            console.log(this.errorMessageApi);
            return;
        }
        this.appUsers = this.dataApi.users;
        console.log(this.dataApi);
    },
};
</script>

<style lang="scss" scoped></style>
