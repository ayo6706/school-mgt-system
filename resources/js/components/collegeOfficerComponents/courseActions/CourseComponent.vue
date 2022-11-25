<template>
    <div>
        <div v-if="showMainView">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createCourse">
                New
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
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(course, index) in courses">
                        <th scope="row">{{index + 1}}</th>
                        <td>{{course.courseName}}</td>
                        <td>{{course.courseCode}}</td>
                        <td>
                            <button @click="editCourse(course.id,course.courseName,course.courseCode)" type="button" class="btn btn-secondary">Edit</button>

                            <!-- Button trigger deletemodal -->
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#exampleModalCenter">
                                <font-awesome-icon icon="coffee" />
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalCenterTitle">Delete</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this course?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-danger"><font-awesome-icon icon="coffee" /></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div v-if="showEditCourseForm" id="updateCourseForm">
            <div class="form-custom row justify-content-center">
                <form>
                    <div class="form-group row">
                        <label for="courseName" class="col-sm-4 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <input v-model="courseNameUpdate" type="text" class="form-control" id="courseNameUpdate" placeholder="">
                        </div>
                        <div v-if="errors.courseName" class="alert alert-warning" role="alert">
                            {{errors.courseName}}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="courseCode" class="col-sm-4 col-form-label">Code</label>
                        <div class="col-sm-8">
                            <input v-model="courseCodeUpdate" type="text" class="form-control" id="courseCodeUpdate" placeholder="">
                            <div v-if="errors.courseCode" class="alert alert-warning" role="alert">
                                {{errors.courseCode}}
                            </div>
                        </div>
                    </div>
                    <button @click="switchView" type="button" class="btn btn-danger" :disabled=isDisabled>
                        <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        Close
                    </button>
                    <button @click="updateCourse" type="button" class="btn btn-primary" :disabled=isDisabled>
                        <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "CourseComponent",
        created() {
            this.getAllCourses();
        },
        data() {
            return {
                // Create course
                errors: {},
                courseId: 0,
                courseName: "",
                courseCode: "",
                showButtonIcon: false,
                isDisabled: false,
                // Get course
                courses: [],

                start: "start",
                stop: "stop",

                courseIdUpdate:0,
                courseNameUpdate: "",
                courseCodeUpdate: "",

                showMainView: true,
                showEditCourseForm: false
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

                this.buttonEffect(this.start);

                if(this.checkForm()) {
                    axios.post('college-officer/create-course',{
                        courseName: this.courseName,
                        courseCode: this.courseCode
                    }).then(response => {
                        this.$toasted.show(response.data.message);
                        this.courseName = '';
                        this.courseCode = '';
                        this.buttonEffect(this.stop);
                        this.getAllCourses();
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

            },

            getAllCourses() {
                axios.get('college-officer/course/all')
                    .then(response => {
                        this.courses = response.data;
                    })
                    .catch(err => console.log(err));
            },
            checkUpdateForm() {
                this.errors = {};
                if(!this.courseNameUpdate) this.errors.courseName = 'Course name required';
                if(!this.courseCodeUpdate) this.errors.courseCode = 'Course code required';

                return Object.keys(this.errors).length === 0;
            },
            updateCourse() {
                console.log("helloo");
                this.buttonEffect(this.start);
                if(this.checkUpdateForm()) {
                    console.log("id: "+ this.courseId);
                    axios.patch('college-officer/course/update',{
                        'id': this.courseId,
                        'courseName': this.courseNameUpdate,
                        'courseCode': this.courseCodeUpdate
                    })
                        .then(response => {
                            this.$toasted.show(response.data.message);
                            this.buttonEffect(this.stop);
                            this.getAllCourses();
                            this.switchView();
                        })
                        .catch(error => {
                            this.$toasted.show('Error occurred');
                            this.buttonEffect(this.stop);
                        });
                } else {
                    this.buttonEffect(this.stop);
                }

            },
            editCourse(id,courseName,courseCode) {
                this.courseId = id;
                console.log(id);
                this.courseNameUpdate = courseName;
                this.courseCodeUpdate = courseCode;
                this.switchView();
            },
            switchView() {
                this.showMainView = !this.showMainView;
                this.showEditCourseForm = !this.showEditCourseForm;
            }
        }
    }
</script>

<style scoped>

</style>
