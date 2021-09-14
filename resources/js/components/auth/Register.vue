<template>
  <div>
    <div class="container">
      <div class="row justify-content-center py-5">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-header text-center">
              <h4>Register</h4>
            </div>
            <div class="card-body">
              <form v-on:submit.prevent="register">
                <div class="form-group">
                  <label for="first_name"> First Name</label>
                  <input type="text" v-model="first_name" class="form-control" name="first_name" placeholder="First name">
                  <small class="error-control" v-if="errors.first_name">
                    {{errors.first_name[0]}}
                  </small>
                </div>

                <div class="form-group">
                  <label for="last_name"> Last Name</label>
                  <input type="text" v-model="last_name" class="form-control" name="last_name" placeholder="Last name">
                  <small class="error-control" v-if="errors.last_name">
                    {{errors.last_name[0]}}
                  </small>
                </div>

                <div class="form-group">
                  <label for="email"> Email Address</label>
                  <input type="email" v-model="email" class="form-control" name="email" placeholder="Email Address">
                  <small class="error-control" v-if="errors.email">
                    {{errors.email[0]}}
                  </small>
                </div>

                <div class="form-group">
                  <label for="password"> Password</label>
                  <input type="password" v-model="password" class="form-control" name="password" placeholder="Password">
                  <small class="error-control" v-if="errors.password">
                    {{errors.password[0]}}
                  </small>
                </div>
                <button class="btn btn-lg btn-primary btn-block">Register</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>
<script>
  import {get, byMethod} from '../../lib/api'

  export default{
    data(){
      return {
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        method: 'POST',
        errors: {}
      }
    },
    methods:{
      register(){
        byMethod(this.method, '/api/register',
        {
          first_name: this.first_name,
          last_name: this.last_name,
          email: this.email,
          password: this.password
        })
        .then((res) => {
          this.$router.push('/login')
        })
        .catch((error) => {
          if(error.response.status === 422) {
            this.errors = error.response.data.errors
          }
        })
      }

    }
  }
</script>