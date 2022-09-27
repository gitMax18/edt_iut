<template>
    <div class="calendar-container">
        <FullCalendar :options="calendarOptions" ref="calendar" />
        <AddEventModal
            v-if="isCreateModalShow"
            :formationCourses="formationCourses"
            :formation="formation"
            :selectedDates="selectedDates"
            :calendarApi="calendarApi"
            @handleCloseModal="handleCloseCreateModal"
        />
        <UpdateEventModal
            v-if="isUpdateModalShow"
            :formationCourses="formationCourses"
            :selectedDates="selectedDates"
            :calendarApi="calendarApi"
            @handleCloseModal="handleCloseUpdateModal"
        />
        <Loader v-if="isLoadingApi" />
        <Reporting :events="calendarOptions.events" :courses="formationCourses" v-if="isShowReporting" />
        <div class="disableVerificationContainer">
            <div>
                <label for="isDisableVerification">Désactiver la vérification</label>
                <input type="checkbox" id="isDisableVerification" v-model="isDisableVerification" />
            </div>
        </div>
    </div>
</template>

<script>
import "@fullcalendar/core/vdom";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import AddEventModal from "./AddEventModal.vue";
import UpdateEventModal from "./UpdateEventModal.vue";
import useFetch from "../mixins/useFetch.vue";
import Loader from "./Loader.vue";
import Reporting from "./Reporting.vue";
import useToast from "../mixins/useToast.vue";
export default {
    name: "Calendar",
    mixins: [useFetch, useToast],
    components: {
        FullCalendar,
        AddEventModal,
        UpdateEventModal,
        Loader,
        Reporting,
    },
    props: {
        formation: Object,
        isShowReporting: Boolean,
    },
    watch: {
        formation: {
            handler() {
                this.fetchEventByFormation();
                this.fetchCourseByFormation();
            },
            immediate: true,
        },
    },
    data() {
        return {
            calendarOptions: {
                plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin],
                headerToolbar: {
                    left: "prev,next,today",
                    center: "title",
                    right: "dayGridMonth,timeGridWeek,timeGridDay",
                },
                buttonText: {
                    today: "Aujourd'hui",
                    month: "Mois",
                    week: "Semaine",
                    day: "Jour",
                    list: "Liste",
                },
                weekNumbers: true,
                initialView: "timeGridWeek",
                editable: true,
                selectable: true,
                weekends: false,
                timeZone: "local",
                locale: "fr",
                allDaySlot: false,
                slotMinTime: "07:00:00",
                slotMaxTime: "20:00:00",
                slotDuration: "00:15:00",
                events: [],
                select: this.handleDateSelect,
                eventClick: this.handleEventClick,
                eventsSet: this.handleEvents,
                eventAdd: this.handleAddEvent,
                eventChange: this.handleChangeEvent,
                eventRemove: this.handleRemoveEvent,
                eventContent: this.displayEventText,
            },
            calendarApi: null,
            isCreateModalShow: false,
            isUpdateModalShow: false,
            selectedDates: null,
            formationCourses: [],
            isDisableVerification: false,
        };
    },
    methods: {
        handleDateSelect(selectData) {
            this.selectedDates = selectData;
            this.isCreateModalShow = true;
        },
        handleEventClick(clickData) {
            console.log("click", clickData.event);
            this.selectedDates = clickData;
            this.isUpdateModalShow = true;
        },
        handleEvents() {},
        handleAddEvent() {},
        async handleChangeEvent(dataEvent) {
            if (dataEvent.oldEvent.startStr !== dataEvent.event.startStr) {
                await this.fetchApi(`event/${dataEvent.event.id}`, "PUT", {
                    course: dataEvent.event.extendedProps.course.id,
                    teacher: dataEvent.event.extendedProps.teacher?.id,
                    classroom: dataEvent.event.extendedProps.classroom,
                    startAt: dataEvent.event.startStr,
                    endAt: dataEvent.event.endStr,
                    isDisableVerification: this.isDisableVerification,
                });
                if (this.isFetchError) {
                    dataEvent.revert();
                    this.toast.error(this.errorMessageApi);
                    return;
                }

                this.toast.success(this.dataApi.message);
            }
        },
        handleRemoveEvent() {},
        toggleShowWeekend() {
            this.calendarOptions.weekends = !this.calendarOptions.weekends;
        },
        displayEventText(args) {
            const textTime = document.createElement("p");
            textTime.innerHTML = `${args.timeText}`;
            const textContent = document.createElement("p");
            const title = args.event.extendedProps.course.groupe ? args.event.title + ` groupe : ${args.event.extendedProps.course.groupe}` : args.event.title;
            textContent.innerHTML = `${title} / ${args.event.extendedProps.classroom} / ${
                args.event.extendedProps.teacher?.firstname + " " + args.event.extendedProps.teacher?.lastname || "Non assignée"
            }`;
            const arrayOfDomNodes = [textTime, textContent];
            return { domNodes: arrayOfDomNodes };
        },
        handleCloseCreateModal() {
            this.isCreateModalShow = false;
        },
        handleCloseUpdateModal() {
            this.isUpdateModalShow = false;
        },
        async fetchCourseByFormation() {
            if (!this.formation) return;

            await this.fetchApi(`course/${this.formation.id}`);

            if (this.isFetchError) {
                console.log(this.errorMessageApi);
                return;
            }
            this.formationCourses = this.dataApi.courses;
        },
        async fetchEventByFormation() {
            if (!this.formation) return;
            await this.fetchApi(`event/${this.formation.id}`);

            if (this.isFetchError) {
                console.log(this.errorMessageApi);
                return;
            }
            this.calendarOptions.events = this.dataApi.events.map((event) => this.transformApiEventToEvent(event));
        },
    },

    async mounted() {
        this.calendarApi = this.$refs.calendar.getApi();
        console.log(new Date(this.calendarApi.getDate()).getFullYear());
    },
};
</script>

<style lang="scss" scoped>
.calendar-container {
    padding: 1rem;
}

.disableVerificationContainer {
    position: absolute;
    top: 50px;
    right: 2%;
}
</style>
