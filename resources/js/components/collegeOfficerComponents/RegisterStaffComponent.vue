<template>
    <div class="form-custom row justify-content-center">
        <form>
            <div class="form-group row">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="departmentOfficerCheckbox" value="departmentalOfficer" v-model="offices">
                    <label class="form-check-label" for="departmentOfficerCheckbox">Departmental Officer</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="courseLecturerCheckBox" value="courseLecturer" v-model="offices">
                    <label class="form-check-label" for="courseLecturerCheckBox">Course Lecturer</label>
                </div>
                <!--<label for="inputOffice" class="col-sm-4 col-form-label">Office</label>-->
                <!--<div class="col-sm-8">-->
                    <!--<select v-model="inputOffice" id="inputOffice" class="form-control">-->
                        <!--<option value="courseLecturer">Course Lecturer</option>-->
                        <!--<option value="departmentalOfficer">Departmental Officer</option>-->
                        <!--<option value="collegeOfficer">College Officer</option>-->
                    <!--</select>-->
                <!--</div>-->
                <div v-if="errors.inputOffice" class="alert alert-warning" role="alert">
                    {{errors.inputOffice}}
                </div>
            </div>
            <div class="form-group row">
                <label for="inputDepartment" class="col-sm-4 col-form-label">Department</label>
                <div class="col-sm-8">
                    <select v-model="department" id="inputDepartment" class="form-control">
                        <option v-for="department in departments" :value=department.id>{{department.name}}</option>
                    </select>
                </div>
                <div v-if="errors.department" class="alert alert-warning" role="alert">
                    {{errors.department}}
                </div>
            </div>
            <div class="form-group row">
                <label for="inputUsername" class="col-sm-4 col-form-label">Username</label>
                <div class="col-sm-8">
                    <input v-model="username" type="text" class="form-control" id="inputUsername" placeholder="">
                </div>
                <div v-if="errors.username" class="alert alert-warning" role="alert">
                    {{errors.username}}
                </div>
            </div>

            <div class="form-group row">
                <label for="inputEmail" class="col-sm-4 col-form-label">Email</label>
                <div class="col-sm-8">
                    <input v-model="email"type="email" class="form-control" id="inputEmail" placeholder="">
                </div>
                <div v-if="errors.email" class="alert alert-warning" role="alert">
                    {{errors.email}}
                </div>
            </div>

            <div class="form-group row">
                <label for="inputPassword" class="col-sm-4 col-form-label">Password</label>
                <div class="col-sm-8">
                    <input v-model="password"type="password" class="form-control" id="inputPassword" placeholder="">
                </div>
                <div v-if="errors.password" class="alert alert-warning" role="alert">
                    {{errors.password}}
                </div>
            </div>

            <div class="form-group row">
                <label for="inputConfirmPassword" class="col-sm-4 col-form-label">Confirm Password</label>
                <div class="col-sm-8">
                    <input v-model="confirmPassword" type="password" class="form-control" id="inputConfirmPassword" placeholder="">
                </div>
                <div v-if="errors.confirmPassword" class="alert alert-warning" role="alert">
                    {{errors.confirmPassword}}
                </div>
                <div v-if="errors.notMatch" class="alert alert-warning" role="alert">
                    {{errors.notMatch}}
                </div>
            </div>

            <button type="button" @click="registerStaff" class="btn btn-primary">
                <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                {{buttonMessage}}
            </button>
        </form>
    </div>
</template>

<script>
    import * as axios from "axios";

    export default {
        name: "RegisterStaffComponent",
        created() {
            this.getDepartments();
        },
        data() {
            return {
                errors: {},
                offices: [],
                department: 0,
                username: "",
                email: "",
                password: "",
                confirmPassword: "",

                buttonMessage: "Submit",
                showButtonIcon: false,

                departments: []
            }
        },
        methods: {
            getDepartments() {
                axios.get('college-officer/department/all')
                    .then(response => {
                        this.departments = response.data;
                    })
                    .catch(err => this.$toasted.show("Unable to load departments"));
            },
            checkForm() {
                this.errors = {};

                if (!Array.isArray(this.offices) || !this.offices.length) this.errors.inputOffice ='Office required';
                if (!this.department ) this.errors.department = 'Department required';
                if (!this.username) this.errors.username ='UserName required.';

                if (!this.email) {
                    this.errors.email = 'Email Required';
                } else if (!this.validEmail(this.email)) {
                    this.errors.email = 'Valid email required.';
                }
                if (!this.password) this.errors.password = 'Password Required';

                if (!this.confirmPassword) {
                    this.errors.confirmPassword = 'Confirm Password Required';
                } else if(this.password !== this.confirmPassword) this.errors.notMatch = 'Passwords not the same';

                return Object.keys(this.errors).length === 0;
            },

            validEmail (email) {
                let re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            },

            registerStaff () {
                const start = "start";
                const stop = "stop";
                this.buttonEffect(start);

                if(this.checkForm()) {
                    axios.post('college-officer/register-user',{
                        name: this.username,
                        email: this.email,
                        password: this.confirmPassword,
                        roles: this.offices,
                        department_id: this.department
                    }).then(response => {
                        this.offices = [];
                        this.department = 0, this.username = "";
                        this.email = "";
                        this.password = "";
                        this.confirmPassword = "";
                        this.$toasted.show(response.data.message);
                        this.buttonEffect(stop);
                    }).catch(error => {
                        this.$toasted.show("Unable to create user");
                        this.buttonEffect(stop);
                    });
                } else {
                    this.buttonEffect(stop);
                }

            },

            buttonEffect (act) {
                if(act === "start") {
                    this.buttonMessage = "Creating User...";
                    this.showButtonIcon = true;
                } else if(act === "stop") {
                    this.buttonMessage = "Submit";
                    this.showButtonIcon = false;
                }

            }
        }

    }
</script>

<style scoped>

</style>
