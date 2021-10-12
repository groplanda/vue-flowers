import Vue from 'vue'
import Router from 'vue-router'

import Home from '@vue/page/Home'
import Category from '@vue/page/Category'
import Cart from '@vue/page/Cart'
import Checkout from '@vue/page/Checkout'
import CheckoutResult from '@vue/page/CheckoutResult'
import Product from '@vue/page/Product'
import SearchPage from '@vue/page/SearchPage'
import Tag from '@vue/page/Tag'
import Notfound from '@vue/page/Notfound'
import Post from '@vue/page/PostPage'
import Stocks from '@vue/page/Stocks'

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
          component: Checkout,
        },
        {
          path: '/checkout-result',
          name: 'checkoutResult',
          component: CheckoutResult
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
          path: '/404',
          name: 'notfound',
          component: Notfound
        }
    ]
});

export default router