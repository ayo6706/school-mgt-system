<template>
    <div>
        <div>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#registerCourse">
                Register Course
            </button>

            <div class="modal fade" id="registerCourse" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Register Course</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group row">
                                    <label for="inputDepartment" class="col-sm-4 col-form-label">Course</label>
                                    <div class="col-sm-8">
                                        <select v-model="courseId" id="inputDepartment" class="form-control">
                                            <option v-for="course in courses" :value=course.id>{{course.courseName}} - {{course.courseCode}}</option>
                                        </select>
                                    </div>
                                    <div v-if="errors.course" class="alert alert-warning" role="alert">
                                        {{errors.course}}
                                    </div>
                                </div>
                                <!--<div class="form-group row">-->
                                    <!--<label for="selectDepartment" class="col-sm-4 col-form-label">Department</label>-->
                                    <!--<select v-model="selectedDepartments" id="selectDepartment" class="custom-select" multiple>-->
                                        <!--<option v-for="(department, index) in departments" :value=department.id>{{index + 1}} - {{department.name}}</option>-->
                                    <!--</select>-->
                                    <!--<div v-if="errors.selectedDepartments" class="alert alert-warning" role="alert">-->
                                        <!--{{errors.selectedDepartments}}-->
                                    <!--</div>-->
                                <!--</div>-->
                                <div class="form-group row">
                                    <div v-for="(department, index) in departments" class="form-check  col-sm-6">
                                        <input class="form-check-input col-sm-1" type="checkbox" id="departmentOfficerCheckbox" :value=department.id v-model="selectedDepartments">
                                        <label class="form-check-label col-sm-11" for="departmentOfficerCheckbox">{{department.name}}</label>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button @click="registerCourse" type="button" class="btn btn-primary" :disabled=isDisabled>
                                <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                Register
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div id="view">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Code</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(registeredCourse, index) in registeredCoursesByLecturer">
                    <th scope="row">{{index + 1}}</th>
                    <td>{{registeredCourse.courseName}}</td>
                    <td>{{registeredCourse.courseCode}}</td>
                    <!--<td>-->
                        <!--<button @click="editCourse(department.id,department.name)" type="button" class="btn btn-secondary">Edit</button>-->

                        <!--&lt;!&ndash; Button trigger deletemodal &ndash;&gt;-->
                        <!--<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteDepartmentModal">-->
                            <!--<font-awesome-icon icon="coffee" />-->
                        <!--</button>-->

                        <!--&lt;!&ndash; Modal &ndash;&gt;-->
                        <!--<div class="modal fade" id="deleteDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">-->
                            <!--<div class="modal-dialog modal-dialog-centered" role="document">-->
                                <!--<div class="modal-content">-->
                                    <!--<div class="modal-header">-->
                                        <!--<h5 class="modal-title" id="exampleModalCenterTitle">Delete</h5>-->
                                        <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
                                            <!--<span aria-hidden="true">&times;</span>-->
                                        <!--</button>-->
                                    <!--</div>-->
                                    <!--<div class="modal-body">-->
                                        <!--Are you sure you want to delete this {{department.name}} department?-->
                                    <!--</div>-->
                                    <!--<div class="modal-footer">-->
                                        <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>-->
                                        <!--<button type="button" class="btn btn-danger"><font-awesome-icon icon="coffee" /></button>-->
                                    <!--</div>-->
                                <!--</div>-->
                            <!--</div>-->
                        <!--</div>-->
                    <!--</td>-->
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>

<script>
    import {mapState} from 'vuex';

    export default {
        name: "CourseRegisterComponent",
        computed: mapState([
            'registeredCoursesByLecturer'
        ]),
        created() {
            // this.getRegisteredCourse();
            this.getAllCourses();
            this.getAllDepartments();
        },
        data() {
            return {
                errors: [],
                courseId: "",
                courses: [],
                departments: [],
                selectedDepartments: [],
                // registeredCourses: [],

                start: "start",
                stop: "stop",

                showButtonIcon: false,
                isDisabled: false
            }
        },
        methods: {
            getAllCourses() {
                axios.get('course-lecturer/course/all')
                    .then(response => {
                        this.courses = response.data;
                    })
                    .catch(err => this.$toasted.show("Unable to load courses"));
            },
            getAllDepartments() {
                axios.get('course-lecturer/department/all')
                    .then(response => {
                        this.departments = response.data;
                    })
                    .catch(err => {
                        console.log(err);
                        this.$toasted.show("Unable to load departments")
                    });
            },
            // getRegisteredCourse() {
            //     axios.get('course-lecturer/registered-course')
            //         .then(response => {
            //             this.registeredCourses = response.data;
            //             // console.log(response.data)
            //         })
            //         .catch(err => this.$toasted.show("Unable to load registered courses"));
            // } ,
            checkForm() {
                this.errors = {};
                if(!Array.isArray(this.selectedDepartments) || !this.selectedDepartments.length) this.errors.selectedDepartments = 'You need to select a department taking this course';
                if(!this.courseId) this.errors.course = 'Course required.';

                return Object.keys(this.errors).length === 0;

            },
            registerCourse () {
                if(this.checkForm()) {
                    axios.post('course-lecturer/register-course',{
                        courseId: this.courseId,
                        selectedDepartments: this.selectedDepartments
                    }).then(response => {
                        this.courseId = "";
                        this.selectedDepartments = [];
                        this.$toasted.show(response.data.message);
                        console.log(response.data.message);
                        this.buttonEffect(this.stop);
                        // this.getRegisteredCourse();
                        this.$store.dispatch('getRegisteredCourse');
                    }).catch(error=> {
                        this.$toasted.show("Unable to register course, maybe course as already been registered");
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
