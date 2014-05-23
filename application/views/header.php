<html>
  <head>
  <title><?=$data['title']?></title>
  <link href="/public/css/style.css?cache=260214-1343" media="" rel="stylesheet" type="text/css"/>
  <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
  <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
  <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
  <script type="text/javascript" src="/public/js/vinyl.js"></script>
  </head>
  <body>
	  <div id="wrapper">
	  <div id="header">
      <h1><?=$data['title']?></h1>
	  </div>
    <div id="menu">
      <ul>
        <li><a href="/">Search</a></li>
        <li><a href="/track/">Tracks</a></li>
        <li><a href="/artist/">Artists</a></li>
        <li><a href="/label/">Labels</a></li>
        <li><a href="/user/">Users</a></li>
        <li><a href="/about/">About</a></li>
      </ul>
    </div>
