import { createRouter, createWebHistory } from "vue-router";
import CallendarPage from "../pages/CallendarPage.vue";
const routes = [
    {
        path: "/",
        name: "CallendarPage",
        component: CallendarPage,
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
