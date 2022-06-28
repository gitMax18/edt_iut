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
            <button @click.prevent="handleSubmit">Valider</button>
            <button @click.prevent="handleDelete">Supprimer</button>
        </form>
    </div>
</template>

<script>
import { classroomType } from "@/etc.js";
export default {
    name: "updateEventModal",
    props: {
        selectedData: Object,
        calendarApi: Object,
    },
    data() {
        return {
            course: this.selectedData.event.title,
            teacher: this.selectedData.event.extendedProps.teacher,
            choosenClassroom: this.selectedData.event.extendedProps.classroom,
            classroomTypes: classroomType,
        };
    },
    methods: {
        handleSubmit() {
            this.calendarApi.unselect();
            this.selectedData.event.setProp("title", this.course);
            this.selectedData.event.setExtendedProp("classroom", this.choosenClassroom);
            this.selectedData.event.setExtendedProp("teacher", this.teacher);
            this.$emit("handleSubmit");
        },

        handleDelete() {
            this.selectedData.event.remove();
            this.$emit("handleSubmit");
        },
    },
    mounted() {},
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
