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
import { v4 as uuidv4 } from "uuid";
import { classroomType } from "@/etc.js";
export default {
    name: "AddEventModal",
    emits: ["handleSubmit"],
    props: {
        selectedData: Object,
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
        handleSubmit() {
            this.calendarApi.unselect();
            this.calendarApi.addEvent({
                id: uuidv4(),
                title: this.course,
                start: this.selectedData.startStr,
                end: this.selectedData.endStr,
                extendedProps: {
                    classroom: this.choosenClassroom,
                    teacher: this.teacher,
                },
            });
            this.$emit("handleSubmit");
        },
    },
    mounted() {
        console.log(this.selectedData);
    },
    updated() {
        console.log(this.choosenClassroom);
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
