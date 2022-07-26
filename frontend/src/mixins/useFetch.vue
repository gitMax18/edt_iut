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
        async fetchApi(endUrl = "event", method = "GET", body = null) {
            try {
                this.isLoadingApi = true;
                this.errorMessageApi = "";
                const response = await fetch(this.baseUrl + endUrl, {
                    method,
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: body ? JSON.stringify(body) : null,
                });

                if (!response.ok) {
                    throw new Error(response.statusText);
                }
                this.dataApi = await response.json();
                // console.log("api : ", this.dataApi);
            } catch (error) {
                this.errorMessageApi = error.message;
            } finally {
                this.isLoadingApi = false;
            }
        },

        transformApiEventToEvent(event) {
            // console.log("event ", event);
            return {
                id: event.id,
                title: event.course.name,
                // todo
                start: event.startAt.slice(0, -6),
                end: event.endAt.slice(0, -6),
                backgroundColor: "lightblue",
                borderColor: "green",
                textColor: "black",
                extendedProps: {
                    classroom: event.classroom,
                    teacher: event.teacher,
                    formation: event.formation,
                    course: event.course,
                },
            };
        },
        transformEventToApiEvent(event) {
            // console.log("apiEvent", event);
            return {
                course: event.extendedProps.course.id,
                startAt: event.start,
                endAt: event.end,
                classroom: event.extendedProps.classroom,
                teacher: event.extendedProps.teacher.id,
                formation: event.extendedProps.formation.id,
            };
        },

        getSlug(str) {
            return str.replace(" ", "-");
        },
    },
};
</script>
