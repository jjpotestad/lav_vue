<template>
  <div class="card">
    <div class="card-header">
      <h2 class="text-center">Show user</h2>
    </div>
    <div class="card-body">
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Surname</th>
            <th scope="col">Role</th>
            <th scope="col">Email</th>
            <th scope="col">API Token</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{user.name}}</td>
            <td>{{user.surname}}</td>
            <td>{{user.role}}</td>
            <td>{{user.email}}</td>
            <td><a @click.prevent="showToken()" href="#" class="text-primary">
              <i class="fa fa-eye" aria-hidden="true"></i>
            </a></td>
          </tr>
        </tbody>
      </table>
      <router-link :to="{ name: 'user.index' }" class="btn btn-outline-secondary float-right">Cancel</router-link>
    </div>
  </div>
</template>

<script>
// Variables globales
import global from "../global";
// Para peticiones la BackEnd
import axios from "axios";
// Para alertas bonitas
import Swal from "sweetalert2";

export default {
  data() {
    return {
      urlApi: global.urlApi,
      urlWeb: global.urlWeb,
      token: null,
      user: {
        name: null,
        surname: null,
        email: null
      }
    };
  },
  mounted() {
    this.getToken();
  },
  methods: {
    getToken() {
      axios
        .get(this.urlWeb + "/gettoken")
        .then(res => {
          this.token = res.data.token;
          let id = this.$route.params.id;
          this.getUser(id);
        })
        .catch(err => {
          // handle error
          console.log(err);
        });
    },

    getUser(id) {
      // Make a request to the API
      axios
        .get(this.urlApi + "/users/" + id, {
          headers: { Authorization: this.token }
        })
        .then(res => {
          if (res.data.status == "success") {
            this.user = res.data.user;
          }
        })
        .catch(err => {
          // handle error
          console.log(err);
        });
    },
    showToken(){
      Swal.fire(
        "User's api_token",
        "Your api_token is"+this.user.api_token,
        "success"
      );
    }
  }
};
</script>
