<template>
  <div class="card">
    <div class="card-header">
      <h2 class="text-center">Edit user</h2>
    </div>

    <div class="card-body">
      <form @submit.prevent="submitEdit">
        <div class="form-group">
          <label for="name">Name</label>
          <input
            type="text"
            class="form-control"
            v-bind:class="[!$v.user.name.required ? 'is-invalid' : 'is-valid']"
            id="name"
            v-model="user.name"
          />
          <div
            class="invalid-feedback"
            v-if="submitted && !$v.user.name.required"
          >Este campo es requerido</div>
        </div>

        <div class="form-group">
          <label for="surname">Surname</label>
          <input
            type="text"
            class="form-control"
            v-bind:class="[!$v.user.surname.required ? 'is-invalid' : 'is-valid']"
            id="surname"
            v-model="user.surname"
          />
          <div
            class="invalid-feedback"
            v-if="submitted && !$v.user.surname.required"
          >Este campo es requerido</div>
        </div>

        <div class="form-group">
          <label for="role">Role</label>
          <select
            ref="role"
            class="form-control"
            v-bind:class="[!$v.user.role.required ? 'is-invalid' : 'is-valid']"
            id="role"
            @change="roleChange()"
          >
            <option>Choose a role</option>
            <option value="ROLE_USER">User</option>
            <option value="ROLE_ADMIN">Admin</option>
          </select>
          <div
            class="invalid-feedback"
            v-if="submitted && !$v.user.role.required"
          >Este campo es requerido</div>
        </div>

        <div class="form-group">
          <label for="email">Email address</label>
          <input
            type="email"
            class="form-control"
            v-bind:class="[!$v.user.email.required || msg_backend.email ? 'is-invalid' : 'is-valid']"
            id="email"
            v-model="user.email"
          />
          <div
            class="invalid-feedback"
            v-if="submitted && !$v.user.email.required"
          >Este campo es requerido</div>
          <div class="invalid-feedback" v-if="msg_backend.email">{{ msg_backend.email[0] }}</div>
        </div>

        <button type="submit" class="btn btn-outline-primary">Save</button>
        <router-link :to="{ name: 'user.index' }" class="btn btn-outline-secondary float-right">Cancel</router-link>
      </form>
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
// Validar el formulario con Vuelidate
import { required, minLength } from "vuelidate/lib/validators";

export default {
  data() {
    return {
      urlApi: global.urlApi,
      urlWeb: global.urlWeb,
      token: null,
      submitted: false,
      user: {
        name: null,
        surname: null,
        email: null
      },
      msg_backend: {
        name: false,
        surname: false,
        email: false,
        role: false
      }
    };
  },
  validations: {
    user: {
      name: {
        required
      },
      surname: {
        required
      },
      email: {
        required
      },
      role: {
        required
      },
    }
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
    roleChange() {
      this.user.role = this.$refs.role.value;
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
    submitEdit(){
      this.submitted = true;
      //Para salir del metodo si no es valido el formulario
      this.$v.$touch();
      if (this.$v.$invalid) {
        return false;
      } else {
        Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, save it!",
        cancelButtonText: "No, thanks!"
      }).then(result => {
        if (result.value) {
        // Make a request to the API
        let id = this.$route.params.id;
        axios
          .patch(this.urlApi + "/users/" + id, this.user, {
            headers: { Authorization: this.token }
          })
          .then(res => {
            if (res.data.status == "success") {
              this.$router.push(global.urlWeb + "/users");
              Swal.fire(
                "Updated user",
                "Your user was updated successfully",
                "success"
              );
            } else {
              // Mensajes de error del backend
              console.log(res.data.message);
              this.msg_backend = res.data.message;
            }
          })
          .catch(err => {
            // handle error
            console.log(err);
          });
          } else {
          Swal.fire("Canceled!", "The user is still unchange.", "success");
        }
      });
      }
    }
  }
};
</script>
