<template>
    <div class="section-form">
        <div class="import-container">
            <h1 class="section-title">Gestion des imports massifs</h1>
            <div class="input-container">
                <h2 class="import-title">Import des professeurs</h2>
                <form ref="usersForm">
                    <input type="file" name="users" />
                    <AppButton @handleClick="handleImport($refs.usersForm, 'user/import')">Importer</AppButton>
                </form>
            </div>
            <div class="input-container">
                <h2 class="import-title">Import des formations</h2>
                <form ref="formationsForm">
                    <input type="file" name="formations" />
                    <AppButton @handleClick="handleImport($refs.formationsForm, 'formation/import')">Importer</AppButton>
                </form>
            </div>
            <div class="input-container">
                <h2 class="import-title">Import des cours</h2>
                <form ref="coursForm">
                    <input type="file" name="courses" />
                    <AppButton @handleClick="handleImport($refs.coursForm, 'course/import')">Importer</AppButton>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import useFetch from "../mixins/useFetch.vue";
import useToast from "../mixins/useToast.vue";
import AppButton from "./AppButton.vue";
export default {
    name: "MassiveImport",
    mixins: [useFetch, useToast],
    components: {
        AppButton,
    },

    methods: {
        async handleImport(form, endUrl) {
            await this.fetchApi(endUrl, "post", new FormData(form), "multipart/form-data");

            if (this.isFetchError) {
                this.toast.error(this.errorMessageApi);
                return;
            }

            this.toast.success(this.dataApi.message);
        },
    },
};
</script>

<style lang="scss" scoped>
.import-title {
    font-size: 1.8rem;
}

.import-container {
    min-width: 35rem;
    width: 50%;
    background-color: white;
    padding: 3rem;
}

form {
    width: 100%;
}
</style>
