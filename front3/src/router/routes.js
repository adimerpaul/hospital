import Sale from 'pages/Sale'
import Patient from 'pages/Patient'
import Reservations from 'pages/Reservations'

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') },
      { path: 'category', component: () => import('pages/Category.vue') },
      { path: 'product', component: () => import('pages/Product.vue') },
      { path: 'sale', component: Sale },
      { path: 'patient', component: Patient },
      { path: 'reservations', component: Reservations }
    ]
  },
  { path: '/signup', component: () => import('pages/Signup.vue') },
  { path: '/login', component: () => import('pages/Login.vue') },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
