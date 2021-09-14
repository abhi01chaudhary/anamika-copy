<template>
  <div>
    <div class="container">
      <div class="row justify-content-center py-5">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-header text-center">
              <h4>Please sign in</h4>
            </div>
            <div class="card-body">
              <form v-on:submit.prevent="login">
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
                <button class="btn btn-lg btn-primary btn-block">Sign in</button>
              </form>
            </div>
            <div class="card-footer" v-if="message">
              <p class="text-center">{{message}}</p>
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
      email: '',
      password: '',
      method: 'POST',
      errors: {},
      message: ''
    }
  },
  methods:{
    // async login() {
    //   await byMethod(this.method, '/api/login',
    //     {
    //       email:this.email,
    //       password:this.password,
    //     })
    //     .then((res) => {
    //       localStorage.setItem('user-token', res.data.token)
    //       this.$store.dispatch('user', res.data.user)
    //       this.$router.push({name: 'dashboard'})
    //     })
    //     .catch((err) => {
    //         console.log(err)
    //     })
    // },
    login: function () {
      let email = this.email
      let password = this.password
      this.$store.dispatch('login', { email, password })
      .then(() => this.$router.push('/dashboard'))
      .catch(error => {
        if(error.response.status === 422) {
          this.errors = error.response.data.errors
        }
        if(error.response.status == 401){
          this.message = 'Waning! Username or Password Incorrect.'
        }
      })
    }
  }
}
</script>

<style lang="scss">
  .card-footer{
    color: red;
  }
</style>