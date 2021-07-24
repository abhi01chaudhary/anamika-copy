import Vue from 'vue'
import VueRouter from 'vue-router'
import Index from '../views/invoices/index.vue'
import Form from '../views/invoices/form.vue'
import Show from '../views/invoices/show.vue'

Vue.use(VueRouter)

const router = new VueRouter({
    routes: [
        {
            path: '/', 
            redirect: '/invoices'
        },
        {
            path: '/invoices', 
            component: Index
        },
        {
            path: '/invoices/create', 
            component: Form
        },
        {
            path: '/invoices/:id/edit', 
            component: Form, 
            meta: {mode: 'edit'}
        },
        {
            path: '/invoices/:id', 
            component: Show
        }
    ]
})

export default router
