<?php require_once("drupal.php");?>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link type="text/css" rel="stylesheet" href="<?php echo $root;?>/css/_main.css" media="all" />

  <!-- font -->
  <style type="text/css">.tk-museo-sans{font-family:"museo-sans",sans-serif;}</style>
  <style type="text/css">.tk-museo-sans{font-family:"museo-sans",sans-serif;}</style>
  <link rel="stylesheet" href="http://use.typekit.net/c/3dc2b5/museo-sans:i1:i7:n1:n3:n5:n7:n9.Py9:H:1,PyG:H:1,Py8:H:1,Py6:H:1,PyC:H:1,PyF:H:1,PyH:H:1/d?3bb2a6e53c9684ffdc9a9bf31f5b2a62623a71fdbda06dee3874e56ac6a5111156e608e180ea4593c0ccd4ad05733edde9ae1dfde252f09f8963a5f3464d43c39417cc55e742ca9c24af3e9072b215d9c99ced6d8ec555d4fd41225702b7cfdffd010fe8cf393486562842c3092354aeb9bdc9a4a5e389f8916226c1bc5bc61899c4abfb8750c71c6473d662b02b5f93c769f83afc61317342581b">
</head>
<body>

  <div id='head'>
    <h1>DATA preview</h1>
    <div id='logo'></div>
  </div>
  
  <!-- same way as drupal to get uuid -->
  <div id="table-js" class='iati' uuid="<?php echo $uuid;?>"></div>

  <?php echo $angular; // defined in drupal.php?>
  
  
  <script src="<?php echo $root;?>/js/jquery.min.js"></script>
  <script src="<?php echo $root;?>/js/angular.min.js"></script>
  <script src="<?php echo $root;?>/js/angular-iati.js"></script>

</body>
</html>