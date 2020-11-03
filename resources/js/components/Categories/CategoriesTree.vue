<template>
    <ul class="list-group">
        <li v-for="item in this.dataTree" :key="item.id" class="list-group-item">
            <a href="#" class="active">{{ item.name }}</a>
            <div class="buttons text-right">
                <button class="btn btn-success">
                    <router-link :to="`/category/${item.id}/edit`" style="color:darkgreen">Edit</router-link>
                </button>
                <button class="btn btn-danger" >
                    <router-link :to="`/category/${item.id}`" style="color:blue">Delete</router-link>
                </button>
            </div>
            <CategoriesTree v-if="item.sub_options.length > 0" :dataTree="item.sub_options"></CategoriesTree>
        </li>
    </ul>
</template>

<script>
    export default {
        name: "CategoriesTree",
        props: ["dataTree"]
    }
</script>

<style scoped>

</style>