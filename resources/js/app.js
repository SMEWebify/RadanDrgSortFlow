import { createApp } from 'vue';
import KanbanMachine from './components/Kanban.vue';
import KanbanStatut from './components/Kanban-statut.vue';

const app = createApp({});

// Enregistrement du composant globalement
app.component('kanban-machine', KanbanMachine);
app.component('kanban-statut', KanbanStatut);

// Monter l'application Vue dans le `div#app`
app.mount('#app');
