import Vue from 'vue'
import Router from 'vue-router'

import Home from '@vue/page/Home'
import Category from '@vue/page/Category'
import Cart from '@vue/page/Cart'
import Checkout from '@vue/page/Checkout'
import Product from '@vue/page/Product'
import SearchPage from '@vue/page/SearchPage'

Vue.use(Router);
let router = new Router({
    mode: 'history',
    routes: [
        {
          path: '/',
          name: 'home',
          component: Home
        },
        {
          path: '/category/:slug',
          name: 'category',
          component: Category
        },
        {
          path: '/cart',
          name: 'cart',
          component: Cart
        },
        {
          path: '/checkout',
          name: 'checkout',
          component: Checkout
        },
        {
          path: '/product/:id',
          name: 'product',
          component: Product
        },
        // {
        //   path: '/tag/:slug',
        //   name: 'tag',
        //   component: Tag
        // },
        {
          path: '/search',
          name: 'search',
          component: SearchPage
        },
        // {
        //   path: '/404',
        //   name: 'notfound',
        //   component: Notfound
        // }
    ]
});

export default router