<template>
    <div class="container">
        <h2 class="title">Entrer les données du cour</h2>
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
            <button @click.prevent="handleSubmit">Valider</button>
        </form>
    </div>
</template>

<script>
import { classroomType } from "@/etc.js";
import useFetch from "../mixins/useFetch.vue";
export default {
    name: "AddEventModal",
    emits: ["handleSubmit"],
    mixins: [useFetch],
    props: {
        selectedDates: Object,
        calendarApi: Object,
        eventData: Object,
    },
    data() {
        return {
            course: "",
            teacher: "",
            classroomTypes: classroomType,
            choosenClassroom: "",
        };
    },
    methods: {
        async handleSubmit() {
            const newEvent = {
                title: this.course,
                start: this.selectedDates.startStr,
                end: this.selectedDates.endStr,
                extendedProps: {
                    classroom: this.choosenClassroom,
                    teacher: this.teacher,
                },
            };

            await this.fetchApi("event", "POST", this.transformEventToApiEvent(newEvent));

            if (this.isFetchError) {
                console.log(this.errorMessageApi);
            } else {
                this.calendarApi.addEvent({ ...newEvent, id: this.dataApi.eventId });
            }

            this.calendarApi.unselect();
            this.$emit("handleSubmit");
        },
    },
    mounted() {
        console.log(this.selectedDates);
    },
};
</script>

<style scoped>
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
