<!DOCTYPE html>

<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Student Result</title>
    <style>

        th {
            font-size: 40px;
            text-align: center;
            font-weight: bold;
        }

        tr > td {
            border-bottom: 3px solid #000000;
        }

    </style>
</head>

<body>

    <table>
    <thead>
        <tr>
            <th>
                <img src={{storage_path('image/oduduwaUniversityLogo.jpeg')}}>
            </th>
        </tr>
        <tr>
            <th colspan="11">
                <h1>ODUDUWA UNIVERSITY IPETUMODU</h1>
            </th>
        </tr>
        <tr>
            <th colspan="11">
                <h1>RAMON ADEDOYIN COLLEGE OF NATURAL AND APPLIED SCIENCES</h1>
            </th>
        </tr>
        <tr>
            <th colspan="11"><b>Session: {{$resultInfo['sessionYearInitial']}}/{{$resultInfo['sessionYearFinal']}}</b></th>
        </tr>
        <tr>
            <th colspan="11"><b>Semester: {{$resultInfo['semesterName']}}</b></th>
        </tr>
        <tr>
            <th colspan="11"><b>Department: {{$resultInfo['departmentName']}}</b></th>
        </tr>
        <tr>
            <th colspan="11"><b>Level: {{$resultInfo['level']}}</b></th>
        </tr>

        <tr>
            {{--<th> </th>--}}
            {{--<th> </th>--}}
            {{--@foreach($tableHeaders as $tableHeader)--}}
                {{--<th>{{$tableHeader['courseUnit']}} unit(s)</th>--}}
            {{--@endforeach--}}
        </tr>
        <tr>
            <th>Name</th>
            <th>Matric No</th>
            @foreach($tableHeaders as $tableHeader)
            <th>{{$tableHeader['courseCode']}} ({{$tableHeader['courseUnit']}})</th>
            @endforeach
            <th>TU</th>
            <th>TP</th>
            <th>GPA</th>
            <th>REMARKS</th>
        </tr>

    </thead>
    <tbody>
    @foreach($studentResults as $studentResult)
        <tr>
            <td>{{$studentResult['student']->name}}</td>
            <td>{{$studentResult['student']->matricNo}}</td>
            @foreach($studentResult['grades'] as $grade)
                <td>{{$grade}}</td>
            @endforeach
            <td>{{$studentResult['tu']}}</td>
            <td>{{$studentResult['tp']}}</td>
            <td>{{$studentResult['gpa']}}</td>
            <td>{{$studentResult['remark']}}</td>
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
</body>
</html>
