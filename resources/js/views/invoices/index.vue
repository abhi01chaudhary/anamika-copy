<template>
    <div>
        <div class="panel">
            <div class="panel-heading">
                <span class="panel-title">
                    <h3>All Invoices</h3>
                </span>
                <div>
                    <router-link to="/invoices/create" class="btn btn-primary">
                        New Invoice
                    </router-link>
                </div>
            </div>
            <div class="panel-body">
                <search 
                    :total_rows="total_rows" 
                    :search="search" 
                    :first_date="first_date" 
                    :second_date="second_date" 
                    :status="status"
                    @searchDate="searchDate($event)"
                    @getRows="getRows($event)" 
                    @liveSearch="liveSearch($event)" 
                    @getStatus="getStatus($event)" 
                />
                <table class="table table-link">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Number</th>
                            <th>Customer</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in model.data" :key="item.data" @click="detailsPage(item)">
                            <td class="w-1">{{item.id}}</td>
                            <td class="w-3">{{item.date}}</td>
                            <td class="w-3">{{item.number}}</td>
                            <td class="w-5">{{item.customer.text}}</td>
                            <td class="w-3">{{item.due_date}}</td>
                            <td class="w-3">
                                <span class="badge bg-success text-center" v-if="item.status == 'Paid'">{{item.status}}</span>
                                <span class="badge bg-danger text-center" v-else-if="item.status == 'Un paid'">{{item.status}}</span>
                            </td>
                            <td class="w-3">{{item.total | formatMoney}}</td>
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
        <div class="below panel">   
            <div><span class="badge badge-primary">Total Turnover</span> <strong>: Rs.{{totalTurnOver}}</strong></div>
            <div><span class="badge badge-success">Total Paid</span><strong>: Rs.{{paidTurnOver}}</strong></div>
            <div><span class="badge badge-danger">Total Unpaid</span><strong>: Rs.{{unPaidTurnOver}}</strong></div>
        </div>
    </div>
</template>
<script type="text/javascript">
    import Vue from 'vue'
    import { get } from '../../lib/api'
    import search from '../../components/layouts/InvoiceSearch'

    export default {
        components:{
            search
        },
        data () {
            return {
                model: {
                    data: []
                },
                total_rows: 15,
                search: '',
                first_date: '',
                second_date: '',
                status: '',
                paidTurnOver: '',
                unPaidTurnOver: '',
                totalTurnOver: '',
            }
        },
        beforeRouteEnter(to, from, next) {
            get('/api/invoices', to.query)
                .then((res) => {
                    next(vm => vm.setData(res))
                })
        },
        beforeRouteUpdate(to, from, next) {
            get('/api/invoices', to.query)
                .then((res) => {
                    this.setData(res)
                    next()
                })
        },
        methods: {
            getRows(event) {
                this.total_rows = event.target.value
                axios.get('/api/invoices/get/total_rows', {params: { total_rows : this.total_rows}})
                    .then(res => {
                        this.setData(res)
                    })
                    .catch(error => {
                        console.log(error)
                    });
            },
            liveSearch(event){
                this.search = event.target.value
                axios.get('/api/invoices/live/search', { params: { q: this.search } })
                    .then(res => {
                        this.setData(res)
                    })
                    .catch(error => {
                        console.log(error)
                    });
            },
            searchDate(event){
                axios.get('/api/invoices/date/search', { params: {first_date: event[0], second_date: event[1]} })
                    .then(res => {
                        this.setData(res)
                    })
                    .catch(error => {
                        console.log(error)
                    });
            },
            getStatus(event){
                this.status = event.target.value
                axios.get('/api/invoices/status/search', { params: {status: event.target.value} })
                    .then(res => {
                        this.setData(res)
                    })
                    .catch(error => {
                        console.log(error)
                    });
            },
            detailsPage(item) {
                this.$router.push(`/invoices/${item.id}`)
            },
            setData(res) {
                Vue.set(this.$data, 'model', res.data.results)
                this.page = this.model.current_page
                this.totalTurnOver = res.data.totalTurnOver
                this.paidTurnOver = res.data.paidTurnOver
                this.unPaidTurnOver = res.data.unPaidTurnOver
                this.$bar.finish()
            },
            nextPage() {
                if(this.model.next_page_url) {
                    const query = Object.assign({}, this.$route.query)
                    query.page = query.page ? (Number(query.page) + 1) : 2

                    this.$router.push({
                        path: '/invoices',
                        query: query
                    })
                }
            },
            prevPage () {
                if(this.model.prev_page_url) {
                    const query = Object.assign({}, this.$route.query)
                    query.page = query.page ? (Number(query.page) - 1) : 1

                    this.$router.push({
                        path: '/invoices',
                        query: query
                    })
                }
            }
        }
    }
</script>

<style lang="scss">
    span.badge.text-center {
        display: block;
        width: 50%;
    }
    .below.panel {
        display: flex;
        justify-content: space-between;
    }
</style>