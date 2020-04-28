<template>
  <div class="card">
    <div class="card-header">
      <h2 class="text-center">Create user</h2>
    </div>

    <div class="card-body">
      <form @submit.prevent="submitSave">
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
        <div class="form-group">
          <label for="password">Password</label>
          <input
            type="password"
            class="form-control"
            v-bind:class="[!$v.user.password.minLength || !$v.user.password.required ? 'is-invalid' : 'is-valid']"
            id="password"
            v-model="user.password"
          />
          <div
            class="invalid-feedback"
            v-if="submitted && !$v.user.password.required"
          >Este campo es requerido</div>
          <div
            class="invalid-feedback"
            v-if="submitted && !$v.user.password.minLength"
          >Este campo debe tener al menos 8 letras</div>
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
      submitted: false,
      token: null,
      user: {
        name: null,
        surname: null,
        email: null,
        role: null,
        password: null
      },
      msg_backend: {
        name: false,
        surname: false,
        email: false,
        role: false,
        password: false
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
      password: {
        required,
        minLength: minLength(8)
      }
    }
  },
  mounted() {
    this.getToken();
  },
  methods: {
    roleChange() {
      this.user.role = this.$refs.role.value;
    },
    getToken() {
      axios
        .get(this.urlWeb + "/gettoken")
        .then(res => {
          this.token = res.data.token;
        })
        .catch(err => {
          // handle error
          console.log(err);
        });
    },
    submitSave() {
      this.submitted = true;
      //Para salir del metodo si no es valido el formulario
      this.$v.$touch();
      if (this.$v.$invalid) {
        return false;
      } else {
        // Make a request to the API
        axios
          .post(this.urlApi + "/users", this.user, {
            headers: { Authorization: this.token }
          })
          .then(res => {
            if (res.data.status == "success") {
              this.$router.push(global.urlWeb + "/users");
              Swal.fire(
                "Created user",
                "Your user was created successfully",
                "success"
              );
            } else {
              // Mensajes de error del backend
              this.msg_backend = res.data.message;
            }
          })
          .catch(err => {
            // handle error
            console.log(err);
          });
      }
    }
  }
};
</script>
<style scoped>
</style>