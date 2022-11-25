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
                    <select v-model="department" id="inputDepartment" class="form-control">
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

            <button @click="downloadTemplate" type="button" class="btn btn-primary" :disabled=isDisabled>
                <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Submit
            </button>
        </form>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    export default {
        name: "DownloadTemplateComponent",
        computed: mapState([
            'departments'
        ]),
        data() {
            return {
                errors: {},
                department: 0,
                level:'',

                start: "start",
                stop: "stop",
                showButtonIcon: false,
                isDisabled: false,
            }
        },
        methods: {
            checkForm() {
                this.errors = {};
                if(!this.department) this.errors.department = 'Department name required';
                if(!this.level) this.errors.level = 'Level is required';

                return Object.keys(this.errors).length === 0;
            },
            downloadTemplate() {
                this.buttonEffect(this.start);
                if(this.checkForm()) {
                    axios.post('course-lecturer/download-template',{
                        'department': this.department,
                        'level': this.level
                    },{
                        responseType: 'blob'
                    }).then(response => {
                        this.department = null;
                        this.level = '';
                        this.buttonEffect(this.stop);

                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', 'student-template.xlsx'); //or any other extension
                        document.body.appendChild(link);
                        link.click();
                        // this.$toasted.show(response.data.message);
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
