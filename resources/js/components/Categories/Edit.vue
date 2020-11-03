<template>
    <div class="category-view" v-if="category">
        <form @submit.prevent="updateForm">
            <div v-if="errors.length" class="alert alert-danger" >
                <b>Please fix following errors</b>
                <ul>
                    <strong><li v-for="(error,index) in errors" :key="index">{{ error }}</li></strong>
                </ul>
                </div>
            <table class="table table-bordered">
                <tr>
                    <th>Name</th>
                    <td>
                        <input type="text"  class="form-control" v-model="category.name">
                    </td>
                </tr>
            </table>
            <input type="submit" value="Update" class="btn btn-primary">
        </form>
    </div>
</template>

<script>
    export default {
        name: 'edit',
        created() {
            axios.get(`/api/category/${this.$route.params.id}`,{
                headers:{
                    "Authorization":`Bearer ${this.currentUser.token}`
                }
            })
                .then((response) => {
                    this.category = response.data.data
                });

        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
            categories() {
                return this.$store.getters.categories;
            }
        },
        data() {
            return {
                category: {
                    name:'',
                },
                errors: []
            };
        },
        methods:{

            updateForm: function (e) {

                if (!this.category.name) {
                    this.errors.push ("Name required")
                }

                if (!this.errors.length) {
                    axios.put(`/api/category/${this.$route.params.id}`, this.$data.category,{
                        headers:{
                            'Authorization':`Bearer ${this.currentUser.token}`
                        }
                    })
                    .then((response) => {
                        this.$store.commit('updateCategories',response.data.data);
                        this.$router.push('/category');
                    });
                }
                e.preventDefault();
            },
        }
    }
</script>
<style scoped>

    .table th{
        vertical-align:middle
    }

</style>
