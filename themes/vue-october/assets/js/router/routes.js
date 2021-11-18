import Vue from 'vue'
import Router from 'vue-router'
import authGuard from './auth-guard.js'

import Home from '@vue/page/Home'
import Category from '@vue/page/Category'
import Cart from '@vue/page/Cart'
import Checkout from '@vue/page/Checkout'
import Product from '@vue/page/Product'
import SearchPage from '@vue/page/SearchPage'
import Tag from '@vue/page/Tag'
import Notfound from '@vue/page/Notfound'
import Post from '@vue/page/PostPage'
import Stocks from '@vue/page/Stocks'
import Account from '@vue/page/Account'
import ResetPassword from '@vue/page/ResetPassword'

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
        {
          path: '/tag/:slug',
          name: 'tag',
          component: Tag
        },
        {
          path: '/search',
          name: 'search',
          component: SearchPage
        },
        {
          path: '/post/:slug',
          name: 'post',
          component: Post
        },
        {
          path: '/stocks',
          name: 'stocks',
          component: Stocks
        },
        {
          path: '/account',
          name: 'account',
          component: Account,
          props: true,
          beforeEnter: authGuard,
        },
        {
          path: '/reset-password',
          name: 'reset-password',
          component: ResetPassword,
          props: true,
        },
        {
          path: '/404',
          name: 'notfound',
          component: Notfound
        }
    ]
});

export default router