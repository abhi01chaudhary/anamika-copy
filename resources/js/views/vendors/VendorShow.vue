<template>
    <div>
        <div class="panel" v-if="show">
            <div class="panel-heading">
                <span class="panel-title">{{model.number}}</span>
                <div>
                    <router-link to="/vendors" class="btn">Back</router-link>
                    <router-link :to="`/vendors/${model.id}/edit`" class="btn">Edit</router-link>
                    <button class="btn btn-error" @click="deleteItem">Delete</button>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <p>{{ model.firstname }}</p>|
                    <p>{{ model.lastname }}</p>|
                    <p>{{ model.vendor_name }}</p>|
                    <p>{{ model.phone }}</p>|
                    <p>{{ model.address }}</p>|
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {get, byMethod} from '../../lib/api'
    
    export default {
        name: 'vendorshow',
        data(){
            return {
                show:false,
                model: {
                    customer: {}
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/vendors/${to.params.id}`)
                .then((res) => {
                    next(vm => vm.setData(res))
                })
        },
        beforeRouteUpdate(to, from, next) {
            this.show = false
            get(`/api/vendors/${to.params.id}`)
                .then((res) => {
                    this.setData(res)
                    next()
                })
        },
        methods: {
            setData(res) {
                Vue.set(this.$data, 'model', res.data.model)
                this.show = true
                this.$bar.finish()
            },
            deleteItem() {
                byMethod('delete', `/api/vendors/${this.model.id}`)
                    .then((res) => {
                        if(res.data.deleted) {
                            this.$router.push('/vendors')
                        }
                    })
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>