import Vue from 'vue'
// Para el manejo de rutas
import VueRouter from 'vue-router'
// Para las validaciones
import Vuelidate from 'vuelidate'
// Componentes
import App from './views/App'
import Home from './views/Home'
import user_index from './components/user_index'
import user_create from './components/user_create'
import user_show from './components/user_show'
import user_edit from './components/user_edit'

import global from './global';

Vue.use(Vuelidate);

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    routes: [
        { path: global.urlWeb+'/home', name: 'home', component: Home },
        { path: global.urlWeb+'/users', name: 'user.index', component: user_index },
        { path: global.urlWeb+'/user/create', name: 'user.create', component: user_create },
        { path: global.urlWeb+'/user/show/:id', name: 'user.show', component: user_show },
        { path: global.urlWeb+'/user/edit/:id', name: 'user.edit', component: user_edit },
    ],
});

const app = new Vue({
    el: '#app',
    components: { App },
    router,
});