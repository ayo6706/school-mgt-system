<template>
    <div class="form-custom row justify-content-center">
        <form>
            <div class="form-group row">
                <label for="inputOldPassword" class="col-sm-4 col-form-label">Old Password</label>
                <div class="col-sm-8">
                    <input v-model="oldPassword" type="password" class="form-control" id="inputOldPassword" placeholder="">
                </div>
                <div v-if="errors.oldPassword" class="alert alert-warning" role="alert">
                    {{errors.oldPassword}}
                </div>
            </div>
            <div class="form-group row">
                <label for="inputNewPassword" class="col-sm-4 col-form-label">New Password</label>
                <div class="col-sm-8">
                    <input v-model="newPassword" type="password" class="form-control" id="inputNewPassword" placeholder="">
                </div>
                <div v-if="errors.newPassword" class="alert alert-warning" role="alert">
                    {{errors.newPassword}}
                </div>
            </div>
            <div class="form-group row">
                <label for="inputConfirmPassword" class="col-sm-4 col-form-label">Confirm New Password</label>
                <div class="col-sm-8">
                    <input v-model="confirmPassword" type="password" class="form-control" id="inputConfirmPassword" placeholder="">
                </div>
                <div v-if="errors.confirmPassword" class="alert alert-warning" role="alert">
                    {{errors.confirmPassword}}
                </div>
                <div v-if="errors.mismatch" class="alert alert-warning" role="alert">
                    {{errors.mismatch}}
                </div>
            </div>
            <button @click="changePassword" type="button" class="btn btn-primary" :disabled=isDisabled>
                <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Submit
            </button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "SettingComponent",
        data() {
            return {
                errors: [],
                oldPassword: '',
                newPassword: '',
                confirmPassword: '',

                start: "start",
                stop: "stop",

                showButtonIcon: false,
                isDisabled: false
            }
        },
        methods: {
            checkForm() {
                this.errors = {};
                if(!this.oldPassword) this.errors.oldPassword = 'Old password required.';
                if(!this.newPassword) this.errors.newPassword = 'New password required.';
                if(!this.confirmPassword) this.errors.confirmPassword = 'Confirm password required.';

                if(this.newPassword !== this.confirmPassword) this.errors.mismatch = 'Password do not match';

                return Object.keys(this.errors).length === 0;
            },
            changePassword() {
                this.buttonEffect(this.start);
                if(this.checkForm()) {
                    axios.post('/change-password',{
                        oldPassword: this.oldPassword,
                        newPassword: this.newPassword,
                        confirmPassword: this.confirmPassword
                    }).then(response => {
                        this.oldPassword = '';
                        this.newPassword = '';
                        this.confirmPassword = '';
                        this.$toasted.show(response.data.message);
                        this.buttonEffect(this.stop);
                    }).catch(error => {
                        this.$toasted.show('Unable to change password');
                        this.buttonEffect(this.stop);
                    })
                } else {
                    this.buttonEffect(this.stop);
                }
            },
            buttonEffect (act) {
                if(act === "start") {
                    this.buttonMessage = "Registering Course...";
                    this.showButtonIcon = true;
                } else if(act === "stop") {
                    this.buttonMessage = "Register";
                    this.showButtonIcon = false;
                }

            }
        }
    }
</script>

<style scoped>

</style>
