<template>
    <div class="form-custom row justify-content-center">
        <form>
            <div class="form-group row">
                <label for="selectCourse" class="col-sm-4 col-form-label">Course</label>
                <div class="col-sm-8">
                    <select v-model="registered_course_id" v-on:change=searchDepartmentOfferingCourse() id="selectCourse" class="custom-select">
                        <option v-for="registeredCourse in registeredCoursesByLecturer" :value=registeredCourse.id>{{registeredCourse.courseName}} - {{registeredCourse.courseCode}}</option>
                    </select>
                </div>
                <div v-if="errors.course" class="alert alert-warning" role="alert">
                    {{errors.course}}
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
                <label for="name" class="col-sm-4 col-form-label">Name</label>
                <div class="col-sm-8">
                    <input v-model="name" type="text" class="form-control" id="name" placeholder="John Doe">
                </div>
                <div v-if="errors.name" class="alert alert-warning" role="alert">
                    {{errors.name}}
                </div>
            </div>
            <div class="form-group row">
                <label for="matricNo" class="col-sm-4 col-form-label">Matric No.</label>
                <div class="col-sm-8">
                    <input v-model="matricNo" type="text" class="form-control" id="matricNo" placeholder="ABC/2011/001">
                </div>
                <div v-if="errors.matricNo" class="alert alert-warning" role="alert">
                    {{errors.matricNo}}
                </div>
            </div>
            <div class="form-group row">
                <label for="att" class="col-sm-4 col-form-label">Attendance</label>
                <div class="col-sm-8">
                    <input v-model="att" type="text" class="form-control" id="att" placeholder="ATT">
                </div>
                <div v-if="errors.att" class="alert alert-warning" role="alert">
                    {{errors.att}}
                </div>
            </div>
            <div class="form-group row">
                <label for="test" class="col-sm-4 col-form-label">Test</label>
                <div class="col-sm-8">
                    <input v-model="test" type="text" class="form-control" id="test" placeholder="TEST">
                </div>
                <div v-if="errors.test" class="alert alert-warning" role="alert">
                    {{errors.test}}
                </div>
            </div>
            <div class="form-group row">
                <label for="exam" class="col-sm-4 col-form-label">Exam</label>
                <div class="col-sm-8">
                    <input v-model="exam" type="text" class="form-control" id="exam" placeholder="EXAM">
                </div>
                <div v-if="errors.exam" class="alert alert-warning" role="alert">
                    {{errors.exam}}
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
    import { mapState } from 'vuex';

    export default {
        name: "UploadNotOnTemplateComponent",
        computed: mapState([
            'registeredCoursesByLecturer'
        ]),
        data(){
            return {
                // registeredCourses: [],
                errors: [],
                registered_course_id: "",
                level: "",
                department_id: "",
                name: "",
                matricNo: "",
                att: "",
                test: "",
                exam: "",

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
                if(!this.registered_course_id) this.errors.course = 'Course is required';
                if(!this.level) this.errors.level = 'Level is required';
                if(!this.department_id) this.errors.department = 'Department is required';
                if(!this.name) this.errors.name = 'Name is required';
                if(!this.matricNo) this.errors.matricNo = 'Matric No is required';
                if(!this.att) this.errors.att = 'Attendance is required';
                if(!this.test) this.errors.test = 'Test is required';
                if(!this.exam) this.errors.exam = 'Exam is required';
                return Object.keys(this.errors).length === 0;
            },
            searchDepartmentOfferingCourse() {
                this.disableOfferingDepartmentInput = true;
                this.showLoadingDepartmentIcon = true;
                let course_id = this.registered_course_id;
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
            uploadResult() {
                this.buttonEffect(this.start);
                if(this.checkForm()) {
                    axios.post('course-lecturer/upload-result-not-on-template',{
                        registered_course_id: this.registered_course_id,
                        level: this.level,
                        department_id: this.department_id,
                        name: this.name,
                        matricNo: this.matricNo,
                        att: this.att,
                        test: this.test,
                        exam: this.exam,
                    }).then(response => {
                        this.registered_course_id = "";
                        this.level = "";
                        this.department_id = "";
                        this.name = "";
                        this.matricNo = "";
                        this.att = "";
                        this.test = "";
                        this.exam = "";
                        this.$toasted.show(response.data.message);
                        this.buttonEffect(this.stop)
                    })
                        .catch(error => {
                            this.$toasted.show('Error occurred');
                            this.buttonEffect(this.stop);
                        })
                } else {
                    this.$toasted.show("Unable to create result");
                    this.buttonEffect(this.stop)
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
