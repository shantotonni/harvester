<template>
    <div>
        <div class="wrapper-page">
            <div class="card overflow-hidden account-card mx-3">
                <div class="bg-primary p-4 text-white text-center position-relative">
                    <h4 class="font-20 m-b-5">Welcome Back !</h4>
                    <p class="text-white-50 mb-4">Sign in to continue to Harvester.</p>
                    <div class="logo logo-admin" style="height: 80px;width:100px"><img :src="`${mainOrigin}assets/images/logo.jpeg`" height="40" alt="logo"></div>
                </div>
                <div class="account-card-content">
                    <form class="form-horizontal m-t-30" @submit.prevent="login" @keydown="form.onKeydown($event)">
                        <div class="form-group">
                            <label for="Username">Username</label>
                            <input type="text" class="form-control" id="Username" :class="{ 'is-invalid': form.errors.has('Username') }" v-model="form.Username" name="Username" placeholder="Username">
                            <div class="error" v-if="form.errors.has('Username')" v-html="form.errors.get('Username')" />
                        </div>
                        <div class="form-group">
                            <label for="Password">Password</label>
                            <input type="password" v-model="form.Password" class="form-control" id="Password" placeholder="Password" :class="{ 'is-invalid': form.errors.has('Password') }">
                            <div class="error" v-if="form.errors.has('Password')" v-html="form.errors.get('Password')" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    name: "Login",
    data(){
        return{
            form : new Form({
                Username : '',
                Password : ''
            }),
        }
    },
    mounted() {
        document.title = 'Login | Harvester';
    },
    methods: {
        login(){
            this.form.post('/api/dashboard-login').then((response)=>{
                if (response.data.status === 401){
                    this.$toaster.error('Userid or Password Not Match')
                }else {
                    this.$store.commit('setToken',response.data.token);
                    this.$router.push({name : 'Dashboard'})
                    window.location.href = '/harvester/dashboard';
                    this.$toaster.success('Successfully Logged In')
                }
            }).catch((error)=>{
                this.$toaster.error('Email or Password Wrong')
            })
        },
    }
}
</script>

