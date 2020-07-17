<template>
    <form>
        <span v-if="error" class="text-danger">{{ error }}</span>
        <div class="form-group">
            <label for="sign-up-name">Name</label>
            <input
                type="text"
                class="form-control"
                id="sign-up-name"
                aria-describedby="emailHelp"
                placeholder="Enter your name"
                v-model="name"
            >
        </div>
        <div class="form-group">
            <label for="sign-up-email">Email</label>
            <input
                type="email"
                class="form-control"
                id="sign-up-email"
                aria-describedby="emailHelp"
                placeholder="Enter email"
                v-model="email"
            >
        </div>
        <div class="form-group">
            <label for="sign-up-password">Password</label>
            <input
                type="password"
                class="form-control"
                id="sign-up-password"
                placeholder="Password"
                v-model="password"
            >
        </div>
        <button :disabled="progress" type="submit" class="btn btn-primary" v-on:click="submit">Sign Up</button>
    </form>
</template>
<script>
import authService from '../../services/authService';
export default {
    data() {
        return {
            name: '',
            email: '',
            password: '',
            error: '',
            progress: false
        };
    },
    methods: {
        submit(e) {
            e.preventDefault();
            this.progress = true;
           
            authService.register({
                name: this.name,
                email: this.email,
                password: this.password
            })
            .then(() => {
                this.error = '';
                this.progress = false;
                this.$emit('submit');
            })
            .catch(e => {
                this.progress = false;
                this.error = _.get(e, 'response.data.error', e.message);
                console.error(e);
            });
        }
    }
}
</script>

<style lang="scss" scoped>
</style>