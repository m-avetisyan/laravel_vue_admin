<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Welcome {{currentUser.name}}</div>

                    <div class="card-body">
                       This is a home page
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name:'home',
        computed:{
            currentUser(){
                return this.$store.getters.currentUser
            }
        }
    };
</script>
