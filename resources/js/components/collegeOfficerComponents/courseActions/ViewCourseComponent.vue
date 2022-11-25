<template>
    <div><!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createCourse">
            Create
        </button>

        <!-- Modal -->
        <div class="modal fade" id="createCourse" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Create Course</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group row">
                                <label for="courseName" class="col-sm-4 col-form-label">Name</label>
                                <div class="col-sm-8">
                                    <input v-model="courseName" type="text" class="form-control" id="courseName" placeholder="">
                                </div>
                                <div v-if="errors.courseName" class="alert alert-warning" role="alert">
                                    {{errors.courseName}}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="courseCode" class="col-sm-4 col-form-label">Code</label>
                                <div class="col-sm-8">
                                    <input v-model="courseCode" type="text" class="form-control" id="courseCode" placeholder="">
                                    <div v-if="errors.courseCode" class="alert alert-warning" role="alert">
                                        {{errors.courseCode}}
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click="createCourse" type="button" class="btn btn-primary" :disabled=isDisabled>
                            <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Save
                        </button>
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
                </tr>
                </thead>
                <tbody>
                <tr v-for="(course, index) in courses">
                    <th scope="row">{{index + 1}}</th>
                    <td>{{course.courseName}}</td>
                    <td>{{course.courseCode}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>

<script>
    import * as axios from "axios";

    export default {
        name: "ViewCourseComponent",
        created() {
            this.getAllCourses();
        },
        data() {
            return {
                // Create course
                errors: {},
                courseName: "",
                courseCode: "",
                showButtonIcon: false,
                isDisabled: false,
                // Get course
                courses: []
            }
        },
        methods: {
            checkForm() {
                this.errors = {};
                if(!this.courseName) this.errors.courseName = 'Course name required';
                if(!this.courseCode) this.errors.courseCode = 'Course code required';

                return Object.keys(this.errors).length === 0;
            },
            createCourse () {
                const start = "start";
                const stop = "stop";
                this.buttonEffect(start);

                if(this.checkForm()) {
                    axios.post('college-officer/create-course',{
                        courseName: this.courseName,
                        courseCode: this.courseCode
                    }).then(response => {
                        this.$toasted.show(response.data.message);
                        this.courseName = '';
                        this.courseCode = '';
                        this.buttonEffect(stop);
                        this.getAllCourses();
                    }).catch(error => {
                        this.$toasted.show('Error occurred');
                        this.buttonEffect(stop);
                    })
                } else {
                    this.buttonEffect(stop);
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

            },

            getAllCourses() {
                axios.get('college-officer/course/all')
                    .then(response => {
                        this.courses = response.data;
                    })
                    .catch(err => console.log(err));
            }
        }
    }
</script>

<style scoped>

</style>
