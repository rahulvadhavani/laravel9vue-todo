import "bootstrap"
import "bootstrap/dist/css/bootstrap.min.css"
import { createApp } from 'vue';
import App from './App.vue';
import Toast,{POSITION} from "vue-toastification";
import "vue-toastification/dist/index.css";

const app = createApp(App);
app.use(Toast,{
    position: POSITION.BOTTOM_RIGHT
})
app.mount("#app");
