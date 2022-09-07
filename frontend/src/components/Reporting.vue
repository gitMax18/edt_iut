<template>
    <div class="container">
        <div class="course-container" v-for="(course, index) in coursesStat" :key="index">
            <h3 class="course-title">{{ index }}</h3>
            <div>
                <span v-for="(teacher, index) in course.teachers" :key="index">{{ teacher.firstname + " " + teacher.lastname + " " }}</span>
            </div>
            <div><span>Heures réalisées : </span> {{ course.doneCount }}</div>
            <div><span>Heures placées à venir : </span> {{ course.notDoneCount }}</div>
            <div><span>Heures restantes à placer : </span> {{ course.notPlacedCount }}</div>
            <div><span>Nombre total d'heures réalisées : </span> {{ course.totalHours }}</div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Reporting",
    props: {
        courses: Array,
        events: Array,
    },
    computed: {
        coursesStat() {
            const coursesStat = {};
            this.courses.forEach((course) => {
                coursesStat[course.name] = {
                    doneCount: 0,
                    notDoneCount: 0,
                    notPlacedCount: course.hours,
                    totalHours: course.hours,
                    teachers: course.teachers,
                };
            });
            this.events.forEach((event) => {
                if (event.end < new Date().toISOString()) {
                    coursesStat[event.title].doneCount += 1;
                } else {
                    coursesStat[event.title].notDoneCount += 1;
                }
                coursesStat[event.title].notPlacedCount -= 1;
            });
            return coursesStat;
        },
    },
    methods: {
        handleClick() {
            console.log(this.coursesStat);
        },
    },
    mounted() {
        console.log(this.courses);
    },
};
</script>

<style lang="scss" scoped>
.container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.course-container {
    padding: 1rem;
}
.course-title {
    color: $color-primary-dark;
}
</style>
