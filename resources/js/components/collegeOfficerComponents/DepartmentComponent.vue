<template>
    <div>
        <div v-if="showMainView">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createDepartment">
                New
            </button>

            <!-- Modal -->
            <div class="modal fade" id="createDepartment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Create Department</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group row">
                                    <label for="courseName" class="col-sm-4 col-form-label">Name</label>
                                    <div class="col-sm-8">
                                        <input v-model="departmentName" type="text" class="form-control" id="courseName" placeholder="">
                                    </div>
                                    <div v-if="errors.departmentName" class="alert alert-warning" role="alert">
                                        {{errors.departmentName}}
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button @click="createDepartment" type="button" class="btn btn-primary" :disabled=isDisabled>
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
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(department, index) in departments">
                        <th scope="row">{{index + 1}}</th>
                        <td>{{department.name}}</td>
                        <td>
                            <button @click="editCourse(department.id,department.name)" type="button" class="btn btn-secondary">Edit</button>

                            <!-- Button trigger deletemodal -->
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteDepartmentModal">
                                <font-awesome-icon icon="coffee" />
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="deleteDepartmentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalCenterTitle">Delete</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            Are you sure you want to delete this {{department.name}} department?
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

        <div v-if="showEditDepartmentForm" id="updateDepartmentForm">
            <div class="form-custom row justify-content-center">
                <form>
                    <div class="form-group row">
                        <label for="departmentNameUpdate" class="col-sm-4 col-form-label">Name</label>
                        <div class="col-sm-8">
                            <input v-model="departmentNameUpdate" type="text" class="form-control" id="departmentNameUpdate" placeholder="">
                        </div>
                        <div v-if="errors.departmentName" class="alert alert-warning" role="alert">
                            {{errors.departmentName}}
                        </div>
                    </div>
                    <button @click="switchView" type="button" class="btn btn-danger" :disabled=isDisabled>
                        <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                        Close
                    </button>
                    <button @click="updateDepartment" type="button" class="btn btn-primary" :disabled=isDisabled>
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
        name: "DepartmentComponent",
        created() {
            this.getAllDepartments();
        },
        data() {
            return {
                // Create course
                errors: {},
                departmentId: 0,
                departmentName: "",
                showButtonIcon: false,
                isDisabled: false,
                // Get course
                departments: [],

                start: "start",
                stop: "stop",

                departmentIdUpdate:0,
                departmentNameUpdate: "",

                showMainView: true,
                showEditDepartmentForm: false
            }
        },
        methods: {
            checkForm() {
                this.errors = {};
                if(!this.departmentName) this.errors.departmentName = 'Department name required';

                return Object.keys(this.errors).length === 0;
            },
            createDepartment () {

                this.buttonEffect(this.start);

                if(this.checkForm()) {
                    axios.post('college-officer/create-department',{
                        name: this.departmentName,
                    }).then(response => {
                        this.$toasted.show(response.data.message);
                        this.departmentName = '';
                        this.buttonEffect(this.stop);
                        this.getAllDepartments();
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

            getAllDepartments() {
                axios.get('college-officer/department/all')
                    .then(response => {
                        this.departments = response.data;
                    })
                    .catch(err => console.log(err));
            },
            checkUpdateForm() {
                this.errors = {};
                if(!this.departmentNameUpdate) this.errors.departmentName = 'Department name required';
                return Object.keys(this.errors).length === 0;
            },
            updateDepartment() {
                console.log("helloo");
                this.buttonEffect(this.start);
                if(this.checkUpdateForm()) {
                    console.log("id: "+ this.departmentId);
                    axios.patch('college-officer/department/update',{
                        'id': this.departmentId,
                        'name': this.departmentNameUpdate
                    })
                        .then(response => {
                            this.$toasted.show(response.data.message);
                            this.departmentNameUpdate = "";
                            this.buttonEffect(this.stop);
                            this.getAllDepartments();
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
            editCourse(id,departmentName) {
                this.departmentId = id;
                console.log(id);
                this.departmentNameUpdate = departmentName;
                this.switchView();
            },
            switchView() {
                this.showMainView = !this.showMainView;
                this.showEditDepartmentForm = !this.showEditDepartmentForm;
            }
        }
    }
</script>

<style scoped>

</style>
