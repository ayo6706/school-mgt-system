<template>
    <div class="form-custom row justify-content-center">
        <form>
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
            <div class="form-group row">
                <div class="custom-file">
                    <input type="file" v-on:change=handleFileUpload() ref="file" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>
            <div v-if="errors.file" class="alert alert-warning" role="alert">
                {{errors.file}}
            </div>
            <button type="button" @click="uploadResult" class="btn btn-primary" :disabled=isDisabled>
                <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                Submit
            </button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "DeptOfficerUploadResult",
        data() {
            return {
                errors: [],
                level: "",
                file: "",

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
                if(!this.file) this.errors.file = 'File is required';
                return Object.keys(this.errors).length === 0;
            },
            handleFileUpload() {
                this.file = this.$refs.file.files[0];
            },
            uploadResult() {
                this.buttonEffect(this.start);

                if(this.checkForm()) {
                    let formData = new FormData();
                    formData.append('file', this.file);
                    formData.append('level', this.level);

                    axios.post('/departmental-officer/upload-department-level-result',formData,{
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(response => {
                        this.buttonEffect(this.stop);
                        this.$toasted.show(response.data.message);
                        this.level = "";
                        this.file = "";
                    })
                        .catch(error => {
                            this.buttonEffect(this.stop);
                            this.$toasted.show("Error occurred");
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
