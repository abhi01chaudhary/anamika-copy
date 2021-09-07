<template>
    <div>
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">
                    <h3>All Products</h3>
                </span>
                <div>
                    <router-link to="/products/create" class="btn btn-primary">
                        New Product
                    </router-link>
                </div>
            </div>
            <div class="panel-body">
                <search :total_rows="total_rows" :search="search" @getRows="getRows($event)" @liveSearch="liveSearch" />
                <table class="table table-link">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Item Code</th>
                            <th>Description</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in model.data" :key="item.data" @click="detailsPage(item)">
                            <td class="w-1">{{item.id}}</td>
                            <td class="w-3">{{item.item_code}}</td>
                            <td class="w-9">{{item.description}}</td>
                            <td class="w-3">{{item.unit_price}}</td>
                            <td class="w-3">{{item.quantity}}</td>
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
    import search from '../../components/layouts/search'

    export default {
        name: 'ProductIndex',
        components: {
            search
        },
        data(){
            return {
                model: {
                    data: []
                },
                search: '',
                total_rows: 50
            }
        },
        beforeRouteEnter (to, from, next) {
            get('/api/products', to.query).
                then((res) => { 
                  next(vm => vm.setData(res))  
                })
        },
        beforeRouteUpdate(to, from, next){
            get('/api/products', to.query).
                then((res) => {
                  this.setData(res)
                  next()
                })
        },
        methods:{
            getRows(event) {
                this.total_rows = event.target.value
                axios.get('/api/products/get/total_rows', {params: { total_rows : this.total_rows}})
                    .then(res => {
                        this.setData(res)
                    })
                    .catch(error => {
                        console.log(error)
                    });
            },
            liveSearch(event){
                this.search = event.target.value
                axios.get('/api/products/live/search', { params: { q: this.search } })
                    .then(res => {
                        this.setData(res)
                    })
                    .catch(error => {
                        console.log(error)
                    });
            },
            setData(res){
                Vue.set(this.$data, 'model', res.data.results)
                this.page = this.model.current_page
                this.$bar.finish()
            },
            detailsPage(item) {
                this.$router.push(`/products/${item.id}`)
            },
            nextPage() {
                if(this.model.next_page_url) {
                    const query = Object.assign({}, this.$route.query)
                    query.page = query.page ? (Number(query.page) + 1) : 2

                    this.$router.push({
                        path: '/products',
                        query: query
                    })
                }
            },
            prevPage () {
                if(this.model.prev_page_url) {
                    const query = Object.assign({}, this.$route.query)
                    query.page = query.page ? (Number(query.page) - 1) : 1

                    this.$router.push({
                        path: '/products',
                        query: query
                    })
                }
            }
        }
    }
</script>

<style lang="scss" scoped>

</style>