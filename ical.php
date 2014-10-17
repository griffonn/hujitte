<?php
header("Content-type: text/ics");
header("Content-Disposition: attachment; filename=huji_timetable.ics");
echo $_POST['ical'];
?>