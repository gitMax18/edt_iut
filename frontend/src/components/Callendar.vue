<template>
    <div>
        <FullCalendar :options="calendarOptions" ref="calendar" />
        <AddEventModal v-if="isCreateModalShow" :selectedDates="selectedDates" :calendarApi="calendarApi" @handleSubmit="handleSubmitCreateModal" />
        <UpdateEventModal v-if="isUpdateModalShow" :selectedDates="selectedDates" :calendarApi="calendarApi" @handleSubmit="handleSubmitUpdateModal" />
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
        handleAddEvent() {},
        handleChangeEvent(event) {
            console.log("changeEvent", event);
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
        handleSubmitCreateModal() {
            this.isCreateModalShow = false;
        },
        handleSubmitUpdateModal() {
            this.isUpdateModalShow = false;
        },
    },
    async mounted() {
        this.calendarApi = this.$refs.calendar.getApi();

        await this.fetchApi("event");

        if (this.isFetchError) {
            console.log(this.errorMessageApi);
        } else {
            this.calendarOptions.events = this.dataApi.events.map((event) => this.transformApiEventToEvent(event));
        }
    },
};
</script>

<style lang="css" scoped></style>
