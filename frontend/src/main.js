import { createApp } from "vue";
import App from "./App.vue";
import router from "./router/index";
import Toast from "vue-toastification";
// Import the CSS or use your own!
import "vue-toastification/dist/index.css";
import toastConfig from "./config/toastConfig";
// import VueToastify from "vue-toastify";

const app = createApp(App);

app.use(router);
app.use(Toast, toastConfig);
// app.use(VueToastify);
app.mount("#app");
