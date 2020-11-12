import Home from './components/Home.vue';
import Register from './components/Auth/Register.vue';
import Login from './components/Auth/Login.vue';
import Confirm from './components/Auth/Confirmation.vue';

import CategoryMain from './components/Categories/Main.vue';
import CategoryList from './components/Categories/List.vue';
import CategoryNew from './components/Categories/New.vue';
import Category from './components/Categories/Show.vue';
import CategoryEdit from './components/Categories/Edit.vue';

import PostMain from './components/Posts/Main.vue';
import PostList from './components/Posts/List.vue';
import PostNew from './components/Posts/New.vue';
import Post from './components/Posts/Show.vue';
import PostEdit from './components/Posts/Edit.vue';

import CartItemMain from './components/CartItems/Main.vue';
import CartItemList from './components/CartItems/List.vue';

import OrdersMain from './components/Orders/Main.vue';
import OrdersList from './components/Orders/List.vue';

import SubscriptionsMain from './components/Subscriptions/Main.vue';
import SubscriptionsList from './components/Subscriptions/List.vue';

import CardMain from './components/Cards/Main.vue';
import CardList from './components/Cards/List.vue';
import CardNew from './components/Cards/New.vue';
import Card from './components/Cards/Show.vue';
import CardEdit from './components/Cards/Edit.vue';

import UsersMain from './components/Users/Main.vue';
import UserList from './components/Users/List.vue';
import UserNew from './components/Users/New.vue';
import User from './components/Users/Show.vue';
import UserEdit from './components/Users/Edit.vue';


export const routes = [
    {
        path: '/',
        component: Home
    },
    {
        path: '/register',
        component: Register,
    },
    {
        path: '/login',
        component: Login,
    },
    {
        path: '/confirmation/:token',
        component: Confirm,
    },
    {
        path: '/category',
        component: CategoryMain,
        children:[
            {
                path:'/',
                component:CategoryList
            },
            {
                path:'new',
                component:CategoryNew
            },
            {
                path:':id',
                component:Category
            },
            {
                path:':id/edit',
                component:CategoryEdit
            },


        ]
    },
    {
        path: '/post',
        component: PostMain,
        children:[
            {
                path:'/',
                component:PostList
            },
            {
                path:'new',
                component:PostNew
            },
            {
                path:':id',
                component:Post
            },
            {
                path:':id/edit',
                component:PostEdit
            },
        ]
    },
    {
        path: '/cartItems',
        component: CartItemMain,
        children:[
            {
                path:'/',
                component:CartItemList
            },
        ]
    },
    {
        path: '/orders',
        component: OrdersMain,
        children:[
            {
                path:'/',
                component:OrdersList
            },
        ]
    },
    {
        path: '/subscriptions',
        component: SubscriptionsMain,
        children:[
            {
                path:'/',
                component:SubscriptionsList
            },
        ]
    },
    {
        path: '/card',
        component: CardMain,
        children:[
            {
                path:'/',
                component:CardList
            },
            {
                path:'new',
                component:CardNew
            },
            {
                path:':id',
                component:Card
            },
            {
                path:':id/edit',
                component:CardEdit
            },
        ]
    },
    {
        path: '/users',
        component: UsersMain,
        children:[
            {
                path:'/',
                component:UserList
            },
            {
                path:'new',
                component:UserNew
            },
            {
                path:':id',
                component:User
            },
            {
                path:':id/edit',
                component:UserEdit
            },
        ]
    },
]
