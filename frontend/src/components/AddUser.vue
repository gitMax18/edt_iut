<template>
    <div class="section-form">
        <form>
            <h1 class="section-title">Ajouter un utilisateur</h1>
            <div class="input-container">
                <label for="name">Prénom</label>
                <input type="text" name="name" id="name" v-model="firstname" />
            </div>
            <div class="input-container">
                <label for="sector">Nom</label>
                <input type="text" name="sector" id="sector" v-model="lastname" />
            </div>
            <div class="input-container">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" v-model="email" />
            </div>
            <AppButton @handleClick="handleClick">Valider</AppButton>
        </form>
    </div>
</template>

<script>
import useFetch from "../mixins/useFetch.vue";
import useToast from "../mixins/useToast.vue";
import AppButton from "./AppButton.vue";
export default {
    name: "AddUser",
    mixins: [useFetch, useToast],
    components: {
        AppButton,
    },
    data() {
        return {
            firstname: "",
            lastname: "",
            email: "",
        };
    },
    methods: {
        async handleClick() {
            await this.fetchApi("user", "POST", {
                firstname: this.firstname,
                lastname: this.lastname,
                email: this.email,
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
            this.firstname = "";
            this.lastname = "";
            this.email = "";
        },
    },
};
</script>

<style lang="scss" scoped></style>
