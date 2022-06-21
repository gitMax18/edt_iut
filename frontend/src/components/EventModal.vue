<template>
    <div class="container">
        <h2 class="title">Entrer les données du cour</h2>
        <form>
            <div class="input-container">
                <label for="course">Cour: </label>
                <input type="text" id="course" v-model="course" />
            </div>
            <div class="input-container">
                <label for="classroom">Salle: </label>
                <input type="text" id="classroom" v-model="classroom" />
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
export default {
    name: "EventModal",
    emits: ["handleSubmit"],
    props: {
        selectedData: Object,
        calendarApi: Object,
    },
    data() {
        return {
            course: "",
            classroom: "",
            teacher: "",
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
                    classroom: this.classroom,
                    teacher: this.teacher,
                },
            });
            this.$emit("handleSubmit");
        },
    },
    mounted() {
        // console.log(this.selectedData);
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
