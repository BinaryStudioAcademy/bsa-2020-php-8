<template>
    <form>
        <span v-if="error" class="text-danger">{{ error }}</span>
        <div class="form-group">
            <label for="sign-in-email">Email</label>
            <input
                type="email"
                class="form-control"
                id="sign-in-email"
                aria-describedby="emailHelp"
                placeholder="Enter email"
                v-model="email"
            >
        </div>
        <div class="form-group">
            <label for="sign-in-password">Password</label>
            <input
                type="password"
                class="form-control"
                id="sign-in-password"
                placeholder="Password"
                v-model="password"
            >
        </div>
        <button :disabled="progress" type="submit" class="btn btn-primary" v-on:click="submit">Sign In</button>
    </form>
</template>

<script>
import authService from '../../services/authService';
export default {
    data() {
        return {
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
            authService.auth({
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