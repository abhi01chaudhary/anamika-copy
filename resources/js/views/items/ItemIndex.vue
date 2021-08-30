<template>
    <div>
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">Items</span>
                <div>
                    <router-link :to="{name: 'items-create'}" class="btn btn-primary">
                        New Item
                    </router-link>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-link">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in model.data" :key="item.data" @click="detailsPage(item)">
                            <td class="w-1">{{item.id}}</td>
                            <td class="w-3">{{item.item_name}}</td>
                            <td class="w-3">{{item.description}}</td>
                            <td class="w-9">{{item.unit_price}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="panel-footer flex-between">
                <div>
                    <small>Showing {{model.from}} - {{model.to}} of {{model.total}}</small>
                </div>
                <div>
                    <button class="btn btn-sm" :disabled="!model.prev_page_url" @click="prevPage">
                        Prev
                    </button>
                    <button class="btn btn-sm" :disabled="!model.next_page_url" @click="nextPage">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {get} from '../../lib/api'

    export default {
        name: 'ItemIndex',
        data(){
            return {
                model: {}
            }
        },
        beforeRouteEnter (to, from, next) {
            get('/api/items', to.query)
                .then((res) => {
                    next(vm => vm.setData(res))
                })
        },
        beforeRouteUpdate(to, from, next) {
            get('/api/items', to.query)
                .then((res) => {
                    this.setData(res)
                    next()
                })
        },
        methods: {
            setData(res){
                this.model = res.data.results
                this.page = this.model.current_page
                this.$bar.finish()
            },
            detailsPage(item){
                this.$router.push(`/items/${item.id}`)
            },
            prevPage(){
                if(this.model.prev_page_url) {
                    const query = Object.assign({}, this.$route.query)
                    query.page = query.page ? (Number(query.page) - 1) : 1

                    this.$router.push({
                        path: '/items',
                        query: query
                    })
                }
            },
            nextPage(){
                if(this.model.next_page_url) {
                    const query = Object.assign({}, this.$route.query)
                    query.page = query.page ? (Number(query.page) + 1) : 2

                    this.$router.push({
                        path: '/items',
                        query: query
                    })
                }
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>