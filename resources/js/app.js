import { createApp } from 'vue';
import Kanban from './components/Kanban.vue';


const app = createApp({});
app.component('kanban', Kanban);
app.mount('#app');