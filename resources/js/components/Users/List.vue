<template>
    <div>
        <div class="btn-wrapper">
            <router-link to="/users/new" class="btn btn-primary" v-if="currentUser.role==='superadmin'">New
            </router-link>
        </div>
        <table class="table">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th colspan="3">Actions</th>
                </thead>
                <tbody>
                    <template v-if="!users.length">
                        <tr>
                            <td colspan="4" class="text-center">No users Available</td>
                        </tr>
                    </template>
                    <template v-else-if="currentUser.role === 'superadmin'">
                        <tr v-for="(user,index) in users" :key="index">
                            <td>{{ user.id }}</td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ user.role }}</td>
                            <td>
                                <toggle-button
                                               :disabled="user.role==='superadmin'? true:false "
                                               :value="Boolean(user.status)" @change="changeState(user.id,$event)"/>
                            </td>

                            <td>
                                <router-link :to="`/users/${user.id}`">Show</router-link>
                            </td>
                            <td>
                                <router-link :to="`/users/${user.id}/edit`">Edit</router-link>
                            </td>
                            <td>
                                <input type="submit" value="Delete" @click="deleteAccount(index,user.id)"
                                       class="btn btn-danger"/>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr v-for="(user,index) in users" :key="index" v-if="user.id===currentUser.id">
                            <td>{{ user.id }}</td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>{{ user.role }}</td>
                            <td>
                                <toggle-button  :disabled="user.role==='admin'? true:false " :value="Boolean(user.status)"
                                               @change="changeState(user.id,$event)"/>
                            </td>
                            <td>
                                <router-link :to="`/users/${user.id}`">Show</router-link>
                            </td>
                            <td>
                                <router-link :to="`/users/${user.id}/edit`">Edit</router-link>
                            </td>
                        </tr>
                </template>
                </tbody>
        </table>
        <modal name="video">
            <form id="myForm">
                <div class="form-group">
                    <h4 style="text-align: center">Deactivation Message</h4>
                </div>
                <div class="form-group">
                    <textarea required class="form-control alert-danger" v-model="deactUserMessage" rows="8"></textarea>
                </div>

                <div class="form-group send_message">
                    <button type="button" @click="changeUserStatus"  class="btn btn-danger" style="text-align:center">Save</button>
                </div>
            </form>
        </modal>
    </div>
</template>

<script>

export default {

    name: 'list',
    mounted() {
        if (this.users.length) {
            return;
        }
        this.$store.dispatch('getUsers');
    },
    computed: {
        users() {
            return this.$store.getters.users
        },
        currentUser() {
            return this.$store.getters.currentUser;
        }
    },
    data() {
        return {
            deactUserID: null,
            deactUserMessage:"",
            deactUserStatus:false
        }
    },
    methods: {
        deleteAccount(index, id) {
            axios.delete(`/api/user/${id}`, {
                headers: {
                    "Authorization": `Bearer ${this.currentUser.token}`,
                }
            })
                .then(res => {
                    this.$store.commit('updateUsers', res.data.data);
                })
                .catch(err => {
                    console.error(err)
                })
        },
        changeState(id,event) {
            let value = event.value;
            if(value){
                this.$modal.hide('video');
                axios.put(`/api/users/${id}/status`,{'status' : value },{
                    headers:{
                        "Authorization":`Bearer ${this.currentUser.token}`,
                    }
                })
                    .then(res => {
                        this.$store.commit('updateUsers', res.data.data);
                        this.$store.commit('deactivateMessage', this.deactUserMessage);
                    })
                    .catch(err => { console.error(err) })
            }
            else{
                this.$modal.show('video');
                this.deactUserID = id
                this.deactUserStatus = value
            }
        },
        changeUserStatus(){
            axios.put(`/api/users/${this.deactUserID}/status`,{'status' : this.deactUserStatus,'deact_message': this.deactUserMessage },{
                headers:{
                    "Authorization":`Bearer ${this.currentUser.token}`,
                }
            })
            .then(res => {
                this.$store.commit('updateUsers', res.data.data);
                // this.$store.commit('deactivateMessage', this.deactUserMessage);
                // this.deactUserMessage = "";
                this.$modal.hide('video');
            })
            .catch(err => { console.error(err) })
        }
    }
}
</script>
<style scoped>

.btn-wrapper {
    text-align: right;
    margin-bottom: 20px;
}

table thead, table tbody {
    text-align: center !important
}

table tr td {
    vertical-align: middle
}

.table thead th {
    text-align: center
}
.send_message{
    text-align:center
}
</style>
