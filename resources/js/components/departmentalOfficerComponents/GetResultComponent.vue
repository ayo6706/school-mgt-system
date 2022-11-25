<template>
    <div class="form-custom row justify-content-center">
            <form>
                <!--<div v-if="showResultInfo" class="resultInfo">-->
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
<hr/>
                <!--</div>-->
                <!--<div v-if="showCourseCode" class="courseCode">-->

                    <div>
                        <h4>Course Information</h4>
                    </div>
                    <!--<button type="button" class="btn btn-success"-->
                        <!--@click="addCourseInfo">-->
                        <!--Add-->
                    <!--</button>-->
                    <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Code</th>
                                <th scope="col">Unit</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(courseInfo, index) in courseInfos">
                            <th scope="row">{{index + 1}}</th>
                            <td>
                                <select v-model="courseInfo.course_id" id="inputDepartment" class="form-control">
                                    <option v-for="course in courses" :value=course.id>{{course.courseName}} - {{course.courseCode}}</option>
                                </select>
                            <td>
                                <select id="inputCourseUnit" v-model="courseInfo.course_unit" class="form-control">
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                </select>
                            </td>
                            <td>
                                <a v-on:click="deleteCourseInfo(index)" style="cursor:pointer">X</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div v-if="errors.courseInfo" class="alert alert-warning" role="alert">
                        {{errors.courseInfo}}
                    </div>
                    <button type="button" class="btn btn-success"
                            @click="addCourseInfo">
                        Add
                    </button>
                <!--</div>-->

                <p></p>
                <button type="button" @click="getDepartmentLevelResult" class="btn btn-primary" :disabled=isDisabled>
                    <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                    Submit
                </button>
            </form>
    </div>
</template>

<script>
    export default {
        name: "GetResultComponent",
        created() {
            this.getDepartmentCourses();
        },
        data() {
            return {
                errors: {},
                // showResultInfo: true,
                // showCourseCode: false,
                courseInfos: [
                    {
                        course_id: "",
                        course_unit: ""
                    }
                ],
                level: "",
                courses: [],

                start: "start",
                stop: "stop",
                showButtonIcon: false,
                isDisabled: false,
            }
        },
        methods: {
            checkForm() {
                this.errors = {};
                if(!this.level) this.errors.level = 'Level is required';

                this.courseInfos.map(courseInfo => {
                    if((courseInfo.course_id === '') || (courseInfo.course_unit === '')) {
                        if(!this.courseInfo) this.errors.courseInfo = 'Course information cannot have an empty field';
                    }
                });
                return Object.keys(this.errors).length === 0;
            },
            getDepartmentLevelResult() {
                this.buttonEffect(this.start);
                if(this.checkForm()) {
                    axios.post('departmental-officer/get-department-result',{
                        level: this.level,
                        courseInfos: this.courseInfos
                    },{
                        responseType: 'blob'
                    }).then(response => {
                        this.buttonEffect(this.stop);
                        this.courseInfos = [
                            {
                                course_id: "",
                                course_unit: ""
                            }
                        ];
                        this.level = "";
                        // console.log(response.data)

                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'department-level-result.xlsx'); //or any other extension
                        document.body.appendChild(link);
                        link.click();
                    }).catch(err => {
                        this.$toasted.show('Error occurred');
                        this.buttonEffect(this.stop);
                    })
                } else {
                    this.buttonEffect(this.stop);
                }
            },
            addCourseInfo() {
                console.log('Adding');

                this.courseInfos.push({
                    course_id: "",
                    course_unit: ""
                })
            },
            deleteCourseInfo(index) {
                if(this.courseInfos.length > 1) {
                    this.courseInfos.splice(index, 1);
                }
            },
            getDepartmentCourses() {
                axios.get('departmental-officer/get-department-courses')
                    .then(response => {
                        this.courses = response.data.courses;
                        console.log(this.courses);
                    })
                    .catch(err=>this.$toasted.show("Unable to load departmental courses"));
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
