import Sale from 'pages/Sale'

const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') },
      { path: 'category', component: () => import('pages/Category.vue') },
      { path: 'product', component: () => import('pages/Product.vue') },
      { path: 'sale', component: Sale }
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
