<template>
    <ul class="list-group">
        <li v-for="item in this.dataTree" :key="item.id" class="list-group-item">
            <a href="#" class="active">{{ item.name }}</a>
            <div class="buttons text-right">
                <button class="btn btn-success">
                    <router-link :to="`/category/${item.id}/edit`" style="color:darkgreen">Edit</router-link>
                </button>
                <input type="submit" value="Delete"  @click="deleteCategory(item.id)" class="btn btn-danger"/>
            </div>
            <CategoriesTree v-if="item.sub_options && item.sub_options.length>0" :dataTree="item.sub_options"></CategoriesTree>
        </li>
    </ul>
</template>

<script>
    export default {
        name: "CategoriesTree",
        props: ["dataTree"],
        computed:{
            currentUser(){
                return this.$store.getters.currentUser;
            },
        },
        methods:{
            deleteCategory(id){
                axios.delete(`/api/category/${id}`,{
                    headers:{
                        "Authorization":`Bearer ${this.currentUser.token}`,
                    }
                })
                    .then(res => {
                        this.$store.commit('updateCategories', res.data.data);
                    })
                    .catch(err => { console.error(err) })
            }
        }
    }

</script>

<style scoped>

</style>