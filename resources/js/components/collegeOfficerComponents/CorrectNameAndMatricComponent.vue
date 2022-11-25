<template>
    <div class="form-custom row justify-content-center">
        <form>
            <div class="form-group row">
                <label for="inputSession" class="col-sm-4 col-form-label">Session</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="inputSession" placeholder="2017/2018">
                </div>
            </div>
            <div class="form-group row">
                <label for="inputSemester" class="col-sm-4 col-form-label">Semester</label>
                <div class="col-sm-8">
                    <select id="inputSemester" class="form-control">
                        <option selected>Choose...</option>
                        <option>First</option>
                        <option>Second</option>
                        <option>Harmattan</option>
                        <option>Rain</option>
                    </select>
                </div>

            </div>
            <div class="form-group row">
                <label for="inputCollege" class="col-sm-4 col-form-label">College</label>
                <div class="col-sm-8">
                    <select id="inputCollege" class="form-control">
                        <option selected>Choose...</option>
                        <option>Ramon Adedoyin College of Natural and Applied Science</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputDepartment" class="col-sm-4 col-form-label">Department</label>
                <div class="col-sm-8">
                    <select id="inputDepartment" class="form-control">
                        <option selected>Choose...</option>
                        <option>Bio Chemistry</option>
                        <option>Computer Science</option>
                        <option>Industrial Chemistry</option>
                        <option>Micro Biology</option>
                        <option>Physics</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputLevel" class="col-sm-4 col-form-label">Level</label>
                <div class="col-sm-8">
                    <select id="inputLevel" class="form-control">
                        <option selected>Choose...</option>
                        <option>100</option>
                        <option>200</option>
                        <option>300</option>
                        <option>400</option>
                        <option>500</option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="inputMatricNo" class="col-sm-4 col-form-label">Matric No.</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="inputMatricNo" placeholder="ABC/2011/001">
                </div>
            </div>

            <div class="form-group row">
                <label for="inputWrongMatricNo" class="col-sm-4 col-form-label">Wrong Matric</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="inputWrongMatricNo" placeholder="">
                </div>
            </div>

            <div class="form-group row">
                <label for="inputCorrectMatricNo" class="col-sm-4 col-form-label">Correct Matric No</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="inputCorrectMatricNo" placeholder="">
                </div>
            </div>

            <div class="form-group row">
                <label for="inputWrongName" class="col-sm-4 col-form-label">Wrong Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="inputWrongName" placeholder="">
                </div>
            </div>

            <div class="form-group row">
                <label for="inputCorrectName" class="col-sm-4 col-form-label">Correct Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" id="inputCorrectName" placeholder="">
                </div>
            </div>

            <button type="button" class="btn btn-primary">Update</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "CorrectNameAndMatricComponent"
    }
</script>

<style scoped>

</style>
