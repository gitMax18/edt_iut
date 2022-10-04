<template>
    <div class="background-modal">
        <div class="container">
            <h2 class="modal-title">Modifier les données du cours</h2>
            <form>
                <div class="input-container">
                    <label for="course">Cours : </label>
                    <select name="course" id="course" v-model="course">
                        <option :value="course">{{ course.groupe ? `${course.name} groupe : ${course.groupe}` : course.name }}</option>
                        <option v-for="course in formationCourses" :key="course.id" :value="course">{{ course.groupe ? `${course.name} groupe : ${course.groupe}` : course.name }}</option>
                    </select>
                </div>
                <div class="input-container">
                    <label for="classroomType">Type de salle: </label>
                    <select name="classroomType" id="classroomType" v-model="choosenClassroom">
                        <option value="Non désigné">Non désigné</option>
                        <optgroup v-for="(type, index) in classroomTypes" :key="type" :label="index">
                            <option v-for="place in type" :key="place" :value="`${index} ${place}`">{{ place }}</option>
                        </optgroup>
                    </select>
                </div>
                <div class="input-container" v-if="course">
                    <label for="teacher">Choisissez un professeur</label>
                    <select name="teacher" id="teacher" v-model="teacher">
                        <option v-if="teacher?.firstname" :value="teacher">{{ teacher.firstname + " " + teacher.lastname }}</option>
                        <option>Non choisie</option>
                        <option v-for="teacher in course.teachers" :key="teacher.id" :value="teacher">{{ teacher.firstname + " " + teacher.lastname }}</option>
                    </select>
                </div>
                <div class="modal-btns">
                    <AppButton @handleClick="handleUpdate">Modifier</AppButton>
                    <AppButton isWarning="true" @handleClick="handleDelete">Supprimer</AppButton>
                </div>

                <!-- <button @click.prevent="handleUpdate">Update</button>
                <button @click.prevent="handleDelete">Delete</button> -->
            </form>
            <div class="close-btn" @click.prevent="$emit('handleCloseModal')">
                <svg xmlns="http://www.w3.org/2000/svg" clip-rule="evenodd" fill-rule="evenodd" stroke-linejoin="round" stroke-miterlimit="2" viewBox="0 0 24 24">
                    <path
                        d="m12.002 2.005c5.518 0 9.998 4.48 9.998 9.997 0 5.518-4.48 9.998-9.998 9.998-5.517 0-9.997-4.48-9.997-9.998 0-5.517 4.48-9.997 9.997-9.997zm0 8.933-2.721-2.722c-.146-.146-.339-.219-.531-.219-.404 0-.75.324-.75.749 0 .193.073.384.219.531l2.722 2.722-2.728 2.728c-.147.147-.22.34-.22.531 0 .427.35.75.751.75.192 0 .384-.073.53-.219l2.728-2.728 2.729 2.728c.146.146.338.219.53.219.401 0 .75-.323.75-.75 0-.191-.073-.384-.22-.531l-2.727-2.728 2.717-2.717c.146-.147.219-.338.219-.531 0-.425-.346-.75-.75-.75-.192 0-.385.073-.531.22z"
                        fill-rule="nonzero"
                    />
                </svg>
            </div>
        </div>
    </div>
</template>

<script>
import { classroomType } from "@/etc.js";
import useFetch from "../mixins/useFetch.vue";
import useToast from "../mixins/useToast.vue";
import AppButton from "./AppButton.vue";
export default {
    name: "updateEventModal",
    mixins: [useFetch, useToast],
    components: {
        AppButton,
    },
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
            await this.fetchApi(`event/${this.selectedDates.event.id}`, "PUT", {
                course: this.course.id,
                teacher: this.teacher?.id,
                classroom: this.choosenClassroom,
                startAt: this.selectedDates.event.startStr,
                endAt: this.selectedDates.event.endStr,
            });
            if (this.isFetchError) {
                this.toast.error(this.errorMessageApi);
                this.selectedDates.revert();
                return;
            }

            this.toast.success(this.dataApi.message);
            this.selectedDates.event.setProp("title", this.course.name);
            this.selectedDates.event.setExtendedProp("classroom", this.choosenClassroom);
            this.selectedDates.event.setExtendedProp("course", this.course);
            this.selectedDates.event.setExtendedProp("teacher", this.teacher);

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
.background-modal {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.67);
    z-index: 100;
    backdrop-filter: blur(2px);
}

.container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: snow;
    padding: 2rem;
    z-index: 4;
    border-radius: 0.5rem;
    width: 40rem;
}

.modal-title {
    margin: 0;
}

.input-container {
    margin: 1rem 0;
}

.modal-btns {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.close-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    width: fit-content;
    z-index: 10000;
    width: 2.5rem;
    height: 2.5rem;
    cursor: pointer;
    svg {
        fill: red;
        z-index: 100000;
    }
}
</style>
