<template>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Cours</th>
                    <th>Heures réalisés</th>
                    <th>Heures placé non effectué</th>
                    <th>Heures non placé</th>
                    <th>Nombre total d'heure</th>
                    <th>Professeur</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(course, index) in coursesStat" :key="index">
                    <td>{{ index }}</td>
                    <td>
                        {{ course.hoursDone }}
                    </td>
                    <td>
                        {{ course.hoursPlacedNotDone }}
                    </td>
                    <td>
                        {{ course.totalHours - course.hoursDone + course.hoursPlacedNotDone }}
                    </td>
                    <td>
                        {{ course.totalHours }}
                    </td>
                    <td>
                        <div v-for="(teacher, index) in course.teachers" :key="index">
                            {{ teacher.firstname + " " + teacher.lastname + " " }}
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
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
                let courseName = "";
                if (course.groupe != null) {
                    courseName = course.name + course.groupe;
                } else {
                    courseName = course.name;
                }
                console.log("course", course);
                coursesStat[courseName] = {
                    hoursDone: 0,
                    hoursPlacedNotDone: 0,
                    totalHours: course.hours,
                    teachers: course.teachers,
                };
            });
            this.events.forEach((event) => {
                let courseName;
                if (event.extendedProps.course.groupe != null) {
                    courseName = event.extendedProps.course.name + event.extendedProps.course.groupe;
                } else {
                    courseName = event.extendedProps.course.name;
                }
                if (event.end < new Date().toISOString()) {
                    coursesStat[courseName].hoursDone += this.getHours(new Date(event.start), new Date(event.end));
                } else {
                    coursesStat[courseName].hoursPlacedNotDone += this.getHours(new Date(event.start), new Date(event.end));
                }
            });
            return coursesStat;
        },
    },
    methods: {
        handleClick() {
            console.log(this.coursesStat);
        },
        getHours(start, end) {
            let diff = (end.getTime() - start.getTime()) / 1000;
            diff /= 60 * 60;
            return Math.abs(Math.round(diff));
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
