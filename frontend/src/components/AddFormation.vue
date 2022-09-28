<template>
    <div class="section-form">
        <form>
            <h1 class="section-title">Ajouter une formation</h1>
            <div class="input-container">
                <label for="name">Nom</label>
                <input type="text" name="name" id="name" v-model="name" />
            </div>
            <div class="input-container">
                <label for="sector">Secteur</label>
                <input type="text" name="sector" id="sector" v-model="sector" />
            </div>
            <div class="input-container">
                <label for="year">Année</label>
                <input type="number" name="year" id="year" v-model="year" />
            </div>
            <div class="input-container">
                <label for="groupeNb">Nombre de groupe</label>
                <input type="number" name="groupeNb" id="groupeNb" v-model="groupeNb" />
            </div>
            <div class="input-container">
                <label for="responsable">Responsable</label>
                <select name="responsable" id="responsable" v-model="responsable">
                    <option value="">Choisissez un responsable</option>
                    <option v-for="user in appUsers" :key="user.id" :value="user.id">{{ user.firstname + " " + user.lastname }}</option>
                </select>
            </div>
            <!-- <button @click.prevent="handleClick">Valider</button> -->
            <AppButton @handleClick="handleClick">Valider</AppButton>
        </form>
    </div>
</template>

<script>
import useFetch from "../mixins/useFetch.vue";
import useToast from "../mixins/useToast.vue";
import AppButton from "./AppButton.vue";
export default {
    name: "AddFormation",
    mixins: [useFetch, useToast],
    components: {
        AppButton,
    },
    data() {
        return {
            name: "",
            sector: "",
            year: "",
            responsable: "",
            appUsers: [],
            responsable: null,
            groupeNb: 0,
        };
    },
    methods: {
        async handleClick(e) {
            await this.fetchApi("formation", "POST", {
                name: this.name,
                sector: this.sector,
                year: this.year,
                responsable: this.responsable,
                groupeNb: this.groupeNb,
            });

            if (this.isFetchError) {
                this.toast.error(this.errorMessageApi);
                return;
            }
            console.log("data", this.dataApi);
            this.toast.success(this.dataApi.message);
            this.resetData();
        },
        resetData() {
            this.name = "";
            this.sector = "";
            this.year = "";
            this.responsable = "";
            this.groupeNb = 0;
        },
    },
    async mounted() {
        await this.fetchApi("user");

        if (this.isFetchError) {
            console.log(this.errorMessageApi);
            return;
        }
        this.appUsers = this.dataApi.users;
        console.log(this.dataApi.message);
    },
};
</script>

<style lang="scss" scoped></style>
