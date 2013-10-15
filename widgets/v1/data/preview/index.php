<?php 

// get url -> uuid
$urlsplit = preg_split("/\//",trim($_SERVER['REQUEST_URI'],"/"));
$uuid = array_pop($urlsplit)."";
$root = "/".join($urlsplit,"/");

?>
<html lang="en" dir="ltr" ng-app="myApp" >
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- font -->
	<style type="text/css">.tk-museo-sans{font-family:"museo-sans",sans-serif;}</style>
	<style type="text/css">.tk-museo-sans{font-family:"museo-sans",sans-serif;}</style>
	<link rel="stylesheet" href="http://use.typekit.net/c/3dc2b5/museo-sans:i1:i7:n1:n3:n5:n7:n9.Py9:H:1,PyG:H:1,Py8:H:1,Py6:H:1,PyC:H:1,PyF:H:1,PyH:H:1/d?3bb2a6e53c9684ffdc9a9bf31f5b2a62623a71fdbda06dee3874e56ac6a5111156e608e180ea4593c0ccd4ad05733edde9ae1dfde252f09f8963a5f3464d43c39417cc55e742ca9c24af3e9072b215d9c99ced6d8ec555d4fd41225702b7cfdffd010fe8cf393486562842c3092354aeb9bdc9a4a5e389f8916226c1bc5bc61899c4abfb8750c71c6473d662b02b5f93c769f83afc61317342581b">

	<link type="text/css" rel="stylesheet" href="<?php echo $root;?>/css/fontello/css/fontello.css" media="all" />
	<link type="text/css" rel="stylesheet" href="<?php echo $root;?>/css/_main.css" media="all" />

</head>
<body>

	<div id='head'>
		<div id='spinner' ng-hide='loaded'></div>
		<h1>{{title}}</h1>
		<a href='' ng-href='{{download}}' ng-model='download_text'>{{download_text}} <i class="icon-download" ng-show="download_text"></i></a>
		<a href='' ng-href='{{link_to_columby}}' target="_blank"><div id='logo'></div></a>
	</div>

	<div id='error' ng-show='error'>
		{{error_message}}<br>
		{{uuid}}
	</div>

	<div class="iati" ng-controller="iati" ng-show="$root.loaded && !$root.error" uuid="<?php echo $uuid;?>">
		<div class="iati-frame">
			<div class="app">
				<div>
					<div class="spinner" ng-show="loading"></div>

					<div class="rij contact"><h3>Contact:</h3></div>
					<table>
						<tr ng-repeat="(k,v) in info.data | key:'contact-email'"><td class="span5">Contact e-mail</td><td>{{v}}</td></tr>
					</table>

					<div class="rij details"><h3>Details:</h3></div>

					<table>
						<tr ng-repeat="(k,v) in info.data | key:'file_type'"><td class="span5">File Type</td><td>{{v}}</td></tr>
						<tr ng-repeat="(k,v) in info.data | key:'xml:lang'"><td>language</td><td>{{v}}</td></tr>
						<tr ng-repeat="(k,v) in info.data | key:'recipient'"><td>Recipient Country</td><td>{{v}}</td></tr>
						<tr ng-repeat="(k,v) in info.data | key:'publisher-type'"><td>Publisher type</td><td>{{v}}</td></tr>
						<tr ng-repeat="(k,v) in info.data | key:'publisher-org-type'"><td>Publisher Organization Type </td><td>{{v}}</td></tr>
						<tr ng-repeat="(k,v) in info.data | key:'publisher-id'"><td>Publisher Identifier</td><td>{{v}}</td></tr>
						<tr ng-repeat="(k,v) in info.data | key:'publisher-country'"><td>Publisher Country</td><td>{{v}}</td></tr>
						<tr ng-repeat="(k,v) in info.data | key:'verification-status'"><td>Verified</td><td>{{v}}</td></tr>
						<tr ng-repeat="(k,v) in info.data | key:'archive-file'"><td>Archive File</td><td>{{v}}</td></tr>
						<tr ng-repeat="(k,v) in info.data | key:'generated-datetime'"><td>Data Updated</td><td>{{v}}</td></tr>
						<tr ng-repeat="(k,v) in info.data | key:'last-updated-datetime'"><td>Record Updated</td><td>{{v}}</td></tr>
						<tr ng-repeat="(k,v) in info.data | key:'license'"><td>License</td><td>{{v}}</td></tr>
					</table>
        <!-- show all: <div class="span7">table><tr ng-repeat="(k,v) in info.data"><td>{{k}}</td><td>{{v}}</td></tr></table></div>-->
      <div class="rij activities"><h3>Iati-Activities:</h3></div>
      <div id="paging">
      	<div class="spinner" ng-show="actloading">&nbsp;</div>
      	<div id="total">total activities: {{data.total}}</div>
      	<div id="prev" ng-click="gotopage(-1)" ng-class="{'active':data.page>1}">&laquo;</div>
      	<div id="page">{{data.page}}/{{data.pages}}</div>
      	<div id="next" ng-click="gotopage(1)" ng-class="{'active':data.page<data.pages}">&raquo;</div>
      </div>
      <div class="rij activity" ng-repeat="node in node.sub | filter:'iati-activity'" ng-include="'row.html'"></div>
      <div id="paging">
      	<div class="spinner" ng-show="actloading"></div>
      	<div id="total">total activities: {{data.total}}</div>
      	<div id="prev" ng-click="gotopage(-1)" ng-class="{'active':data.page>1}">&laquo;</div>
      	<div id="page">{{data.page}}/{{data.pages}}</div>
      	<div id="next" ng-click="gotopage(1)" ng-class="{'active':data.page<data.pages}">&raquo;</div>
      </div>
  </div>
</div>
</div>
</div>



<script type='text/ng-template' id='row.html'>
	<div class="klik" open>
	<h5><i ng-class="{'icon-minus-squared-small':node.open,'icon-plus-squared-small':!node.open}"></i> <b>{{node['iati-identifier']}}</b> {{node.title}}</h5>
	<div class="description">{{node.description}}</div>
	<div class="attributes">
	<div class="attribute" ng-repeat="(k,v) in node.attributes"><label>{{k}}</label>: {{v}}</div>
	</div>
	<div class="value" ng-model="node.value">{{node.value}}</div>
	<Br>
	<div class="spinner" ng-show="node.loading"></div>
	<div class="rij" ng-include="'activity.html'" ng-hide="!node.open"></div>
	</div>
	</script>

	<script type="text/ng-template" id="activity.html">
	<div class="row-fluid"><div class="span7"><table>
	<tr ng-repeat="org in node.sub | filter:'activity-status'"><td class="span5">activity status</td><td> {{org.value}}</td></tr>
	<tr ng-repeat="org in node.sub | filter:'default-tied-status'"><td>default-tied-status</td><td>{{org.value}}</td></tr>
	<tr ng-repeat="org in node.sub | filter:'iati-identifier'"><td>iati identifier</td><td> {{org.value}}</td></tr>
	<tr ng-repeat="org in node.sub | filter:'other-identifier'"><td>other identifier</td><td> {{org.value}}</td></tr>
	<tr ng-repeat="org in node.sub | filter:'reporting-org'"><td>reporting Organisation</td><td> {{org.value}}</tr>
	<tr ng-repeat="org in node.sub | filter:'participating-org'"><td>Participating Organisation</td><td> {{org.value}}</td></tr>
	<tr ng-repeat="org in node.sub | filter:'activity-date'"><td>activity date</td><td> {{org.value}}</td></tr>
	<tr ng-repeat="org in node.sub | filter:'related-activity'"><td>related activity</td><td> {{org.value}}</td></tr>
	</table></div></div>
	<div ng-repeat="node in node.sub | filter:'transaction'" class="transaction">
	<h5>Transaction:</h5>
	<div class="row-fluid"><div class="span7"><table>
	<tr ng-repeat="t in node.transactions | filter:'transaction-date'"><td class="span5">transaction date</td><td> {{t.attributes['iso-date']}}</td></tr>
	<tr ng-repeat="t in node.transactions | filter:'transaction-type'"><td>transaction type</td><td>{{t.value}}</td></tr>
	<tr ng-repeat="t in node.transactions | filter:'disbursement-channel'"><td>disbursement-channel</td><td> {{t.value}}</td></tr>
	<tr ng-repeat="t in node.transactions | filter:'value'"><td>value</td><td> {{t.value}}</td></tr>
	</table></div></div>
	</div>
</script>


<script src="<?php echo $root;?>/js/jquery.min.js"></script>
<script src="<?php echo $root;?>/js/angular.min.js"></script>
<script src="<?php echo $root;?>/js/angular-iati.js"></script>

</body>
</html>