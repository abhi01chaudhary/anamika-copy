<template>
    <div>
        <div class="panel" v-if="show">
            <div class="panel-heading">
                <span class="panel-title">{{model.number}}</span>
                <div>
                    <router-link to="/products" class="btn">Back</router-link>
                    <router-link :to="`/products/${model.id}/edit`" class="btn">Edit</router-link>
                    <button class="btn btn-error" @click="deleteItem">Delete</button>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <p>{{ model.item_code }}</p>|
                    <p>{{ model.description }}</p>|
                    <p>{{ model.unit_price }}</p>|
                    <p>{{ model.quantity }}</p>|
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {get, byMethod} from '../../lib/api'

    export default {
        name: 'ProductShow',
        data(){
            return {
                model: {
                    product: {}
                },
                show: false
            }
        },
        beforeRouteEnter (to, from, next) {
            get(`/api/products/${to.params.id}`)
                .then((res) => {
                    next(vm => vm.setData(res))
                })
        },
        beforeRouteUpdate(to, from, next){
            this.show = false
            get(`/api/products/${to.params.id}`)
                .then((res) => {
                    vm.setData(res)
                    next()
                })
        },
        methods: {
            setData(res) {
                Vue.set(this.$data, 'model', res.data.model)
                this.show = true
                this.$bar.finish()
            },
            deleteItem(){
                byMethod('delete',`/api/products/${this.model.id}`)
                    .then((res) => {
                        if(res.data.deleted) {
                            this.$router.push('/products')
                        }
                    })
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>