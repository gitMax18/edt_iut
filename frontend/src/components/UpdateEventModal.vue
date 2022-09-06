<template>
    <div class="container">
        <h2 class="title">Modifier les données du cour</h2>
        <form>
            <div class="input-container">
                <label for="course">cour : </label>
                <select name="course" id="course" v-model="course">
                    <option :value="course">{{ course.name }}</option>
                    <option v-for="course in formationCourses" :key="course.id" :value="course">{{ course.name }}</option>
                </select>
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
                    <option :value="teacher">{{ teacher.firstname }}</option>
                    <option v-for="teacher in course.teachers" :key="teacher.id" :value="teacher">{{ teacher.firstname }}</option>
                </select>
            </div>
            <button @click.prevent="handleUpdate">Update</button>
            <button @click.prevent="handleDelete">Delete</button>
        </form>
        <button class="exit-btn" @click="$emit('handleCloseModal')">X</button>
    </div>
</template>

<script>
import { classroomType } from "@/etc.js";
import useFetch from "../mixins/useFetch.vue";
import useToast from "../mixins/useToast.vue";
export default {
    name: "updateEventModal",
    mixins: [useFetch, useToast],
    emits: ["handleCloseModal"],
    props: {
        selectedDates: Object,
        calendarApi: Object,
        formationCourses: Object,
    },
    data() {
        return {
            course: this.selectedDates.event.extendedProps.course,
            teacher: this.selectedDates.event.extendedProps.teacher,
            choosenClassroom: this.selectedDates.event.extendedProps.classroom,
            classroomTypes: classroomType,
        };
    },
    methods: {
        async handleUpdate() {
            this.selectedDates.event.setProp("title", this.course.name);
            this.selectedDates.event.setExtendedProp("classroom", this.choosenClassroom);
            this.selectedDates.event.setExtendedProp("teacher", this.teacher);
            this.selectedDates.event.setExtendedProp("course", this.course);

            this.$emit("handleCloseModal");
        },

        async handleDelete() {
            await this.fetchApi(`event/${this.selectedDates.event.id}`, "DELETE");

            if (this.isFetchError) {
                this.toast.error(this.errorMessageApi);
                return;
            }
            this.toast.success(this.dataApi.message);
            this.selectedDates.event.remove();

            this.$emit("handleCloseModal");
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

.exit-btn {
    position: absolute;
    top: 0;
    right: 0;
    padding: 0.5rem;
}
</style>
