import './assets/css/main.css'

import {createApp} from 'vue'
import App from './App.vue'
import './style.css'
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const app = createApp(App);

const toastOptions = {
    position: "top-center",
    timeout: 5000,
    closeOnClick: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    hideProgressBar: false,
};

app.use(Toast, toastOptions);
app.mount('#app');

