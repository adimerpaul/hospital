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
      { path: '', component: IndexPage, meta: { requiresAuth: true } },
      { path: 'category', component: Category, meta: { requiresAuth: true } },
      { path: 'product', component: Product, meta: { requiresAuth: true } },
      { path: 'sale', component: Sale, meta: { requiresAuth: true } },
      { path: 'patient', component: Patient, meta: { requiresAuth: true } },
      { path: 'reservations', component: Reservations, meta: { requiresAuth: true } },
      { path: 'history/:id', component: History, meta: { requiresAuth: true } }
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
