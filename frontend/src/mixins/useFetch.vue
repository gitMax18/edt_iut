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
                    throw new Error(response.message);
                }
                this.dataApi = await response.json();
            } catch (error) {
                this.errorMessageApi = error.message;
            } finally {
                this.isLoadingApi = false;
            }
        },

        transformApiEventToEvent(event) {
            // console.log("event ", event);
            return {
                id: event[0].id,
                title: event[0].course.name,
                start: event[0].startAt.slice(0, -6),
                end: event[0].endAt.slice(0, -6),
                backgroundColor: event.backgroundColor,
                borderColor: event.borderColor,
                textColor: event.textColor,
                extendedProps: {
                    classroom: event[0].classroom,
                    teacher: event[0].teacher,
                    formation: event[0].formation,
                    course: event[0].course,
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
