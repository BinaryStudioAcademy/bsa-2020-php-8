<template>
    <div
        class="modal fade"
        :class="{ show: show }"
        :style="{ display: modalDisplay }"
        tabindex="-1"
        role="dialog"
    >
        <div class="modal-dialog" role="document">
            <div v-if="isAuth" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sign In</h5>
                    <a href="#" class="pull-right" v-on:click="showSignUp">Sign Up</a>
                </div>
                <div class="modal-body">
                    <sign-in v-on:submit="submit" />
                </div>
            </div>
            <div v-if="isRegister" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Sign Up</h5>
                    <a href="#" class="pull-right" v-on:click="showSignIn">Sign In</a>
                </div>
                <div class="modal-body">
                    <sign-up v-on:submit="submit" />
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import SignIn from './SignIn';
import SignUp from './SignUp';
export default {
    components: {
        'sign-in': SignIn,
        'sign-up': SignUp
    },
    props: {
        show: Boolean,
    },
    data() {
        return {
            type: 'auth',
            formData: {}
        };
    },
    computed: {
        modalDisplay() {
            return this.show ? 'block' : 'none';
        },
        isAuth() {
            return this.type === 'auth';
        },
        isRegister() {
            return this.type === 'register';
        }
    },
    methods: {
        submit(data) {
            this.$emit('hide');
        },
        showSignUp() {
            this.type = 'register';
        },
        showSignIn() {
            this.type = 'auth';
        }
    }
}
</script>

<style lang="scss" scoped>
</style>