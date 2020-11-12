<template>
    <div class="post-new">
        <form action="#"  @submit.prevent="submitForm">
            <div v-if="errors.length" class="alert alert-danger" >
                <b>Please fix following errors</b>
                <ul>
                    <strong><li v-for="(error,index) in errors" :key="index">{{ error }}</li></strong>
                </ul>
            </div>
            <table class="table">
                <tr>
                    <th>Name</th>
                    <td>
                        <input type="text" class="form-control" v-model="post.name" placeholder="Name"/>
                    </td>
                    <td>
                         <span v-if="errors.name">
                            {{ errors.name }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>
                        <textarea rows="3" class="form-control" v-model="post.description"></textarea>
                    </td>
                    <td>
                         <span v-if="errors.description">
                            {{ errors.description }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Image</th>
                    <td>
                        <input type="file"   accept="image/*" @change="handleFileUpload">
                    </td>
                    <td>
                         <span v-if="errors.image">
                            {{ errors.image }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>
                        <treeselect
                            :options="categories"
                            :value="value"
                            :normalizer="normalizer"
                            @input="changeCategory"
                        />
                    </td>
                    <td>
                         <span v-if="errors.category">
                            {{ errors.category }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <router-link to="/post" class="btn btn-danger">Cancel</router-link>
                    </td>
                    <td class="text-right">
                        <input type="submit" class="btn btn-primary" value="Add">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</template>

<script>

    export default {
        name:'new',
        computed:{
            currentUser(){
                return this.$store.getters.currentUser;
            }
        },
        mounted(){
            this.AllCategories();
        },
        data() {
            return {
                post: {
                    name:'',
                    description: '',
                    image:'',
                    category:''
                },
                categories:[],
                value: null,
                errors: [],
                normalizer(node) {
                    return {
                        id: node.id,
                        label: node.name,
                        children: node.sub_options,
                    }
                },
            };
        },
        methods:{
            handleFileUpload(event){
                this.post.image = event.target.files[0];
            },
            submitForm: function (e) {

                this.errors = [];

                if (!this.post.name) {
                    this.errors.push("Name required")
                }else if(this.post.name.length < 3){
                    this.errors.push("Name must contains minimum 3 characters")
                }
                if (!this.post.description) {
                    this.errors.push("Description required")
                }
                if (!this.post.image) {
                    this.errors.push("Image required")
                }
                if (!this.post.category) {
                    this.errors.push("Category for added post required")
                }
                if (!this.errors.length) {
                    const data = new FormData();
                    data.append('name', this.post.name);
                    data.append('description', this.post.description);
                    data.append('image', this.post.image);
                    data.append('category_id', this.post.category);
                    axios.post('/api/post/new', data,{
                        headers:{
                            'Authorization':`Bearer ${this.currentUser.token}`
                        }
                    })
                    .then((response) => {
                        console.log(response.data)
                        this.$store.commit('updatePosts',response.data.data);
                        this.$router.push('/post');
                    });
                }
                e.preventDefault();
            },
            AllCategories(){
                axios.get('/api/category',{
                    headers:{
                        'Authorization':`Bearer ${this.$store.getters.currentUser.token}`
                    }
                }).then((response) => {
                    this.categories = response.data.data;
                })
            },
            changeCategory(e){
                this.post.category = e;
            },
        }
    }
</script>
