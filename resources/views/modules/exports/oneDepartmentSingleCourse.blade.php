<table>
    <thead>
    <tr>
        <th>
            <img src={{storage_path('image/oduduwaUniversityLogo.jpeg')}}>
        </th>
    </tr>
    <tr>
        <th colspan="7">
            <h1>ODUDUWA UNIVERSITY IPETUMODU</h1>
        </th>
    </tr>
    <tr>
        <th colspan="7">
            <h1>RAMON ADEDOYIN COLLEGE OF NATURAL AND APPLIED SCIENCES</h1>
        </th>
    </tr>
    <tr>
        <th colspan="7">Session: {{$resultInfo['sessionYearInitial']}}/{{$resultInfo['sessionYearFinal']}}</th>
    </tr>
    <tr>
        <th colspan="7">Semester: {{$resultInfo['semesterName']}}</th>
    </tr>
    <tr>
        <th colspan="7">Department: {{$resultInfo['departmentName']}}</th>
    </tr>
    <tr>
        <th colspan="7">Level: {{$resultInfo['level']}}</th>
    </tr>
    <tr>
        <th colspan="7">Course Name: {{$resultInfo['courseName']}}</th>
    </tr>
    <tr>
        <th colspan="7">Course Code: {{$resultInfo['courseCode']}}</th>
    </tr>
    <tr>
        <th> </th>
    </tr>
    <tr>
        <th>Name</th>
        <th>Matric No</th>
        <th>Att.</th>
        <th>Test</th>
        <th>Exam</th>
        <th>Total</th>
        <th>Grade</th>
    </tr>
    </thead>
    <tbody>
    @foreach($studentResults as $studentResult)
        <tr>
            <td>{{$studentResult['student']->name}}</td>
            <td>{{$studentResult['student']->matricNo}}</td>
            <td>{{$studentResult['result']->att}}</td>
            <td>{{$studentResult['result']->test}}</td>
            <td>{{$studentResult['result']->exam}}</td>
            <td>{{$studentResult['result']->total}}</td>
            <td>{{$studentResult['result']->grade}}</td>
        </tr>
    @endforeach
    </tbody>
</table>


<table>
    <thead>
    <tr>
        <th>ANALYSIS</th>
    </tr>
        <tr>
            <th>Total number of student</th>
            <th>{{$analysis['noOfStudents']}}</th>
        </tr>
        <tr>
            <th></th>
            <th>FAILED</th>
            <th>PASSED</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Number Of Student</td>
            <td>{{$analysis['noOfFailures']}}</td>
            <td>{{$analysis['noOfPasses']}}</td>
        </tr>
        <tr>
            <td>Percentage(%)</td>
            <td>{{$analysis['percentageFail']}}</td>
            <td>{{$analysis['percentagePass']}}</td>
        </tr>
    </tbody>
</table>
