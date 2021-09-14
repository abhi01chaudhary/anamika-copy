<template>
    <div>
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Add Item</span>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label>Item Name</label>
                            <input type="text" class="form-control" v-model="form.item_name" placeholder="Item Name">
                            <small class="error-control" v-if="errors.item_name">
                                {{errors.item_name[0]}}
                            </small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" v-model="form.description" placeholder="Description">
                            <small class="error-control" v-if="errors.description">
                                {{errors.description[0]}}
                            </small>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" class="form-control" v-model="form.unit_price" placeholder="Unit Price">
                            <small class="error-control" v-if="errors.unit_price">
                                {{errors.unit_price[0]}}
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
                store: `/api/items`,
                method: 'POST'
            }
        },
        methods:{
            errors(){
                this.$toaster.success('Some Errors!')
            },
            onSave() {
                this.errors = {}
                this.isProcessing = true
                byMethod(this.method, this.store, this.form)
                .then((res) => {
                    this.$toaster.success('Item Created Successfully!')
                    this.$router.push(`/items`)
                })
                .catch((error) => {
                    // error.response.status Check status code
                    if(error.response.status === 422) {
                        this.$toaster.warning('Please fill in the required fields!')
                        this.errors = error.response.data.errors
                    }
                    this.isProcessing = false
                }).finally(() => {
                    //Perform action in always
                });

            },
            onCancel() {
                this.$router.push(`/items`)
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>