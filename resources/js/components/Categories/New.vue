<template>
    <div class="category-new">
        <form @submit.prevent="submitForm">
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
                        <input type="text" class="form-control" v-model="category.name" placeholder="Name"/>
                    </td>
                    <td>
                         <span v-if="errors.name">
                            {{ errors.name }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Parent Category</th>
                    <td>
                        <treeselect
                            :options="categories"
                            :value="value"
                            :normalizer="normalizer"
                            @input="changeCategory"
                        />
                    </td>
                </tr>
                <tr>
                    <td>
                        <router-link to="/category" class="btn btn-danger">Cancel</router-link>
                    </td>
                    <td class="text-right">
                        <input type="submit" value="Create" class="btn btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</template>

<script>

// import the styles
import '@riophae/vue-treeselect/dist/vue-treeselect.css'
    export default {
        name:'new',
        computed:{
            currentUserToken(){
                return this.$store.getters.currentUser.token;
            },
        },
        mounted(){
            this.AllCategories();
        },
        data() {
            return {
                category: {
                    name:'',
                    parent_id: null,
                },
                errors: [],
                categories:[],
                value:null,
                // options:[],
                // another_options: [
                //     {
                //         key: 'a',
                //         name: 'a',
                //         subOptions: [ {
                //             key: 'aa',
                //             name: 'aa',
                //         },{
                //             key: 'bb',
                //             name: 'bb',
                //         },{
                //             key: 'cc',
                //             name: 'cc',
                //         } ],
                //     },
                //     {
                //         key: 'a',
                //         name: 'a',
                //         subOptions: [ {
                //             key: 'aa',
                //             name: 'aa',
                //         } ],
                //     },
                //     {
                //         key: 'a',
                //         name: 'a',
                //         subOptions: [ {
                //             key: 'aa',
                //             name: 'aa',
                //         } ],
                //     }
                // ],
                normalizer(node) {
                    return {
                        id: node.id,
                        label: node.name,
                        children: node.sub_options,
                    }
                },
            }
        },
        methods:{
            changeCategory(e){
                this.category.parent_id = e;
            },
            submitForm: function (e) {

                this.errors = [];

                if (!this.category.name) {
                    this.errors.push("Name required")
                }else if(this.category.name.length < 3){
                    this.errors.push("Name must contains minimum 3 characters")
                }
                if (!this.errors.length) {
                    axios.post('/api/category/new', this.$data.category,{
                        headers:{
                            'Authorization':`Bearer ${this.currentUserToken}`
                        }
                    })
                    .then((response) => {
                        if(response.data.code === 200){
                            this.$store.commit('updateCategories',response.data.data);
                            this.$router.push('/category');
                        }
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
            }
        }
    }
</script>
