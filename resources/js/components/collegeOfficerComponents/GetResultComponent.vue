<template>
    <div class="form-custom row justify-content-center">
        <form>
                <div class="form-group row">
                    <label for="inputDepartment" class="col-sm-4 col-form-label">Department</label>
                    <div class="col-sm-8">
                        <select v-model="department_id" v-on:change=getDepartmentCourses() id="inputDepartment" class="form-control">
                            <option v-for="department in departments" :value="department.id">{{department.name}}</option>
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

            <hr/>
            <div v-if="loadingCourseIcon">
            <span  class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
            <span>Loading department courses...</span>
            </div>
            <div v-if="showCourseInformation">
                <div>
                    <h4>Course Information</h4>
                </div>

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
                            <select v-model="courseInfo.course_id" id="inputCourse" class="form-control">
                                <option v-for="course in courses" :value=course.id>{{course.courseName}} - {{course.courseCode}}</option>
                            </select>
                        </td>
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
                <button type="button" class="btn btn-success"
                        @click="addCourseInfo">
                    Add
                </button>
            </div>
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
            this.getAllDepartments();
        },
        data() {
            return {
                errors: {},
                courseInfos: [
                    {
                        code: "",
                        unit: ""
                    }
                ],
                level: "",
                courses: [],
                departments: "",
                department_id:"",

                showCourseInformation:false,
                loadingCourseIcon:false,

                isDisabled: false,
                showButtonIcon: false
            }
        },
        methods: {
            checkForm() {
                this.errors = {};
                if(!this.level) this.errors.level = 'Level is required';
                if(!this.department_id) this.errors.department = 'Department is required';
                this.courseInfos.map(courseInfo => {
                    if((courseInfo.course_id === '') || (courseInfo.course_unit === '')) {
                        if(!this.courseInfo) this.errors.courseInfo = 'Course information cannot have an empty field';
                    }
                });
                return Object.keys(this.errors).length === 0;
            },
            gotoNext() {
                this.showResultInfo = !this.showResultInfo;
                this.showCourseCode = !this.showCourseCode;
            },
            getAllDepartments() {
                axios.get('college-officer/department/all')
                    .then(response => {
                        this.departments = response.data;
                    })
                    .catch(err => console.log(err));
            },
            getDepartmentLevelResult() {
                this.buttonEffect(this.start);
                if(this.checkForm()) {
                    axios.post('college-officer/get-department-result',{
                        department_id: this.department_id,
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
                        this.department_id = "";
                        this.level = "";
                        this.loadingCourseIcon = false;
                        this.showCourseInformation = false;

                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'department-level-result.xlsx'); //or any other extension
                        document.body.appendChild(link);
                        link.click();
                        console.log(response.data);
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
                    code: "",
                    unit: ""
                })
            },
            deleteCourseInfo(index) {
                if(this.courseInfos.length > 1) {
                    this.courseInfos.splice(index, 1);
                }
            },
            getDepartmentCourses() {
                this.loadingCourseIcon = true;
                axios.post('college-officer/get-department-courses',{
                    department_id: this.department_id
                })
                    .then(response => {
                        this.courses = response.data.courses;
                        this.showCourseInformation = true;
                        this.loadingCourseIcon = false;
                        console.log(this.courses);
                    })
                    .catch(err=>{
                        this.loadingCourseIcon = false;
                        this.$toasted.show("Unable to load departmental courses")
                    });
            },
            buttonEffect (act) {
                if(act === "start") {
                    this.showButtonIcon = true;
                    this.isDisabled = true;
                } else if(act === "stop") {
                    this.showButtonIcon = false;
                    this.isDisabled = false;
                }
            }
        }
    }
</script>

<style scoped>

</style>
