<template>
    <div class="user-view" v-if="user">
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
                        <input type="text"  class="form-control" v-model="user.name">
                    </td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
                        <input type="text" class="form-control" v-model="user.email">
                    </td>
                </tr>

                <tr v-if="currentUser.role==='superadmin'">
                    <th>Role</th>
                    <td>
                      <select class="form-control" v-model="user.role" @change="changeRole">
                        <option label="admin">admin</option>
                        <option label="superadmin">superadmin</option>
                      </select>
                    </td>
                </tr>
              <tr>
                <th>Password</th>
                <td>
                  <input type="password" class="form-control" v-model="user.password">
                </td>
              </tr>
              <tr>
                <th>Confirm Password</th>
                <td>
                  <input type="password" class="form-control" v-model="user.password_confirmation">
                </td>
              </tr>
                <tr>
                    <th>Action</th>
                    <td>
                        <input type="submit" value="Update" class="btn btn-primary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'edit',
        created() {
            axios.get(`/api/users/${this.$route.params.id}`,{
                headers:{
                    "Authorization":`Bearer ${this.currentUser.token}`
                }
            })
                .then((response) => {
                    this.user = response.data.data
                });

        },
        computed: {
            currentUser() {
                return this.$store.getters.currentUser;
            },
        },
        data() {
            return {
                user: {
                    name:'',
                    email:'',
                    role:'',
                    password:'',
                  password_confirmation:''
                },
                errors: []
            };
        },
        methods:{

            updateForm: function (e) {

                if (!this.user.name) {
                    this.errors.push ("Name required")
                }
                if (!this.user.email) {
                    this.errors.push("Email required")
                }
                if (!this.errors.length) {
                    axios.put(`/api/users/${this.$route.params.id}`, this.$data.user,{
                        headers:{
                            'Authorization':`Bearer ${this.currentUser.token}`
                        }
                    })
                    .then((response) => {
                        this.$store.commit('updateUsers',response.data.data);
                        this.$router.push('/users');
                    });
                }
                e.preventDefault();
            },
          changeRole:function(e){
            this.$data.user.role = e.target.value
          }
        }
    }
</script>
<style scoped>

    .table th{
        vertical-align:middle
    }

</style>
