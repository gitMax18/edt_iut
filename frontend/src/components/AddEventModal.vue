<template>
    <div class="background-modal">
        <div class="container">
            <h2 class="modal-title">Entrer les données du cours</h2>
            <form>
                <div class="input-container">
                    <label for="course">Cours : </label>
                    <select name="course" id="course" v-model="course">
                        <option value="">Choisissez un type de cours</option>
                        <option v-for="course in formationCourses" :key="course.id" :value="course">{{ course.groupe ? `${course.name} groupe : ${course.groupe}` : course.name }}</option>
                    </select>
                </div>
                <div class="input-container">
                    <label for="classroomType">Type de salle : </label>
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
                        <option>Non choisie</option>
                        <option v-for="teacher in course.teachers" :key="teacher.id" :value="teacher">{{ teacher.firstname }}</option>
                    </select>
                </div>
                <div class="input-container disableVerification-container">
                    <label for="disableVerification">Désactiver la vérification</label>
                    <input type="checkbox" name="isDisableVerification" id="isDisableVerification" v-model="isDisableVerification" />
                </div>
                <AppButton @handleClick="handleSubmit">Valider</AppButton>
            </form>
            <!-- <button class="exit-btn" @click="$emit('handleCloseModal')">X</button> -->
            <!-- <CloseButton class="close-btn" @handleClick="$emit('handleCloseModal')" /> -->
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
    name: "AddEventModal",
    emits: ["handleSubmit"],
    components: {
        AppButton,
    },
    mixins: [useFetch, useToast],
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
            isDisableVerification: false,
        };
    },
    methods: {
        async handleSubmit() {
            const newEvent = {
                title: this.course.name,
                start: this.selectedDates.startStr,
                end: this.selectedDates.endStr,
                isDisableVerification: this.isDisableVerification,
                extendedProps: {
                    classroom: this.choosenClassroom,
                    teacher: this.teacher,
                    formation: this.formation,
                    course: this.course,
                },
            };
            await this.fetchApi("event", "POST", this.transformEventToApiEvent(newEvent));

            if (this.isFetchError) {
                this.toast.error(this.errorMessageApi);
                return;
            }

            this.calendarApi.addEvent({ ...newEvent, id: this.dataApi.eventId }, true);
            this.toast.success(this.dataApi.message);
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

.disableVerification-container {
    display: flex;
    justify-content: center;
    align-items: center;
}
</style>
