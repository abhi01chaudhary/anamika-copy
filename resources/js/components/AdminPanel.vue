<template>
    <div>
        <!-- Page Wrapper -->
        <div id="wrapper" v-if="isLoggedIn">

            <Sidebar />

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <TopBar />

                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                            <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                    class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                        </div>

                        <router-view></router-view>

                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

            <Footer />

            </div>
            <!-- End of Content Wrapper -->
        </div>

        <div id="wrapper" v-if="!isLoggedIn">
            <p>You're not logged in!</p>
        </div>
    </div>
</template>

<script>
    import TopBar from '../components/layouts/TopBar.vue'
    import Sidebar from '../components/layouts/Sidebar.vue'
    import Footer from '../components/layouts/Footer.vue'
    import {mapGetters} from 'vuex'

    export default {
        name: 'AdminPanel',
        components:{
            TopBar,
            Sidebar,
            Footer
        },
        computed: {
            ...mapGetters(['isLoggedIn'])
        }
    }
</script>

<style lang="scss" scoped>

</style>