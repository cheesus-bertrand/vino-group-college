import { createRouter, createWebHistory } from "vue-router";

import Home from "../pages/Home.vue";
import Cart from "../pages/Cart.vue";
import Login from "../pages/Login.vue";

const routes = [
  {
    path: "/",
    component: Home,
  },
  {
    path: "/cart",
    component: Cart,
  },
  {
    path: "/login",
    component: Login,
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
