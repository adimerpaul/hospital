import Sale from 'pages/Sale'
import Patient from 'pages/Patient'
import Reservations from 'pages/Reservations'
import IndexPage from 'pages/IndexPage'
import Category from 'pages/Category'
import Product from 'pages/Product'
import History from 'pages/History'

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: IndexPage },
      { path: 'category', component: Category },
      { path: 'product', component: Product },
      { path: 'sale', component: Sale },
      { path: 'patient', component: Patient },
      { path: 'reservations', component: Reservations },
      { path: 'history/:id', component: History }
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
