<template>
  <div id="app">
    <md-toolbar class="md-primary md-toolbar-section-end" md-elevation="1">
      <h3 class="md-title md-toolbar-section-start" style="flex: 1">Title</h3>
      <router-link to="/"><md-button>News</md-button></router-link>
      <router-link to="/posts" v-if="isLogged"><md-button >Posts</md-button></router-link>
      <router-link to="/login" v-if="!isLogged"><md-button> Login</md-button> </router-link>
      <router-link to="/register" v-if="!isLogged"><md-button> Register </md-button></router-link>
      <md-button class="logout" style="float: right; cursor: pointer; font-weight: bold" type="button" @click="logout()" v-if="isLogged">
        Logout
      </md-button>
    </md-toolbar>

    <div style="padding: 30px">
      <router-view/>
    </div>

  </div>
</template>


<script>
  import { mapGetters } from 'vuex'

  export default {
    computed: {
      ...mapGetters([
        'isLogged'
      ])
    },

    methods: {
      logout () {
        this.$store.dispatch('logout')
      }
    }
  }
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}


a .md-button-content{
  font-weight: bold;
  color: black!important;
}

.logout{
  color: black!important;
}

 a.router-link-exact-active .md-button-content{
  color: white!important;
}

</style>
