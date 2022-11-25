<table>
    <thead>
    <tr>
        <th>Session</th>
        <th>{{$resultInfo['sessionYearInitial']}}/{{$resultInfo['sessionYearFinal']}}</th>
    </tr>
    <tr>
        <th>Semester</th>
        <th>{{$resultInfo['semesterName']}}</th>
    </tr>
    <tr>
        <th>
            Department
        </th>
        <th>{{$resultInfo['departmentName']}}</th>
    </tr>
    <tr>
        <th>Level</th>
        <th>{{$resultInfo['level']}}</th>
    </tr>
    <tr>
        <th>Course Name</th>
        <th>{{$resultInfo['courseName']}}</th>
    </tr>
    <tr>
        <th>Course Code</th>
        <th>{{$resultInfo['courseCode']}}</th>
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
            <td>{{$studentResult['name']}}</td>
            <td>{{$studentResult['matricNo']}}</td>
            <td>{{$studentResult['att']}}</td>
            <td>{{$studentResult['test']}}</td>
            <td>{{$studentResult['exam']}}</td>
            <td>{{$studentResult['total']}}</td>
            <td>{{$studentResult['grade']}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
