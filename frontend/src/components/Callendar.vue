<template>
    <div class="calendar-container">
        <FullCalendar :options="calendarOptions" ref="calendar" />
        <AddEventModal
            v-if="isCreateModalShow"
            :formation="formation"
            :sector="sector"
            :selectedDates="selectedDates"
            :calendarApi="calendarApi"
            @handleCloseModal="handleCloseCreateModal"
        />
        <UpdateEventModal v-if="isUpdateModalShow" :selectedDates="selectedDates" :calendarApi="calendarApi" @handleCloseModal="handleCloseUpdateModal" />
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
export default {
    name: "Calendar",
    mixins: [useFetch],
    components: {
        FullCalendar,
        AddEventModal,
        UpdateEventModal,
    },
    props: {
        formation: String,
        sector: String,
    },
    watch: {
        formation() {
            this.fetchEventByFormation();
        },
        sector() {
            this.fetchEventByFormation();
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
                initialView: "timeGridWeek",
                editable: true,
                selectable: true,
                weekends: false,
                timeZone: "local",
                locales: "fr",
                // selectMirror: true,
                allDaySlot: false,
                slotMinTime: "06:00:00",
                slotMaxTime: "19:00:00",
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
        };
    },
    methods: {
        handleDateSelect(selectData) {
            this.selectedDates = selectData;
            this.isCreateModalShow = true;
        },
        handleEventClick(clickData) {
            this.selectedDates = clickData;
            this.isUpdateModalShow = true;
        },
        handleEvents() {},
        handleAddEvent() {
            console.log(this.calendarOptions.events);
        },
        async handleChangeEvent(dataEvent) {
            await this.fetchApi(`event/${dataEvent.event.id}`, "PUT", {
                course: dataEvent.event.title,
                teacher: dataEvent.event.extendedProps.teacher,
                classroom: dataEvent.event.extendedProps.classroom,
                startAt: dataEvent.event.startStr,
                endAt: dataEvent.event.endStr,
                sector: dataEvent.event.extendedProps.sector,
                formation: dataEvent.event.extendedProps.formation,
            });

            if (this.isFetchError) {
                console.log(this.errorMessageApi);
                dataEvent.revert();
                return;
            }
            if (this.dataApi.status === "error") {
                dataEvent.revert();
            }

            console.log(this.dataApi.message);
        },
        handleRemoveEvent() {},
        toggleShowWeekend() {
            this.calendarOptions.weekends = !this.calendarOptions.weekends;
        },
        displayEventText(args) {
            const textTime = document.createElement("p");
            textTime.innerHTML = `${args.timeText}`;
            const textContent = document.createElement("p");
            textContent.innerHTML = `${args.event.title} / ${args.event.extendedProps.classroom} / ${args.event.extendedProps.teacher}`;
            const arrayOfDomNodes = [textTime, textContent];
            return { domNodes: arrayOfDomNodes };
        },
        handleCloseCreateModal() {
            this.isCreateModalShow = false;
        },
        handleCloseUpdateModal() {
            this.isUpdateModalShow = false;
        },
        async fetchEventByFormation() {
            await this.fetchApi(`event/${this.getSlug(this.sector)}/${this.getSlug(this.formation)}`);

            if (this.isFetchError) {
                console.log(this.errorMessageApi);
                return;
            }
            this.calendarOptions.events = this.dataApi.events.map((event) => this.transformApiEventToEvent(event));
        },
    },
    async mounted() {
        this.calendarApi = this.$refs.calendar.getApi();

        await this.fetchApi("event");

        if (this.isFetchError) {
            console.log(this.errorMessageApi);
            return;
        }
        this.calendarOptions.events = this.dataApi.events.map((event) => this.transformApiEventToEvent(event));
    },
};
</script>

<style lang="scss" scoped>
.calendar-container {
    padding: 1rem;
}
</style>
