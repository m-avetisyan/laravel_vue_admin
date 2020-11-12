<template>
    <div>
        <div class="btn-wrapper">
            <router-link to="/post/new" class="btn btn-primary">New</router-link>
        </div>
        <template v-if="posts.length">
            <div v-if="errors.length" class="alert alert-danger">
                <b>Please fix following errors</b>
                <ul>
                    <strong><li v-for="(error,index) in errors" :key="index">{{ error }}</li></strong>
                </ul>
            </div>
            <table class="table">
                <thead>
                <tr>

                    <th>ID</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th colspan="3">Actions</th>
                </tr>
                </thead>

            <tbody>
                    <tr v-for="(post,index) in posts" :key="index">
                        <td>{{ post.id}}</td>
                        <td>{{ post.name }}</td>
                        <td>{{ post.description}}</td>
                        <td>
                            <img :src="`/uploads/posts/${post.image}`" style="width:100px"/>
                        </td>
                        <td>
                            <router-link :to="`/post/${post.id}`">Show</router-link>
                        </td>
                        <td>
                            <router-link :to="`/post/${post.id}/edit`">Edit</router-link>
                        </td>
                        <td>
                             <input type="submit" value="Delete"  @click="deletePost(post.id,index)" class="btn btn-danger"/>
                        </td>
                    </tr>
            </tbody>
        </table>
        </template>
        <template v-else>No posts available</template>
    </div>
</template>

<script>
    export default {
        name: 'list',
      mounted() {
        if (this.posts.length) {
          return;
        }
        this.$store.dispatch('getPosts');
      },
        data() {
            return {
                errors: [],
            };
        },
        computed:{
            currentUser(){
                return this.$store.getters.currentUser;
            },
            posts(){
                return this.$store.getters.posts;
            }
        },
        methods:{
            deletePost(id,index){
                axios.delete(`/api/post/${id}`,{
                    headers:{
                        "Authorization":`Bearer ${this.currentUser.token}`,
                    }
                })
                .then(res => {
                    if(this.posts && this.posts.length > 0) {
                        this.posts.splice(index, 1);
                    }
                    this.$store.commit('updatePosts', res.data.data);
                })
                .catch(err => { console.error(err) })
            },
        }
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

</style>
