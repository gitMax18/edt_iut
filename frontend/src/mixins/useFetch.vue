<script>
export default {
    name: "useFetch",
    computed: {
        isFetchError() {
            return !this.isLoadingApi && this.errorMessageApi.length > 0 ? true : false;
        },
    },
    data() {
        return {
            errorMessageApi: "",
            isLoadingApi: false,
            dataApi: null,
            baseUrl: "http://localhost:8000/api/",
        };
    },
    methods: {
        async fetchApi(endUrl = "event", method = "GET", body = null, contentType = "application/json") {
            try {
                this.isLoadingApi = true;
                this.errorMessageApi = "";
                if (contentType !== "multipart/form-data" && body !== null) {
                    body = JSON.stringify(body);
                }
                const response = await fetch(this.baseUrl + endUrl, {
                    method,
                    headers: {
                        // "Content-Type": contentType,
                    },
                    body,
                });
                if (!response.ok) {
                    throw new Error("Une erreur interne s'est produite");
                }
                const data = await response.json();

                if (!data.success) {
                    throw new Error(data.message);
                }
                this.dataApi = data;
            } catch (error) {
                this.errorMessageApi = error.message;
            } finally {
                this.isLoadingApi = false;
            }
        },

        transformApiEventToEvent(event) {
            return {
                id: event.id,
                title: event.course.name,
                start: event.startAt.slice(0, -6),
                end: event.endAt.slice(0, -6),
                backgroundColor: event.course.backgroundColor,
                borderColor: event.course.borderColor,
                textColor: event.course.textColor,
                extendedProps: {
                    classroom: event.classroom,
                    teacher: event.teacher,
                    formation: event.formation,
                    course: event.course,
                },
            };
        },
        transformEventToApiEvent(event) {
            // console.log("event : ", event);
            // let teachers = null;
            // if (event.extendedProps.teacher !== "none") {
            //     teachers = event.extendedProps.teacher.map((teacherObject) => {
            //         return teacherObject.id;
            //     });
            // }
            console.log("test", event);
            return {
                course: event.extendedProps.course.id,
                startAt: event.start,
                endAt: event.end,
                classroom: event.extendedProps.classroom,
                teacher: event.extendedProps.teacher?.id,
                formation: event.extendedProps.formation.id,
                isDisableVerification: event.isDisableVerification,
            };
        },
    },
};
</script>
