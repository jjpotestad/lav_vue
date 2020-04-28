<template>
  <div>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
      <div class="container">
        <router-link :to="{ name: 'home' }" class="navbar-brand">Lav_Vue</router-link>
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side Of Navbar -->
          <ul class="navbar-nav mr-auto"></ul>
          <!-- Right Side Of Navbar -->
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <router-link :to="{ name: 'home' }" class="nav-link">Home</router-link>
            </li>
            <li class="nav-item">
              <router-link :to="{ name: 'user.index' }" class="nav-link">User list</router-link>
            </li>
            <li class="nav-item">
              <a @click.prevent="submitLogout()" href="#" class="nav-link">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <router-view></router-view>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import global from "../global";
import axios from "axios";

export default {
  data() {
    return {
      urlWeb: global.urlWeb
    };
  },
  methods: {
    submitLogout() {
      // console.log(global.urlWeb+"/logout");
      axios.post(global.urlWeb + "/logout").catch(err => {
        // handle error
        console.log(err);
        window.location.href = global.urlWeb + "/login";
      });
    }
  }
};
</script>
<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.container {
  padding: 1rem;
}
</style>