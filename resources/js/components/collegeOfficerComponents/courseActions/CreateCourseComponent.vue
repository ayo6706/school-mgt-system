<template>
    <div>
        <!--<div><h3>Create Course</h3></div>-->
        <div class="form-custom row justify-content-center">
            <form>
                <div class="form-group row">
                    <label for="courseName" class="col-sm-4 col-form-label">Name</label>
                    <div class="col-sm-8">
                        <input v-model="courseName" type="text" class="form-control" id="courseName" placeholder="">
                    </div>
                    <div v-if="errors.courseName" class="alert alert-warning" role="alert">
               <font-awesome-icon icon="coffee" /         {{errors.courseName}}
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
                <button @click="createCourse" type="button" class="btn btn-primary" :disabled=isDisabled>
                    <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Submit
                </button>
            </form>
        </div>
    </div>
</template>

<script>
    import * as axios from "axios";

    export default {
        name: "CreateCourseComponent",
        data() {
            return {
                errors: {},
                courseName: "",
                courseCode: "",
                showButtonIcon: false,
                isDisabled: false
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

            }
        }
    }
</script>

<style scoped>

</style>
