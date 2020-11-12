<template>
    <div>
        <div class="btn-wrapper">
            <router-link to="/category/new" class="btn btn-primary">Add New</router-link>
        </div>
        <template v-if="categories">
            <ul>
                <CategoriesTree :dataTree="categories"></CategoriesTree>
            </ul>
        </template>
        <template v-else>
            <div class="alert alert-dark">
                There is no Categories
            </div>
        </template>
    </div>
</template>

<script>
import CategoriesTree from "./CategoriesTree";

export default {
    name: 'list',
    components:{
        CategoriesTree
    },
    mounted() {
        if (this.categories.length) {
            return;
        }
        this.$store.dispatch('getCategories');
    },
    computed:{
        currentUser(){
            return this.$store.getters.currentUser;
        },
        categories(){
            return this.$store.getters.categories;
        }
    },
}
</script>
<style scoped>

.btn-wrapper {
    text-align: right;
    margin-bottom: 20px;
}
table thead, table tbody{
    text-align:center!important
}
table tr td{
    vertical-align:middle
}
.table thead th{
    text-align:center
}
.list-group-collapse
{
    overflow: hidden;
}

.list-group-collapse li  ul {
    margin-left: -15px;
    margin-right: -15px;
    margin-bottom: -11px;
    border-radius: 0px;
}

.list-group-collapse li ul
{
    border-radius: 0px !important;
    margin-top: 8px;
}

.list-group-collapse li  ul li {
    border-radius: 0px !important;
    border-left: none;
    border-right: none;
    padding-left: 32px;
}

#subgroup
{
    display: none;
}
.slide-enter-active {
    -moz-transition-duration: 0.7s;
    -webkit-transition-duration: 0.7s;
    -o-transition-duration: 0.7s;
    transition-duration: 0.7s;
    -moz-transition-timing-function: linear;
    -webkit-transition-timing-function: linear;
    -o-transition-timing-function: linear;
    transition-timing-function: linear;
}

.slide-leave-active {
    -moz-transition-duration: 0.7s;
    -webkit-transition-duration: 0.7s;
    -o-transition-duration: 0.7s;
    transition-duration: 0.7s;
    -moz-transition-timing-function: linear;
    -webkit-transition-timing-function: linear;
    -o-transition-timing-function: linear;
    transition-timing-function: linear
}

.slide-enter-to, .slide-leave {
    max-height: 100px;
    overflow: hidden;
}

.slide-enter, .slide-leave-to {
    overflow: hidden;
    max-height: 0;
}
</style>
