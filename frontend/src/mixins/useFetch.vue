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
            } catch (error) {
                this.errorMessageApi = error.message;
            } finally {
                this.isLoadingApi = false;
            }
        },

        transformApiEventToEvent(event) {
            return {
                id: event.id,
                title: event.course,
                start: event.startAt.slice(0, -6),
                end: event.endAt.slice(0, -6),
                extendedProps: {
                    classroom: event.classroom,
                    teacher: event.teacher,
                },
            };
        },

        transformEventToApiEvent(event) {
            return {
                course: event.title,
                startAt: event.start,
                endAt: event.end,
                classroom: event.extendedProps.classroom,
                teacher: event.extendedProps.teacher,
            };
        },
    },
};
</script>
