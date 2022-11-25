<template>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <div class="nav-custom">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="getResult" data-toggle="pill" href="#getResultView" role="tab" aria-controls="v-pills-home" aria-selected="true">Get Result</a>
                        <a class="nav-link" id="uploadResult" data-toggle="pill" href="#uploadResultView" role="tab" aria-controls="v-pills-profile" aria-selected="false">Upload Result</a>
                        <a class="nav-link" id="studentResult" data-toggle="pill" href="#studentResultView" role="tab" aria-controls="v-pills-profile" aria-selected="false">Student Result</a>
                        <a class="nav-link" id="settings" data-toggle="pill" href="#settingsView" role="tab" aria-controls="v-pills-settings" aria-selected="false">Change Password</a>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <span> <h4>Session: {{sessionYear}} Semester: {{semesterName}}</h4></span>
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="getResultView" role="tabpanel" aria-labelledby="getResult">
                        <get-result-component></get-result-component>
                    </div>
                    <div class="tab-pane fade" id="uploadResultView" role="tabpanel" aria-labelledby="uploadResult">
                        <dept-officer-upload-result></dept-officer-upload-result>
                    </div>
                    <div  class="tab-pane fade" id="studentResultView" role="tabpanel" aria-labelledby="studentResult">
                        <student-result-component></student-result-component>
                    </div>
                    <div class="tab-pane fade" id="settingsView" role="tabpanel" aria-labelledby="settings">
                        <setting-component></setting-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import GetResultComponent from "./GetResultComponent";
    import DeptOfficerUploadResult from "./DeptOfficerUploadResultComponent";
    import SettingComponent from "../SettingComponent";
    import StudentResultComponent from "./StudentResultComponent";
    import { mapState } from 'vuex'

    export default {
        name: "DepartmentOfficerHomeComponent",
        computed: mapState([
            'sessionYear','semesterName'
        ]),
        components: {SettingComponent, DeptOfficerUploadResult, GetResultComponent, StudentResultComponent},
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>

<style scoped>

</style>
