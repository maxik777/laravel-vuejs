import Vue from 'vue'
import VueRouter from 'vue-router'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Posts from '../views/Posts'
import News from '../views/News'


Vue.use(VueRouter)

  const routes = [
  {
    path: '/login',
    name: 'Home',
    component: Login
  },
  {
    path: '/register',
    name: 'Register',
    component: Register
  },
  {
    path: '/posts',
    name: 'Posts',
    meta: {
      auth: true
    },
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: Posts
  },
  {
    path: '/',
    name: 'News',
    component: News
  },
  {
    path: '*',
    name: 'About',
    meta: {
      auth: true
    },
    component: Posts
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

router.beforeEach((to, from, next) => {
  const loggedIn = localStorage.getItem('user')

  if (to.matched.some(record => record.meta.auth) && !loggedIn) {
    next(router.push({ path: '/login' }))
    return
  }
  next()
})




export default router
