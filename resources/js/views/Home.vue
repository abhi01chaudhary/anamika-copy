<template>
    <div>
        <router-view></router-view>
    </div>
</template>

<script>
import navbar from '../components/layouts/Navbar.vue'
    export default {
        name: 'Home',
        data(){
            return {}
        },
        components:{
            navbar
        }
    }
</script>

<style lang="scss" scoped>

</style>