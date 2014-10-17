<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>HUJI Timetable Export</title>

    <!-- Bootstrap core CSS -->
    <link href="http://getbootstrap.com/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="http://getbootstrap.com/examples/signin/signin.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <h2>This is a tool to export your student timetable from Rishum-Net system to iCalendar format,
      that can be imported to Google Calendar, Outlook, or whatever app you are using.</h2>
      
      <h4>As this tool works with Rishum-Net, it is available for students of Faculty of Sciences only. Sorry.</h4>
      
      <h4><strong>Important!</strong> As you could notice, this site does not have SSL protection,
      so your login info will go to Rishum-Net server in plain text. You are warned.</h4>
      
      <form class="form-signin" role="form" method="POST" action="compile.php">
        <input type="text" name="login" class="form-control" placeholder="Student ID" required autofocus>
        <input type="password" name="password" class="form-control" placeholder="Personal Code" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Get my timetable</button>
      </form>

      <h4><strong>Please note:</strong> current version is the first one. That means, it supports
      currently only 2014-2015 academic year, and other limitations.</h4>
      <h4>Source code is hosted on <a href="//github.com/griffonn/hujitte">GitHub</a></h4>
    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="http://getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
