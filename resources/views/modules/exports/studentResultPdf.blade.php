<!DOCTYPE html>

<html>

<head>

    <title>Student Result</title>
    <style>
        body {
            margin: 0px;
            font-family: sans-serif;
            font-size: 10pt;
        }

        /* Style the header */
        /* .header {

        } */
        .logo {
            padding: 20px;
            float: left;
            width: 74.4px;
        }
        img {
            width: 150px;
            max-height: 100px;
        }
        .headerBody {
            margin-left: 100px;
            width: 80%;
            /*padding: 20px;*/
            text-align: center;
        }
        .motto {
            font-size: 7.5pt;
            font-style: italic;
        }

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        .biodata {
            /*width: 60%;*/
            /*margin-left: 26%;*/
        }
        .leftBio {
            /*width: 50%;*/
            float: left;
        }
        .rightBio {
            /*width: 50%;*/
            float: right;
        }

        .bioKey {
            font-weight: bold;
        }

        .resultTable {
            /*border-collapse: collapse;*/
            margin-left: 16%;
            width: 100%;
            margin-bottom: 20px;
            margin-top: 16px;
        }
        .resultSummary {
            width: 30%;
            margin-left: 16%;
            margin-bottom: 40px;
            font-size: 7.3pt;
            font-weight: bold;
        }

        /*table, td, th {*/
            /*padding: 6px;*/
            /*border: 1px solid black;*/
        /*}*/

        /*table {*/
            /*border-collapse: collapse;*/
            /*width: 100%;*/
        /*}*/

        th {
            text-align: left;
            font-weight: bold;
        }



        th, td {
            padding: 2px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .signature {
            margin: 5px;
        }
        .signatureLeft {
            float: left;
        }
        .signatureRight {
            float: right;
        }
        .sign {
            padding: 5px;
        }
        .signTitle {
            padding: 5px;
        }

        .gradingSystem {
            float: right;
            font-size: 4.5pt;
            font-weight: bold;
            width: 30%;
        }

        .footer {
            text-align: center;
            color: red;
            margin: 13px;
        }
    </style>

</head>

<body>
<div class="header">
    <div class="logo">
        <img src={{storage_path('image/oduduwaUniversityLogo.jpeg')}}>
    </div>
    <div class="headerBody">
        <h3>ODUDUWA UNIVERSITY IPETUMODU</h3>
        <h5>P.M.B, 5533, ILE-IFE</h5>
        <span class="motto"> Motto: Learning for Human Development</span>

        <h5>RAMON ADEDOYIN COLLEGE OF NATURAL AND APPLIED SCIENCES</h5>

        <p>DEPARTMENT OF {{$resultInfo['departmentName']}}</p>

        <p>{{$resultInfo['sessionYearInitial']}}/{{$resultInfo['sessionYearFinal']}} SESSION RESULT</p>
    </div>
</div>
<div class="row">
    <div class="biodata">
        <div class="leftBio">
            <p><span class="bioKey">STUDENT NAME:</span> {{$studentResult['student']->name}}</p>
            <p><span class="bioKey">MATRIC NUMBER:</span> {{$studentResult['student']->matricNo}}</p>
        </div>
        <div class="rightBio">
            <p><span class="bioKey">SEMESTER:</span> {{$resultInfo['semesterName']}}</p>
            <p><span class="bioKey">LEVEL:</span> {{$resultInfo['level']}}</p>
        </div>
    </div>
</div>

<table class="resultTable">
    <thead>
    <tr>
        <th>COURSE CODE</th>
        <th>COURSE TITLE</th>
        <th>UNIT</th>
        <th>SCORE</th>
        <th>GRADE</th>
    </tr>
    </thead>
    <tbody>
     @foreach($studentResult['results'] as $result)
        <tr>
            <td>{{ $result['courseInfo']->courseCode }}</td>
                <td>{{ $result['courseInfo']->courseName }}</td>
                <td>{{ $result['unit'] }}</td>
                <td>{{ $result['result']->total }}</td>
                <td>{{ $result['result']->grade }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<div>
    <table class="resultSummary">
        <thead>
        <tr>
            <th></th>
            <th>TU</th>
            <th>TP</th>
            <th>GPA</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>CURRENT</td>
            <td>{{$studentResult['tu']}}</td>
            <td>{{$studentResult['tp']}}</td>
            <td>{{$studentResult['gpa']}}</td>
        </tr>
        <tr>
            <td>CUMMULATIVE</td>
            <td>{{$studentResult['ctu']}}</td>
            <td>{{$studentResult['ctp']}}</td>
            <td>{{$studentResult['cgpa']}}</td>
        </tr>
        </tbody>
    </table>
</div>

<div class="row">

    <div class="signature">

        <div class="signatureLeft">
            <div class="sign">
                _______________________________
            </div>
            <div class="signTitle">
                Head of Department
            </div>
        </div>

        <div class="signatureRight">
            <div class="sign">
                _______________________________
            </div>
            <div class="signTitle">
                 Office of the Provost
            </div>
        </div>

    </div>
</div>

<div class="row">
    <div class="gradingSystem">
        <h3>Grading System</h3>
        <table>
            <thead>
            <tr>
                <th>Mark %</th>
                <th>Grade Point</th>
                <th>Grade</th>
                <th>Level of Achievement</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>70-100</td>
                <td>5</td>
                <td>A</td>
                <td>Excellent</td>
            </tr>
            <tr>
                <td>60-69</td>
                <td>4</td>
                <td>B</td>
                <td>Very Good</td>
            </tr>
            <tr>
                <td>50-59</td>
                <td>3</td>
                <td>C</td>
                <td>Good</td>
            </tr>
            <tr>
                <td>45-49</td>
                <td>2</td>
                <td>D</td>
                <td>Satifactory</td>
            </tr>
            <tr>
                <td>0-44</td>
                <td>0</td>
                <td>F</td>
                <td>Fail</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="footer">
        <span>PLEASE NOTE: Any alteration renders this document invalid</span>
    </div>
</div>

</body>

</html>

