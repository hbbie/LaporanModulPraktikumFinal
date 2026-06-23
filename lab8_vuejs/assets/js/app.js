const { createApp } = Vue;
const { createRouter, createWebHashHistory } = VueRouter;

// URL API CI4
const apiUrl = 'http://localhost/lab11_ci/ci4/public';

// Routing
const routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: '/artikel',
        component: Artikel
    }
];

// Buat Router
const router = createRouter({
    history: createWebHashHistory(),
    routes
});

// Inisialisasi Vue App
const app = createApp({});

// Gunakan Router
app.use(router);

// Mount ke div #app
app.mount('#app');