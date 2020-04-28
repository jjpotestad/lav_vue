<template>
  <div class="card">
    <div class="card-header">
      <h2 class="text-center">Users list</h2>
      <router-link :to="{ name: 'user.create' }" class="text-success float-right"><i class="fa fa-plus" aria-hidden="true"></i></router-link>
    </div>
    <div class="card-body">
      <table class="table">
        <thead class="thead-dark">
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Email</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in users" :key="item.id">
            <td>{{item.name}}</td>
            <td>{{item.email}}</td>
            <td>
              <div class="btn-group" role="group">
                <router-link class="text-warning" :to="urlWeb+'/user/edit/'+item.id"><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
                <router-link class="text-primary" :to="urlWeb+'/user/show/'+item.id"><i class="fa fa-eye" aria-hidden="true"></i></router-link>
                <a
                  @click.prevent="delUser(item.id)"
                  v-if="item.role == 'ROLE_USER'"
                  href="#"
                  class="text-danger"
                ><i class="fa fa-trash" aria-hidden="true"></i></a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
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
      users: null,
      urlApi: global.urlApi,
      urlWeb: global.urlWeb,
      token: null
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
          this.getAll();
        })
        .catch(err => {
          // handle error
          console.log(err);
        });
    },
    getAll() {
      // Make a request to the API
      axios
        .get(this.urlApi + "/users", {
          headers: { Authorization: this.token }
        })
        .then(res => {
          if (res.data.status == "success") {
            this.users = res.data.list;
          }
        })
        .catch(err => {
          // handle error
          console.log(err);
        });
    },

    delUser(id) {
      Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, thanks!"
      }).then(result => {
        if (result.value) {
          // Make a request to the API
          axios
            .delete(this.urlApi + "/users/" + id, {
              headers: { Authorization: this.token }
            })
            .then(res => {
              if (res.data.status == "success") {
                this.$nextTick(function() {
                  // DOM is now updated
                  this.getAll();
                });
              }
            })
            .catch(err => {
              // handle error
              console.log(err);
            });
        } else {
          Swal.fire("Canceled!", "The user is still there.", "success");
        }
      });
    }
  }
};
</script>
<style scoped>
  .btn-group a{
    margin-right: 8px;
  }
</style>