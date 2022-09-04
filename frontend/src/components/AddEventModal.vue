<template>
    <div class="container">
        <h2 class="title">Entrer les données du cour</h2>
        <form>
            <div class="input-container">
                <label for="course">cour : </label>
                <select name="course" id="course" v-model="course">
                    <option value="">Choisissez un type de cour</option>
                    <option v-for="course in formationCourses" :key="course.id" :value="course">{{ course.name }}</option>
                </select>
                <!-- <label for="course">Cour: </label>
                <input type="text" id="course" v-model="course" /> -->
            </div>
            <div class="input-container">
                <label for="classroomType">Type salle: </label>
                <select name="classroomType" id="classroomType" v-model="choosenClassroom">
                    <option value="">Choisissez un type de salle</option>
                    <option v-for="type in classroomTypes" :key="type" :value="type">{{ type }}</option>
                </select>
            </div>
            <div class="input-container" v-if="course">
                <select name="teacher" id="teacher" v-model="teacher">
                    <option value="">Choisissez un professeur</option>
                    <option v-for="teacher in course.teachers" :key="teacher.id" :value="teacher">{{ teacher.firstname }}</option>
                </select>
            </div>
            <button @click.prevent="handleSubmit">Valider</button>
        </form>
        <button class="exit-btn" @click="$emit('handleCloseModal')">X</button>
    </div>
</template>

<script>
import { classroomType } from "@/etc.js";
import useFetch from "../mixins/useFetch.vue";
export default {
    name: "AddEventModal",
    emits: ["handleSubmit"],
    mixins: [useFetch],
    emits: ["handleCloseModal"],
    props: {
        selectedDates: Object,
        calendarApi: Object,
        eventData: Object,
        formation: Object,
        formationCourses: Array,
    },

    data() {
        return {
            course: {},
            teacher: {},
            classroomTypes: classroomType,
            choosenClassroom: "",
        };
    },
    methods: {
        async handleSubmit() {
            const newEvent = {
                title: this.course.name,
                start: this.selectedDates.startStr,
                end: this.selectedDates.endStr,
                extendedProps: {
                    classroom: this.choosenClassroom,
                    teacher: this.teacher,
                    formation: this.formation,
                    course: this.course,
                },
            };
            await this.fetchApi("event", "POST", this.transformEventToApiEvent(newEvent));

            if (this.isFetchError) {
                console.log(this.errorMessageApi);
                return;
            }

            if (this.dataApi.status === "error") {
                console.log(this.dataApi.message);
                return;
            }

            this.calendarApi.addEvent({ ...newEvent, id: this.dataApi.eventId }, true);

            this.calendarApi.unselect();
            this.$emit("handleCloseModal");
        },
    },
    mounted() {
        // console.log(this.selectedDates);
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

.exit-btn {
    position: absolute;
    top: 0;
    right: 0;
    padding: 0.5rem;
}
</style>
