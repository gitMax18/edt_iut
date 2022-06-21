<template>
    <div>
        <FullCalendar :options="calendarOptions" ref="calendar" />
        <EventModal v-if="isModalShow" :selectedData="selectedData" :calendarApi="calendarApi" @handleSubmit="handleSubmitModal" />
    </div>
</template>

<script>
import "@fullcalendar/core/vdom";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import EventModal from "./EventModal.vue";
export default {
    name: "Calendar",
    components: {
        FullCalendar,
        EventModal,
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
                locales: "fr",
                // selectMirror: true,
                allDaySlot: false,
                slotMinTime: "06:00:00",
                slotMaxTime: "19:00:00",
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
            isModalShow: false,
            selectedData: null,
        };
    },
    methods: {
        handleDateSelect(selectData) {
            this.selectedData = selectData;
            this.isModalShow = true;
        },
        handleEventClick(clickData) {
            if (confirm(`Are you sure you want to delete the event '${clickData.event.title}'`)) {
                clickData.event.remove();
            }
        },
        handleEvents() {},
        handleAddEvent() {},
        handleChangeEvent() {},
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
        handleSubmitModal() {
            this.isModalShow = false;
        },
    },
    mounted() {
        this.calendarApi = this.$refs.calendar.getApi();
    },
};
</script>

<style lang="css" scoped></style>
