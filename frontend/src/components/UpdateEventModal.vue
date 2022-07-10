<template>
    <div class="container">
        <h2 class="title">Modifier les données du cour</h2>
        <form>
            <div class="input-container">
                <label for="course">Cour: </label>
                <input type="text" id="course" v-model="course" />
            </div>
            <div class="input-container">
                <label for="classroomType">Type salle: </label>
                <select name="classroomType" id="classroomType" v-model="choosenClassroom">
                    <option value="">Choisissez un type de salle</option>
                    <option v-for="type in classroomTypes" :key="type" :value="type">{{ type }}</option>
                </select>
            </div>
            <div class="input-container">
                <label for="teacher">Professeur: </label>
                <input type="text" id="teacher" v-model="teacher" />
            </div>
            <button @click.prevent="handleUpdate">Valider</button>
            <button @click.prevent="handleDelete">Supprimer</button>
        </form>
    </div>
</template>

<script>
import { classroomType } from "@/etc.js";
import useFetch from "../mixins/useFetch.vue";
export default {
    name: "updateEventModal",
    mixins: [useFetch],
    props: {
        selectedDates: Object,
        calendarApi: Object,
    },
    data() {
        return {
            course: this.selectedDates.event.title,
            teacher: this.selectedDates.event.extendedProps.teacher,
            choosenClassroom: this.selectedDates.event.extendedProps.classroom,
            classroomTypes: classroomType,
        };
    },
    methods: {
        async handleUpdate() {
            await this.fetchApi(`event/${this.selectedDates.event.id}`, "PUT", {
                course: this.course,
                teacher: this.teacher,
                classroom: this.choosenClassroom,
                startAt: this.selectedDates.event.start,
                endAt: this.selectedDates.event.end,
            });

            if (this.isFetchError) {
                console.log(this.errorMessageApi);
            } else {
                console.log(this.dataApi);
                this.selectedDates.event.setProp("title", this.course);
                this.selectedDates.event.setExtendedProp("classroom", this.choosenClassroom);
                this.selectedDates.event.setExtendedProp("teacher", this.teacher);
            }

            this.calendarApi.unselect();

            this.$emit("handleSubmit");
        },

        async handleDelete() {
            console.log(this.selectedDates.event.id);

            await this.fetchApi(`event/${this.selectedDates.event.id}`, "DELETE");

            if (this.isFetchError) {
                console.log(this.errorMessageApi);
            } else {
                console.log(this.dataApi);
                this.selectedDates.event.remove();
            }

            this.$emit("handleSubmit");
        },
    },
};
</script>

<style lang="scss" scoped>
.container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: beige;
    padding: 1.5rem;
    z-index: 4;
    border-radius: 0.5rem;
}

.title {
    margin: 0;
}

.input-container {
    margin: 1rem 0;
}
</style>
