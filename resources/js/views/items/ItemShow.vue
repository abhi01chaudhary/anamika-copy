<template>
    <div>
        <div class="panel" v-if="show">
            <div class="panel-heading">
                <span class="panel-title">{{model.id}}</span>
                <div>
                    <router-link to="/items" class="btn">Back</router-link>
                    <router-link :to="`/items/${model.id}/edit`" class="btn">Edit</router-link>
                    <button class="btn btn-error" @click="deleteItem">Delete</button>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <p>{{ model.item_name }}</p>|
                    <p>{{ model.description }}</p>|
                    <p>{{ model.unit_price }}</p>|
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {get, byMethod} from '../../lib/api'

    export default {
        name: 'ItemShow',
        data(){
            return {
                show:false,
                model: {
                    item: {}
                }
            }
        },
        beforeRouteEnter(to, from, next) {
            get(`/api/items/${to.params.id}`)
                .then((res) => {
                    next(vm => vm.setData(res))
                })
        },
        beforeRouteUpdate(to, from, next) {
            this.show = false
            get(`/api/items/${to.params.id}`)
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
                byMethod('delete', `/api/items/${this.model.id}`)
                    .then((res) => {
                        if(res.data.deleted) {
                            this.$router.push('/items')
                        }
                    })
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>