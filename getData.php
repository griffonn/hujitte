<?php

header("Content-Type: application/javascript; charset=utf-8");

if (!is_numeric($_POST['login']) || !is_numeric($_POST['password'])
    || !strlen($_POST['login'])==8 || strlen($_POST['password'])>5 || strlen($_POST['password'])<4){
    die('Invalid data');
}

$base_url = 'https://rishum-net.huji.ac.il/site/student/';
$login_data = 'login='.$_POST['login'].'&password='.$_POST['password'];

$ch = curl_init();

// Options for all requests
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); 
curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);

// First request: get session-id for logging in
curl_setopt($ch, CURLOPT_URL, $base_url.'login.asp');
$response = curl_exec($ch);
if (FALSE === $response)
    throw new Exception(curl_error($ch), curl_errno($ch));

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);

preg_match("/session\-id=([a-f0-9]{32})/", $header, $sessionIds);

// Second request: log in and get new session-id
curl_setopt($ch, CURLOPT_URL, $base_url.'login_handle.asp');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $login_data);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
curl_setopt($ch, CURLOPT_COOKIE, "session-id=".$sessionIds[1]);

$response = curl_exec($ch);
if (FALSE === $response)
    throw new Exception(curl_error($ch), curl_errno($ch));

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);

preg_match("/session\-id=([a-f0-9]{32})/", $header, $sessionIds);

// Third request: get timetable
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_URL, $base_url.'build_time_table.asp');
curl_setopt($ch, CURLOPT_COOKIE, "session-id=".$sessionIds[1]);

$response = curl_exec($ch);
if (FALSE === $response)
    throw new Exception(curl_error($ch), curl_errno($ch));

$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);

// Close connection
curl_close($ch);
// $body = file_get_contents("data.html");

preg_match("/<script language=JavaScript>.*<script type=\"text/s", $body, $scripts);
echo trim(preg_replace("/<\/script>\s+<script type=\"text/s", "", ltrim($scripts[0], "<script language=JavaScript>")));

$courseList = "var CourseList = {A: [";

preg_match_all("/CoursesA\[\"(\d{5})\"/", $body, $coursesA);

foreach (array_unique($coursesA[1]) as $key => $courseNumber) {
    $courseList .= $courseNumber.",";
}

$courseList = rtrim($courseList, ",");
$courseList .= "], B: [";

preg_match_all("/CoursesB\[\"(\d{5})\"/", $body, $coursesB);

foreach (array_unique($coursesB[1]) as $key => $courseNumber) {
    $courseList .= $courseNumber.",";
}

$courseList = rtrim($courseList, ",");
$courseList .= "]};";

echo $courseList;
?>