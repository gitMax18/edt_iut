<template>
    <form>
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
            <label for="responsable">Responsable</label>
            <select name="responsable" id="responsable" v-model="responsable">
                <option value="">Choisissez un responsable</option>
                <option v-for="user in appUsers" :key="user.id" :value="user.id">{{ user.firstname + " " + user.lastname }}</option>
            </select>
        </div>
        <button @click.prevent="handleClick">Valider</button>
    </form>
</template>

<script>
import useFetch from "../mixins/useFetch.vue";
import { useToast } from "vue-toastification";
export default {
    name: "AddFormation",
    mixins: [useFetch],
    data() {
        return {
            name: "",
            sector: "",
            year: "",
            responsable: "",
            appUsers: [],
            responsable: null,
            toast: useToast(),
        };
    },
    methods: {
        async handleClick(e) {
            await this.fetchApi("formation", "POST", {
                name: this.name,
                sector: this.sector,
                year: this.year,
                responsable: this.responsable,
            });

            if (this.isErrorMessageApi) {
                this.toast(this.errorMessageApi);
                return;
            }
            console.log("data", this.dataApi);
            this.toast.success(this.dataApi.message);
        },
    },
    async mounted() {
        await this.fetchApi("user");

        if (this.isFetchError) {
            console.log(this.errorMessageApi);
            return;
        }

        this.appUsers = this.dataApi.users;
    },
};
</script>

<style lang="scss" scoped></style>
