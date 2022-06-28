<template>
    <div>
        <FullCalendar :options="calendarOptions" ref="calendar" />
        <AddEventModal v-if="isCreateModalShow" :selectedData="selectedData" :calendarApi="calendarApi" @handleSubmit="handleSubmitCreateModal" />
        <UpdateEventModal v-if="isUpdateModalShow" :selectedData="selectedData" :calendarApi="calendarApi" @handleSubmit="handleSubmitUpdateModal" />
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
import axios from "axios";
export default {
    name: "Calendar",
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
            isCreateModalShow: false,
            isUpdateModalShow: false,
            selectedData: null,
        };
    },
    methods: {
        handleDateSelect(selectData) {
            this.selectedData = selectData;
            this.isCreateModalShow = true;
        },
        handleEventClick(clickData) {
            this.selectedData = clickData;
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
    mounted() {
        this.calendarApi = this.$refs.calendar.getApi();
        fetch("http://localhost:8000/api/event", {
            mode: "no-cors",
            headers: {
                "Access-Control-Allow-Origin": "*",
            },
        })
            .then((res) => {
                console.log(res);
                if (!res.ok) {
                    throw new Error("Un probleme est survenue");
                }
                res.json();
            })
            .then((data) => console.log(data))
            .catch((err) => console.log(err.message));
    },
};
</script>

<style lang="css" scoped></style>
