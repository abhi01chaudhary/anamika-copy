<template>
  <div>
    <div class="container">
      <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="collapse navbar-collapse">
          <!-- for logged-in user-->
          <div class="navbar-nav" v-if="isLoggedIn">
            <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>
            <a class="nav-item nav-link" style="cursor: pointer;" @click="logout">Logout</a>
          </div>
          <!-- for non-logged user-->
          <div class="navbar-nav" v-else>
            <router-link to="/login" class="nav-item nav-link">Login</router-link>
            <router-link to="/register" class="nav-item nav-link">Register</router-link>
            <router-link to="/dashboard" class="nav-item nav-link">Dashboard</router-link>
          </div>
        </div>
      </nav>
    </div>
  </div>
</template>

<script>
  export default {
    name: 'Navbar',
    data(){
      return {
        isLoggedIn: false
      }
    }
  }
</script>

<style lang="scss" scoped>

</style>