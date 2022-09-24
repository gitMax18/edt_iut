<template>
    <main>
        <h1>Gestion des imports massifs</h1>
        <div>
            <h2>Import des professeurs</h2>
            <form ref="usersForm">
                <input type="file" name="users" />
                <button @click.prevent="handleImport($refs.usersForm, 'user/import')">Importer</button>
            </form>
        </div>
        <div>
            <h2>Import des formations</h2>
            <form ref="formationsForm">
                <input type="file" name="formations" />
                <button @click.prevent="handleImport($refs.formationsForm, 'formation/import')">Importer</button>
            </form>
        </div>
        <div>
            <h2>Import des cours</h2>
            <form ref="coursForm">
                <input type="file" name="courses" />
                <button @click.prevent="handleImport($refs.coursForm, 'course/import')">Importer</button>
            </form>
        </div>
    </main>
</template>

<script>
import useFetch from "../mixins/useFetch.vue";
import useToast from "../mixins/useToast.vue";
export default {
    name: "MassiveImport",
    mixins: [useFetch, useToast],

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

<style lang="scss" scoped></style>
