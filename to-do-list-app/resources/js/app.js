require("./bootstrap");

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";

import { createApp } from "vue";
import TaskComponent from "./components/TaskComponent.vue";

const app = createApp({});
app.component("task-component", TaskComponent);
app.mount("#app");
