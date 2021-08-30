<template>
    <div>
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Add Customer</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" class="form-control" v-model="form.firstname" placeholder="First Name">
                            <small class="error-control" v-if="errors.firstname">
                                {{errors.firstname[0]}}
                            </small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" class="form-control" v-model="form.lastname" placeholder="Last Name">
                            <small class="error-control" v-if="errors.lastname">
                                {{errors.lastname[0]}}
                            </small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>
                                Email
                            </label>
                            <input type="email" class="form-control" v-model="form.email" placeholder="Email">
                            <small class="error-control" v-if="errors.email">
                                {{errors.email[0]}}
                            </small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>
                                Phone
                            </label>
                            <input type="text" class="form-control" v-model="form.phone" placeholder="Phone">
                            <small class="error-control" v-if="errors.phone">
                                {{errors.phone[0]}}
                            </small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" class="form-control" v-model="form.address" placeholder="Address">
                            <small class="error-control" v-if="errors.address">
                                {{errors.address[0]}}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer flex-end">
                <div>
                    <button class="btn btn-primary" :disabled="isProcessing" @click="onSave">Save</button>
                    <button class="btn" :disabled="isProcessing" @click="onCancel">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {get, byMethod } from '../../lib/api'
    export default {
        data(){
            return {
                form: {},
                isProcessing: false,
                store: `/api/customers/store`
            }
        },
        methods:{
            errors(){
                console.log('here');
            },
            onSave() {
                this.errors = {}
                this.isProcessing = true

                axios.post(this.store, this.form)
                .then((res) => {
                    console.log(this.form);
                    //Perform Success Action
                })
                .catch((error) => {
                    // error.response.status Check status code
                    if(error.response.status === 422) {
                        this.errors = error.response.data.errors
                    }
                    this.isProcessing = false
                }).finally(() => {
                    //Perform action in always
                });

            },
            onCancel() {
                // console.log(this.$route.meta.mode);
                // if(this.$route.meta.mode === 'edit') {
                //     this.$router.push(`${this.resource}/${this.form.id}`)
                // } else {
                //     this.$router.push(`${this.resource}`)
                // }
                this.$router.push(`/customers`)
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>