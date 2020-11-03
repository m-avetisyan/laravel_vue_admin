<template>
    <div class="login row justify-content-center">
        <div class="col-md-4 col-lg-4">
            <div class="card">
                <div class="card-header text-cente">Login</div>
                <div class="card-body">
                    <div class="form-group row" v-if="authError">
                        <div class="alert alert-danger">
                            {{ authError }}
                        </div>
                    </div>
                    <form @submit.prevent="authenticate">
                        <div class="form-group row">
                            <label for="email">Email:</label>
                            <input type="email" v-model="form.email" class="form-control" placeholder="Email Address">
                        </div>
                        <div class="form-group row">
                            <label for="password">Password:</label>
                            <input type="password" v-model="form.password" class="form-control" placeholder="Password">
                        </div>
                        <div class="form-group text-center">
                            <input type="submit"  class="btn btn-success" value="Login">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import {login} from '../../auth';
    export default {
        name: "login",
        computed: {
            authError() {
                return this.$store.getters.authError;
            }
        },
        data() {
            return {
                form: {
                    email: '',
                    password: ''
                },
                error: null
            };
        },
        methods: {
            authenticate() {
                this.$store.dispatch('login');
                login(this.$data.form)
                    .then((res) => {
                        this.$store.commit("loginSuccess", res);
                        this.$router.push({path: '/'});
                    }).catch(err => {
                        this.form.email="";
                        this.form.password=""
                        this.$store.commit('loginFailed', err);
                    })

            }
        },
    }
</script>

<style scoped>
    .error {
        text-align: center;
        color: red;
    }
</style>
