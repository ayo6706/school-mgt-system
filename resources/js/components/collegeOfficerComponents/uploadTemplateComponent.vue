<template>
    <div class="form-custom row justify-content-center">
        <form>
            <!--<div class="form-group row">-->
                <!--<label for="inputSession" class="col-sm-4 col-form-label">Session</label>-->
                <!--<div class="col-sm-8">-->
                    <!--<input type="text" class="form-control" id="inputSession" placeholder="2017/2018">-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="form-group row">-->
                <!--<label for="inputSemester" class="col-sm-4 col-form-label">Semester</label>-->
                <!--<div class="col-sm-8">-->
                    <!--<select id="inputSemester" class="form-control">-->
                        <!--<option selected>Choose...</option>-->
                        <!--<option>First</option>-->
                        <!--<option>Second</option>-->
                        <!--<option>Harmattan</option>-->
                        <!--<option>Rain</option>-->
                    <!--</select>-->
                <!--</div>-->

            <!--</div>-->
            <!--<div class="form-group row">-->
                <!--<label for="inputCollege" class="col-sm-4 col-form-label">College</label>-->
                <!--<div class="col-sm-8">-->
                    <!--<select id="inputCollege" class="form-control">-->
                        <!--<option selected>Choose...</option>-->
                        <!--<option>Ramon Adedoyin College of Natural and Applied Science</option>-->
                    <!--</select>-->
                <!--</div>-->
            <!--</div>-->
            <div class="form-group row">
                <label for="inputDepartment" class="col-sm-4 col-form-label">Department</label>
                <div class="col-sm-8">
                    <select v-model="department_id" id="inputDepartment" class="form-control">
                        <option v-for="department in departments" :value=department.id>{{department.name}}</option>
                    </select>
                </div>
                <div v-if="errors.department" class="alert alert-warning" role="alert">
                    {{errors.department}}
                </div>
            </div>
            <div class="form-group row">
                <label for="inputLevel" class="col-sm-4 col-form-label">Level</label>
                <div class="col-sm-8">
                    <select v-model="level" id="inputLevel" class="form-control">
                        <option>100</option>
                        <option>200</option>
                        <option>300</option>
                        <option>400</option>
                        <option>500</option>
                    </select>
                </div>
                <div v-if="errors.level" class="alert alert-warning" role="alert">
                    {{errors.level}}
                </div>
            </div>

            <div class="form-group row">
                <div class="custom-file">
                    <input type="file" v-on:change=handleFileUpload() ref="file" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>
            <button type="button" @click="uploadTemplate" class="btn btn-primary" :disabled=isDisabled>
                <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Submit
            </button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "uploadTemplateComponent",
        created(){
            this.getDepartments();
        },
        data() {
            return {
                errors: {},
                file: "",
                departments: '',
                department_id: 0,
                level: '',

                start: 'start',
                stop: 'stop',
                showButtonIcon: false,
                isDisabled: false,
            }
        },
        methods: {
            checkForm() {
                this.errors = {};
                if(!this.department_id) this.errors.department = 'Department is required';
                if(!this.level) this.errors.level = 'Level is required';
                return Object.keys(this.errors).length === 0;
            },
            getDepartments() {
                axios.get('college-officer/department/all')
                    .then(response => {
                        this.departments = response.data;
                    })
                    .catch(err => this.$toasted.show("Unable to load departments"));
            },
            handleFileUpload(){
                this.file = this.$refs.file.files[0];
            },
            uploadTemplate(){

                this.buttonEffect(this.start);

                if(this.checkForm()) {
                    // Initialize the form data
                    let formData = new FormData();

                    // Add the form data we need to submit
                    formData.append('file', this.file);
                    formData.append('department_id', this.department_id);
                    formData.append('level', this.level);
                    // formData.append('_method', 'POST');
                    // Make the request to the POST /single-file URL
                    axios.post( 'college-officer/upload-template',
                        formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    ).then((response) => {
                        this.buttonEffect(this.stop);
                        this.$toasted.show(response.data.message);
                        console.log(response);
                        console.log('SUCCESS!!');
                        this.department_id = "";
                        this.level = "";
                    })
                        .catch((error) => {
                            console.log(error);
                            console.log('FAILURE!!');
                            this.buttonEffect(this.stop);
                        });
                } else {
                    this.buttonEffect(this.stop);
                }
            },
            buttonEffect (act) {
                if(act === "start") {
                    this.buttonMessage = "Creating User...";
                    this.showButtonIcon = true;
                    this.isDisabled = true;
                } else if(act === "stop") {
                    this.buttonMessage = "Submit";
                    this.showButtonIcon = false;
                    this.isDisabled = false;
                }

            }
        }
    }
</script>

<style scoped>

</style>
