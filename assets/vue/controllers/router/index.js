import { createMemoryHistory, createRouter, createWebHistory, createWebHashHistory, craete } from 'vue-router';
import Blank from "../components/Right/Blank.vue"
import Right from "../components/Right/Right.vue"
const routes = [
    {
        name: 'blank',
        path: '/prueba',
        component: Blank
    },
    {
        name: 'conversation',
        path: '/conversation/:id',
        component: Right
    }
];

export const router = createRouter({
    history: createMemoryHistory(),
    routes
});
