<template>
    <div>
        <div class="row" style="margin-left: 0px; justify-content: space-between;">
            <div class="form-group">
                <label for="show">Show rows</label>
                <select name="number_of_rows" id="select-rows" @change="$emit('getRows', $event)" class="form-control" :value="total_rows">
                    <option value="3">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="500">500</option>
                </select>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="search">Search</label>
                    <input type="text" name="Search" :value="search" @keyup="$emit('liveSearch', $event)" class="form-control" placeholder="search">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'search',
        data(){
            return {}
        },
        props: ['total_rows', 'search']
    }
</script>

<style lang="scss" scoped>

</style>