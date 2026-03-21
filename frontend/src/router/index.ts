import { createRouter, createWebHistory } from 'vue-router';
import LoginView from '@/modules/login/views/Login.vue';
import ResultView from '@/modules/results/views/Result.vue';

const routes = [
    { path: '/login', name: 'login', component: LoginView },
    { path: '/', name: 'results', component: ResultView, meta: { requiresAuth: true } },
    { path: '/:pathMatch(.*)*', redirect: '/' },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, _from, next) => {
    const token = localStorage.getItem('jwt_token');
    if (to.meta.requiresAuth && !token) {
        next({ name: 'login' });
    } else {
        next();
    }
});

export default router;
