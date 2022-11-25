<template>
    <div class="container">
        <div class="row">
            <div class="col-3">
                <div class="nav-custom">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="uploadTemplate" data-toggle="pill" href="#uploadTemplateView" role="tab" aria-controls="v-pills-home" aria-selected="true">Upload Template</a>
                        <a class="nav-link" id="getResult" data-toggle="pill" href="#getResultView" role="tab" aria-controls="v-pills-profile" aria-selected="false">Get Result</a>
                        <a class="nav-link" id="uploadResult" data-toggle="pill" href="#uploadResultView" role="tab" aria-controls="v-pills-messages" aria-selected="false">Upload Result</a>
                        <a class="nav-link" id="registerStaff" data-toggle="pill" href="#registerStaffView" role="tab" aria-controls="v-pills-settings" aria-selected="false">Register Staff</a>
                        <a class="nav-link" id="course" data-toggle="pill" href="#courseView" role="tab" aria-controls="v-pills-settings" aria-selected="false">Course</a>
                        <a class="nav-link" id="department" data-toggle="pill" href="#departmentView" role="tab" aria-controls="v-pills-departments" aria-selected="false">Department</a>
                        <a class="nav-link" id="session" data-toggle="pill" href="#sessionView" role="tab" aria-controls="v-pills-settings" aria-selected="false">Session Settings</a>
                        <a class="nav-link" id="correctResult" data-toggle="pill" href="#correctResultView" role="tab" aria-controls="v-pills-messages" aria-selected="false">Correct Result</a>
                        <a class="nav-link" id="correctNameAndMatric" data-toggle="pill" href="#correctNameAndMatricView" role="tab" aria-controls="v-pills-settings" aria-selected="false">Correct Name and Matric</a>
                        <a class="nav-link" id="settings" data-toggle="pill" href="#settingsView" role="tab" aria-controls="v-pills-settings" aria-selected="false">Change Password</a>
                    </div>
                </div>
            </div>
            <div class="col-9">
                <span> <h4>Session: {{sessionYear}} Semester: {{semesterName}}</h4></span>
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="uploadTemplateView" role="tabpanel" aria-labelledby="uploadTemplate">
                        <upload-template-component></upload-template-component>
                    </div>
                    <div class="tab-pane fade" id="getResultView" role="tabpanel" aria-labelledby="getResult">
                        <get-result-component></get-result-component>
                    </div>
                    <div class="tab-pane fade" id="uploadResultView" role="tabpanel" aria-labelledby="uploadResult">
                        <college-officer-upload-result-component></college-officer-upload-result-component>
                    </div>
                    <div class="tab-pane fade" id="registerStaffView" role="tabpanel" aria-labelledby="registerStaff">
                        <register-staff-component></register-staff-component>
                    </div>
                    <div class="tab-pane fade" id="courseView" role="tabpanel" aria-labelledby="course">
                        <course-component></course-component>
                    </div>
                    <div class="tab-pane fade" id="departmentView" role="tabpanel" aria-labelledby="department">
                        <department-component></department-component>
                    </div>
                    <div class="tab-pane fade" id="sessionView" role="tabpanel" aria-labelledby="course">
                        <session-setting-component></session-setting-component>
                    </div>
                    <div class="tab-pane fade" id="correctResultView" role="tabpanel" aria-labelledby="correctResult">
                        <correct-result-component></correct-result-component>
                    </div>
                    <div class="tab-pane fade" id="correctNameAndMatricView" role="tabpanel" aria-labelledby="correctNameAndMatric">
                        <correct-name-and-matric-component></correct-name-and-matric-component>
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
    import UploadTemplateComponent from "./uploadTemplateComponent";
    import GetResultComponent from "./GetResultComponent";
    import CollegeOfficerUploadResultComponent from "./CollegeOfficerUploadResultComponent";
    import RegisterStaffComponent from "./RegisterStaffComponent";
    import SettingComponent from "../SettingComponent";
    import CourseComponent from "./courseActions/CourseComponent";
    import SessionSettingComponent from "./SessionSettingComponent";
    import DepartmentComponent from "./DepartmentComponent";
    import CorrectResultComponent from "../collegeOfficerComponents/CorrectResultComponent";
    import CorrectNameAndMatricComponent from "../collegeOfficerComponents/CorrectNameAndMatricComponent";

    import { mapState } from 'vuex'

    export default {
        name: "CollegeOfficerHomeComponent",
        computed: mapState([
            'sessionYear','semesterName'
        ]),
        components: {
            SessionSettingComponent,
            SettingComponent,
            RegisterStaffComponent,
            CollegeOfficerUploadResultComponent,
            GetResultComponent,
            UploadTemplateComponent,
            CourseComponent,
            CorrectNameAndMatricComponent,
            CorrectResultComponent,
            DepartmentComponent
        }
    }
</script>

<style scoped>

</style>
