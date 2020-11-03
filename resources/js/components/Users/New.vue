<template>
    <div class="user-new">
        <form @submit.prevent="submitForm">
            <div v-if="errors.length" class="alert alert-danger">
                <b>Please fix following errors</b>
                <ul>
                    <strong>
                        <li v-for="(error,index) in errors" :key="index">{{ error }}</li>
                    </strong>
                </ul>
            </div>
            <table class="table">
                <tr>
                    <th>Name</th>
                    <td>
                        <input type="text" class="form-control" v-model="user.name" placeholder="Name"/>
                    </td>
                    <td>
                         <span v-if="errors.name">
                            {{ errors.name }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>
                        <input type="text" class="form-control" v-model="user.email" placeholder="Email"/>
                    </td>
                    <td>
                         <span v-if="errors.email">
                            {{ errors.email }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <th>Password</th>
                    <td>
                        <input type="text" class="form-control" v-model="user.password" placeholder="Password"/>
                    </td>
                    <td>
                        <span v-if="errors.password">
                            {{ errors.password }}
                        </span>
                    </td>
                </tr>
                <tr>
                <tr>
                    <th>Role</th>
                    <td>
                        <select class="form-control" v-model="user.role" @change="changeRole">
                            <option label="admin">admin</option>
                            <option label="superadmin">superadmin</option>
                        </select>
                    </td>
                    <td>
                    <span v-if="errors.password">
                        {{ errors.password }}
                    </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <router-link to="/users" class="btn btn-danger">Cancel</router-link>
                    </td>
                    <td class="text-right">
                        <input type="submit" value="Create" class="btn btn-primary">
                    </td>
                </tr>
            </table>
            <div class="alert alert-success" v-if="status">
                <b>Please check your Email for Confirm Registration</b>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: 'new',
    computed: {
        currentUser() {
            return this.$store.getters.currentUser;
        }
    },
    data() {
        return {
            user: {
                name: '',
                email: '',
                password: '',
                role: '',
                status:'',
            },
            status: false,
            errors: [],
        };
    },
    methods: {
        submitForm: function (e) {

            this.errors = [];

            if (!this.user.name) {
                this.errors.push("Name required")
            } else if (this.user.name.length < 3) {
                this.errors.push("Name must contains minimum 3 characters")
            }
            if (!this.user.email) {
                this.errors.push("Email required")
            } else if (this.user.email.length < 4) {
                this.errors.push("Email must contains minimum 4 characters")
            }
            if (!this.user.password) {
                this.errors.push("Password required")
            } else if (this.user.password.length < 4) {
                this.errors.push("Password must contains minimum 3 characters")
            }
            if (!this.user.role) {
                this.errors.push("Role required")
            }
            if (!this.errors.length) {
                axios.post('/api/auth/register', this.$data.user, {

                    headers: {
                        'Authorization': `Bearer ${this.currentUser.token}`
                    }
                })
                    .then((response) => {
                        if (response.data.code === 200) {
                            this.$store.commit('updateUsers', response.data.data);
                            this.status = true
                            this.$router.push('/users');
                        }
                    });
            }
            e.preventDefault();
        },
        changeRole: function (e) {
            this.$data.user.role = e.target.value
        }
    }
}
</script>
