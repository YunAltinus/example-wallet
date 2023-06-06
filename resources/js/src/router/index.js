import { createRouter, createWebHashHistory } from "vue-router";
import $cookies from "vue-cookies";
import { checkUserLogin } from "@/store/isLogin.js";

const router = createRouter({
    history: createWebHashHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: "/",
            name: "home",
            component: () => import("../views/index.vue"),
        },
        {
            path: "/login",
            name: "login",
            component: () => import("../views/login/index.vue"),
            meta: {
                noRequiresAuth: true,
            },
        },
        {
            path: "/register",
            name: "register",
            component: () => import("../views/register/index.vue"),
            meta: {
                noRequiresAuth: true,
            },
        },
        {
            path: "/transaction",
            name: "transaction",
            component: () => import("../views/transaction/index.vue"),
        },
    ],
});

router.beforeEach((to, from, next) => {
    const token = $cookies.get("accessToken");

    if (to.matched.some((record) => !record.meta.noRequiresAuth)) {
        if (!token) {
            checkUserLogin(false);

            next({ name: "login" });
        } else {
            checkUserLogin(true);

            next();
        }
    } else {
        if (token) {
            checkUserLogin(true);

            next({ name: "home" });
        } else {
            checkUserLogin(false);

            next();
        }
    }
});

export default router;
