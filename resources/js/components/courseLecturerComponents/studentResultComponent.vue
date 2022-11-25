<template>
    <div class="form-custom row justify-content-center">
        <form>
            <div class="form-group row">
                <label for="inputCourse" class="col-sm-4 col-form-label">Course</label>
                <div class="col-sm-8">
                    <select v-model="course_id" v-on:change=searchDepartmentOfferingCourse() id="inputCourse" class="form-control">
                        <option v-for="registeredCourse in registeredCourses" :value="registeredCourse.id">{{registeredCourse.courseCode}} - {{registeredCourse.courseName}}</option>
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
            <!--<div class="form-group row">-->
                <!--<label for="inputMatricNumber" class="col-sm-4 col-form-label">Matric Number</label>-->
                <!--<div class="col-sm-8">-->
                    <!--<input type="text" class="form-control" id="inputMatricNumber" placeholder="U/10/MB/0001">-->
                <!--</div>-->
            <!--</div>-->

            <button type="button" @click="downloadResult" class="btn btn-primary" :disabled=isDisabled>
                <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Submit
            </button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "studentResult",
        created() {
            this.getRegisteredCourse();
        },
        data() {
            return {
                errors: [],
                course_id: "",
                level: "",
                department_id: "",
                file: "",

                registeredCourses: [],
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
                return Object.keys(this.errors).length === 0;
            },
            getRegisteredCourse() {
                axios.get('course-lecturer/registered-course')
                    .then(response => {
                        this.registeredCourses = response.data;
                    })
                    .catch(err => this.$toasted.show("Unable to load registered courses"));
            },
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
            downloadResult() {
                this.buttonEffect(this.start);
                if(this.checkForm()) {
                    axios.post('/course-lecturer/download-result',{
                        course_id: this.course_id,
                        level: this.level,
                        department_id: this.department_id
                    },{
                        responseType: 'blob'
                    }).then(response => {
                        this.course_id = '';
                        this.level = '';
                        this.department_id = '';
                        this.buttonEffect(this.stop);
                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'department-result.xlsx'); //or any other extension
                        document.body.appendChild(link);
                        link.click();
                    }).catch(error => {
                        this.$toasted.show('Error occurred');
                        this.buttonEffect(this.stop);
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
