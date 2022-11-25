<template>
    <div class="form-custom row justify-content-center">
        <form>
            <div class="form-group row">
                <label for="inputCourse" class="col-sm-4 col-form-label">Course</label>
                <div class="col-sm-8">
                    <select v-model="course_id" v-on:change=searchDepartmentOfferingCourse() id="inputCourse" class="form-control">
                        <option v-for="registeredCourse in registeredCoursesByLecturer" :value="registeredCourse.id">{{registeredCourse.courseCode}} - {{registeredCourse.courseName}}</option>
                    </select>
                </div>
                <div v-if="errors.course" class="alert alert-warning" role="alert">
                    {{errors.course}}
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
                <label for="inputDepartment" class="col-sm-4 col-form-label">Department</label>
                <div class="col-sm-8">
                    <select v-model="department_id" id="inputDepartment" class="form-control" :disabled=disableOfferingDepartmentInput>
                        <option v-for="courseOfferingDepartment in courseOfferingDepartments" :value="courseOfferingDepartment.id">{{courseOfferingDepartment.name}}</option>
                    </select>
                    <span v-if="showLoadingDepartmentIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                </div>
                <div v-if="errors.department" class="alert alert-warning" role="alert">
                    {{errors.department}}
                </div>
            </div>
            <div class="form-group row">
                <div class="custom-file">
                    <input type="file" v-on:change=handleFileUpload() ref="file" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
                <div v-if="errors.file" class="alert alert-warning" role="alert">
                    {{errors.file}}
                </div>
            </div>

            <button type="button" @click="uploadResult" class="btn btn-primary" :disabled=isDisabled>
                <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Submit
            </button>
        </form>
    </div>
</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "UploadResultComponent",
        computed: mapState([
            'registeredCoursesByLecturer'
        ]),
        // created() {
        //     // this.getRegisteredCourse();
        // },
        data() {
            return {
                errors: [],
                course_id: "",
                level: "",
                department_id: "",
                file: "",
                // registeredCourses: [],
                courseOfferingDepartments: [],

                start: "start",
                stop: "stop",

                showButtonIcon: false,
                isDisabled: false,

                disableOfferingDepartmentInput: true,
                showLoadingDepartmentIcon: false
            }
        },
        methods: {
            checkForm() {
                this.errors = {};
                if(!this.course_id) this.errors.course = 'Course is required';
                if(!this.level) this.errors.level = 'Level is required';
                if(!this.department_id) this.errors.department = 'Department is required';
                if(!this.file) this.errors.file = 'File is required';
                return Object.keys(this.errors).length === 0;
            },
            // getRegisteredCourse() {
            //     axios.get('course-lecturer/registered-course')
            //         .then(response => {
            //             this.registeredCourses = response.data;
            //         })
            //         .catch(err => this.$toasted.show("Unable to load registered courses"));
            // },
            searchDepartmentOfferingCourse() {
                this.disableOfferingDepartmentInput = true;
                this.showLoadingDepartmentIcon = true;
                let course_id = this.course_id;
                axios.post('course-lecturer/search-department-offering-course',{
                    course_id: course_id
                })
                    .then(response => {
                        this.showLoadingDepartmentIcon = false;
                        this.courseOfferingDepartments = response.data.departments;
                        this.disableOfferingDepartmentInput = false;
                    })
                    .catch(err => {
                        this.showLoadingDepartmentIcon = false;
                        this.disableOfferingDepartmentInput = true;
                        this.$toasted.show("Unable to load department offering course")
                    });
            },
            handleFileUpload() {
                this.file = this.$refs.file.files[0];
            },
            uploadResult() {
                this.buttonEffect(this.start);

                if(this.checkForm()) {
                    let formData = new FormData();
                    formData.append('file', this.file);
                    formData.append('registered_course_id', this.course_id);
                    formData.append('department_id', this.department_id);
                    formData.append('level', this.level);

                    axios.post('/course-lecturer/upload-result',formData,{
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                        this.buttonEffect(this.stop);
                        this.$toasted.show(response.data.message);
                        this.course_id = "";
                        this.level = "";
                        this.department_id = "";
                        this.file = "";
                    })
                        .catch(error => {
                            this.buttonEffect(this.stop);
                            this.$toasted.show("Error occurred");
                        })
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
