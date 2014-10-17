<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
<body id="abody">
<?php
echo '<script type="text/javascript">var postLogin = '.$_POST['login'].'; var postPass = '.$_POST['password'].'</script>';
?>
    <script type="text/javascript">

    var c = $.ajax({
        type: "POST",
        url: "getData.php",
        data: {login: postLogin, password: postPass},
        dataType: "script",
        success: go,
        async: false
    });

    var SEMESTER_A_START = 1414310400,
        SEMESTER_A_END = 1422662399,
        SEMESTER_B_START = 1425196800,
        SEMESTER_B_END = 1435363199,

        HOUR_OFFSET = 3600,
        DAY_OFFSET = 24*3600,
        HOUR_TYPES = ["Lecture", "Targilim", "Maabada"],
        HOUR_TYPES_HEBREW = ["הרצאה", "תרגול", "מעבדה"];


    function go(data, textStatus, jqXHR){
        var Courses = {A: CoursesA, B: CoursesB};

        var ical = "BEGIN:VCALENDAR\r\n";
        ical+="PRODID:-//Google Inc//Google Calendar 70.9054//EN\r\n";
        ical+="VERSION:2.0\r\n";
        ical+="CALSCALE:GREGORIAN\r\n";
        ical+="METHOD:PUBLISH\r\n";
        ical+="X-WR-CALNAME:Studies\r\n";
        ical+="X-WR-TIMEZONE:Asia/Jerusalem\r\n";
        ical+="X-WR-CALDESC:\r\n";
        ical+="BEGIN:VTIMEZONE\r\n";
        ical+="TZID:Asia/Jerusalem\r\n";
        ical+="X-LIC-LOCATION:Asia/Jerusalem\r\n";
        ical+="BEGIN:DAYLIGHT\r\n";
        ical+="TZOFFSETFROM:+0200\r\n";
        ical+="TZOFFSETTO:+0300\r\n";
        ical+="TZNAME:IDT\r\n";
        ical+="DTSTART:20130601T000000\r\n";
        ical+="RDATE:20130329T020000\r\n";
        ical+="RDATE:20140328T020000\r\n";
        ical+="RDATE:20150327T020000\r\n";
        ical+="RDATE:20160325T020000\r\n";
        ical+="RDATE:20170324T020000\r\n";
        ical+="RDATE:20180323T020000\r\n";
        ical+="RDATE:20190329T020000\r\n";
        ical+="END:DAYLIGHT\r\n";
        ical+="BEGIN:STANDARD\r\n";
        ical+="TZOFFSETFROM:+0300\r\n";
        ical+="TZOFFSETTO:+0200\r\n";
        ical+="TZNAME:IST\r\n";
        ical+="DTSTART:20131101T000000\r\n";
        ical+="RDATE:20131027T020000\r\n";
        ical+="RDATE:20141026T020000\r\n";
        ical+="RDATE:20151025T020000\r\n";
        ical+="RDATE:20161030T020000\r\n";
        ical+="RDATE:20171029T020000\r\n";
        ical+="RDATE:20181028T020000\r\n";
        ical+="RDATE:20191027T020000\r\n";
        ical+="END:STANDARD\r\n";
        ical+="END:VTIMEZONE\r\n";


        parseSemester("A", SEMESTER_A_START, SEMESTER_A_END, Courses);
        parseSemester("B", SEMESTER_B_START, SEMESTER_B_END, Courses);

        ical+="END:VCALENDAR";

        $("<form action=\"ical.php\" method=\"POST\"></form>")
            .append(
                $("<textarea name=\"ical\" id=\"\" cols=\"30\" rows=\"10\"></textarea>")
                    .val(ical))
            .submit();

    }

    function parseSemester(semester, start, end, Courses){
        $(CourseList[semester]).each(function(index, el){
            var course = Courses[semester][el];
            if (course.Semester !== "3"){
                $(HOUR_TYPES).each(function(index, el){
                    parseHourType(el, HOUR_TYPES_HEBREW[index], course, start, end);
                });
            }

        });
    }

    function parseHourType(type, type_heb, course, start, end){
        var OType,
            typeGroups = type+"Groups",
            typeGroupsSelected = type+"GroupsSelected";

        $(course[typeGroups]).each(function(index, el){
            if (course[typeGroupsSelected] === el.ID){
                OType = el;
            }
        });

        if (OType !== undefined){
            $(OType.Lessons).each(function(index, el){
                var date = start === SEMESTER_A_START ? "201410"+(26+(el.Day-1)) : "2015030"+(el.Day),
                    startTime = el.Start.replace(":", "")+"00",
                    endTime = el.End.replace(":", "")+"00",
                    endDate = start === SEMESTER_A_START ? "20150130T235959Z" : "20150626T235959Z";

                ical+="BEGIN:VEVENT\r\n";
                ical+="DTSTART;TZID=Asia/Jerusalem:"+date+"T"+startTime+"\r\n";
                ical+="DTEND;TZID=Asia/Jerusalem:"+date+"T"+endTime+"\r\n";
                ical+="RRULE:FREQ=WEEKLY;UNTIL="+endDate+"\r\n";                
                ical+="DTSTAMP:20141018T000000Z"+"\r\n";
                ical+="UID:"+rand(26)+"@google.com\r\n";
                ical+="CREATED:20141018T000000Z"+"\r\n";
                ical+="DESCRIPTION:"+"\r\n";
                ical+="LAST-MODIFIED:20141018T000000Z"+"\r\n";
                ical+="LOCATION:"+el.Place.replace("&#39;", "")+"\r\n";
                ical+="SEQUENCE:0\r\n";
                ical+="STATUS:CONFIRMED\r\n";
                ical+="SUMMARY:"+course.Number+" "+course.Name+" - "+type_heb+"\r\n";
                ical+="TRANSP:OPAQUE\r\n";
                ical+="END:VEVENT\r\n";
            });
        }
    }

    function rand(len)
    {
        var text = "";
        var possible = "abcdefghijklmnopqrstuvwxyz0123456789";

        for( var i=0; i < len; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }
    </script>

</body>
</html>