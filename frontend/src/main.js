import { createApp } from "vue";
import App from "./App.vue";
import router from "./router/index";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
import toastConfig from "./config/toastConfig";

const app = createApp(App);

app.use(router);
app.use(Toast, toastConfig);
app.mount("#app");
