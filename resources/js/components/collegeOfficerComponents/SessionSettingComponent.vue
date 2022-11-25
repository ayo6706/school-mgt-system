<template>
    <div class="form-custom row justify-content-center">
        <div class="col-md-6">
            <h2>Current Session</h2>
            <p>
                <span>Session Year: {{currentSessionYear}}</span>
            </p>
            <p>
                <span>Semester Name: {{currentSemesterName}}</span>
            </p>
        </div>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#createActivateSession">
            Activate New Session
        </button>
        <div class="modal fade" id="createActivateSession" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Activate Session</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group row">
                                <span class="col-sm-12 col-form-label"><h3>{{initialYear}}/{{finalYear}}</h3></span>
                                <div class="col-sm-12">
                                    <button type="button" @click="decreaseSession" class="btn btn-primary">Decrease</button>
                                    <button type="button" @click="increaseSession" class="btn btn-primary">Increase</button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="inputSemester" class="col-sm-4 col-form-label">Semester</label>
                                <div class="col-sm-8">
                                    <select v-model="semesterName" id="inputSemester" class="form-control">
                                        <option>First</option>
                                        <option>Second</option>
                                        <option>Harmattan</option>
                                        <option>Rain</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" @click="activateSession" class="btn btn-primary">
                            <span v-if="showButtonIcon" class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                            Activate Session
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SessionSettingComponent",
        created() {
              this.getCurrentSession();
        },
        data() {
            return {
                initialYear:2000,
                finalYear:2001,
                semesterName:"",

                start: "start",
                stop: "stop",

                currentSessionYear:"",
                currentSemesterName:"",

                showButtonIcon: false,
                isDisabled: false,
            }
        },
        methods: {
            getCurrentSession() {
                axios.get('college-officer/get-activated-session')
                    .then(response => {
                        console.log(response.data);
                        this.currentSessionYear = response.data.sessionYear;
                        this.currentSemesterName = response.data.semesterName;
                    })
                    .catch(err=>{
                        $this.toasted.show("Unable to load current session");
                    })
            },
            increaseSession() {
                this.initialYear++;
                this.finalYear++;
            },
            decreaseSession() {
                this.initialYear--;
                this.finalYear--
            },
            activateSession() {
                this.buttonEffect(this.start);
                axios.post('college-officer/activate-session',{
                    sessionYearInitial: this.initialYear,
                    sessionYearFinal: this.finalYear,
                    // sessionYear:this.sessionYear,
                    semesterName:this.semesterName,
                }).then(response => {
                    this.buttonEffect(this.stop);
                    this.$toasted.show(response.data.message);
                    this.getCurrentSession();
                    this.$store.dispatch('getCurrentSession');
                    this.sessionYear = "";
                    this.semesterName = "";
                }).catch(error => {
                    this.buttonEffect(this.stop);
                    console.log(error);
                    this.$toasted.show("Could not activate session");
                })
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
