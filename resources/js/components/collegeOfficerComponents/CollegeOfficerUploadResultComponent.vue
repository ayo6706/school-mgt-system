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
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</template>

<script>
    export default {
        name: "CollegeOfficerUploadResultComponent"
    }
</script>

<style scoped>

</style>
