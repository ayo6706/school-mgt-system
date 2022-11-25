<template>
    <div class="container">
        <!--<div class="row justify-content-center">-->
            <!--<div class="col-md-8">-->
                <!--<div class="card card-default">-->
                    <!--<div class="card-header">Example Component</div>-->

                    <!--<div class="card-body">-->
                        <!--I'm an example component.-->
                        <!--&lt;!&ndash;<router-link to="/another-example">Go to Foo</router-link>&ndash;&gt;-->
                        <!--&lt;!&ndash;<router-link to="/example">Go to Bar</router-link>&ndash;&gt;-->
                    <!--</div>-->
                <!--</div>-->
                <!--&lt;!&ndash;<router-link></router-link>&ndash;&gt;-->
            <!--</div>-->
        <!--</div>-->
        <div class="row">
            <div class="col-3">
                <div class="nav-custom">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active" id="downloadTemplateTab" data-toggle="pill" href="#downloadTemplateView" role="tab" aria-controls="v-pills-home" aria-selected="true">Download Template</a>
                        <a class="nav-link" id="registerCourseDetails" data-toggle="pill" href="#registerCourseDetailsView" role="tab" aria-controls="v-pills-profile" aria-selected="false">Register course Details</a>
                        <a class="nav-link" id="uploadResult" data-toggle="pill" href="#uploadResultView" role="tab" aria-controls="v-pills-messages" aria-selected="false">Upload Result</a>
                        <a class="nav-link" id="uploadNotOnTemplateuploadNotOnTemplate" data-toggle="pill" href="#uploadNotOnTemplateView" role="tab" aria-controls="v-pills-settings" aria-selected="false">Upload(Not on Template)</a>
                        <a class="nav-link" id="studentResult" data-toggle="pill" href="#studentResultView" role="tab" aria-controls="v-pills-settings" aria-selected="false">Score Sheet</a>
                        <a class="nav-link" id="settings" data-toggle="pill" href="#settingsView" role="tab" aria-controls="v-pills-settings" aria-selected="false">Change Password</a>

                    </div>
                </div>
            </div>
            <div class="col-9">
                <span> <h4>Session: {{sessionYear}} Semester: {{semesterName}}</h4></span>
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade show active" id="downloadTemplateView" role="tabpanel" aria-labelledby="downloadTemplateTab">
                        <DownloadTemplateComponent></DownloadTemplateComponent>
                    </div>
                    <!--Download Template ends-->
                    <div class="tab-pane fade" id="registerCourseDetailsView" role="tabpanel" aria-labelledby="registerCourseDetails">
                        <course-register-component></course-register-component>
                    </div>
                    <div class="tab-pane fade" id="uploadResultView" role="tabpanel" aria-labelledby="uploadResult">
                        <upload-result-component></upload-result-component>
                    </div>
                    <div class="tab-pane fade" id="uploadNotOnTemplateView" role="tabpanel" aria-labelledby="uploadNotOnTemplate">
                        <upload-not-on-template-component></upload-not-on-template-component>
                    </div>
                    <div class="tab-pane fade" id="studentResultView" role="tabpanel" aria-labelledby="studentResult">
                        <student-result></student-result>
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
    import DownloadTemplateComponent from "./DownloadTemplateComponent";
    import CourseRegisterComponent from "./CourseRegisterComponent";
    import UploadResultComponent from "./UploadResultComponent";
    import UploadNotOnTemplateComponent from "./UploadNotOnTemplateComponent";
    import SettingComponent from "../SettingComponent";
    import StudentResult from "./studentResultComponent";
    import { mapState } from 'vuex'
    export default {
        name: "LecturerHomeComponent",
        computed: mapState([
            'sessionYear','semesterName'
        ]),
        components: {
            StudentResult,
            SettingComponent,
            UploadNotOnTemplateComponent,
            UploadResultComponent, CourseRegisterComponent, DownloadTemplateComponent},
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
