<?php 

// get url -> uuid
$urlsplit = preg_split("/\//",trim($_SERVER['REQUEST_URI'],"/"));
$uuid = array_pop($urlsplit)."";
$root = "/".join($urlsplit,"/");

?>
<!doctype html>
<html xmlns:ng="http://angularjs.org" id="ng-app" ng-app="iati_widget" lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!--[if lte IE 8]><script src="<?php echo $root;?>/js/json2.js"></script><![endif]-->

  <!-- font -->
  <style type="text/css">.tk-museo-sans{font-family:"museo-sans",sans-serif;}</style>
  <style type="text/css">.tk-museo-sans{font-family:"museo-sans",sans-serif;}</style>
  <link rel="stylesheet" href="http://use.typekit.net/c/3dc2b5/museo-sans:i1:i7:n1:n3:n5:n7:n9.Py9:H:1,PyG:H:1,Py8:H:1,Py6:H:1,PyC:H:1,PyF:H:1,PyH:H:1/d?3bb2a6e53c9684ffdc9a9bf31f5b2a62623a71fdbda06dee3874e56ac6a5111156e608e180ea4593c0ccd4ad05733edde9ae1dfde252f09f8963a5f3464d43c39417cc55e742ca9c24af3e9072b215d9c99ced6d8ec555d4fd41225702b7cfdffd010fe8cf393486562842c3092354aeb9bdc9a4a5e389f8916226c1bc5bc61899c4abfb8750c71c6473d662b02b5f93c769f83afc61317342581b">

  <link type="text/css" rel="stylesheet" href="<?php echo $root;?>/css/fontello/css/fontello.css" media="all" />
  <link type="text/css" rel="stylesheet" href="<?php echo $root;?>/css/_main.css" media="all" />

  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.10/angular.min.js"></script>

</head>
<body>
  <div id='top'></div>
  <div id='head'>
    <div id='spinner' ng-hide='loaded'></div>
    <h1>{{title}}</h1>
    <a href='' ng-href='{{download}}' ng-model='download_text' class="icon-download"></a>
    <a href='' ng-href='{{link_to_columby}}' target="_blank" ng-model='download_text' class="icon-link-ext"></a>
    <a href='' ng-href='http://www.columby.com' target="_blank"><div id='logo'></div></a>
  </div>

  <div id='error' ng-show='error'>
    {{error_message}}<br>
    {{uuid}}
  </div>

  <div class="iati" ng-controller="iatiCtrl" ng-show="$root.loaded && !$root.error" uuid="<?php echo $uuid;?>">
    <div class="iati-frame">
      <div class="app">
        <div>
          <div class="spinner" ng-show="loading"></div>

          <div class="rij contact"><h3>Contact:</h3></div>
          <table>
            <tr ng-repeat="(k,v) in info.data.organization | key:'title'">
              <td class="span5">Organization</td>
              <td>{{v}}</td>
            </tr>
            <tr ng-repeat="(k,v) in info.data | key:'author_email'"><td class="span5">Contact e-mail</td><td>{{v}}</td></tr>
          </table>

          <div class="rij details"><h3>Details:</h3></div>
          
          <table>

            <tr ng-repeat="(k,v) in info.data | key:'file_type'"><td class="span5">File Type</td><td>{{v}}</td></tr>
            <tr ng-repeat="(k,v) in info.data | key:'license'"><td>License</td><td>{{v}}</td></tr>

            <tr ng-repeat="(k,v) in info.data | key:'xml:language'"><td>language</td><td>{{v}}</td></tr>
            <tr ng-repeat="(k,v) in info.data | key:'recipient_country'"><td>Recipient Country</td><td>{{v}}</td></tr>
            <tr ng-repeat="(k,v) in info.data | key:'publisher_source_type'"><td>Publisher type</td><td>{{v}}</td></tr>
            <tr ng-repeat="(k,v) in info.data | key:'publisher_organization_type'"><td>Publisher Organization Type </td><td>{{v}}</td></tr>
            <tr ng-repeat="(k,v) in info.data | key:'publisher_iati_id'"><td>Publisher Identifier</td><td>{{v}}</td></tr>
            <tr ng-repeat="(k,v) in info.data | key:'publisher_country'"><td>Publisher Country</td><td>{{v}}</td></tr>
            <tr ng-repeat="(k,v) in info.data | key:'verified'"><td>Verified</td><td>{{v}}</td></tr>
            <tr ng-repeat="(k,v) in info.data | key:'archive_file'"><td>Archive File</td><td>{{v}}</td></tr>
            <tr ng-repeat="(k,v) in info.data | key:'data_updated'"><td>Data Updated</td><td>{{v}}</td></tr>
            <tr ng-repeat="(k,v) in info.data | key:'record_updated'"><td>Record Updated</td><td>{{v}}</td></tr>
            
          </table>
        <!-- show all: <div class="span7">table><tr ng-repeat="(k,v) in info.data"><td>{{k}}</td><td>{{v}}</td></tr></table></div>-->
      <div class="rij activities"><h3>IATI-Activities:</h3></div>
      <div id="paging">
        <div class="spinner" ng-show="actloading">&nbsp;</div>
        <div id="total">total activities: {{pager.total}}</div>
        <div id="prev" ng-click="gotopage(-1)" ng-class="{'active':pager.page>1}">&laquo;</div>
        <div id="page">{{pager.page}}/{{pager.pages}}</div>
        <div id="next" ng-click="gotopage(1)" ng-class="{'active':pager.page<pager.pages}">&raquo;</div>
      </div>
      
      <div class="rij activity" ng-repeat="node in node.sub | filter:'iati-activity'" ng-include="'row.html'"></div>
      
      <div id="paging">
        <div class="spinner" ng-show="actloading"></div>
        <div id="total">total activities: {{pager.total}}</div>
        <div id="prev" ng-click="gotopage(-1)" ng-class="{'active':pager.page>1}">&laquo;</div>
        <div id="page">{{pager.page}}/{{pager.pages}}</div>
        <div id="next" ng-click="gotopage(1)" ng-class="{'active':pager.page<pager.pages}">&raquo;</div>
      </div>
  </div>
</div>
</div>
</div>
<div id="bottom"></div>


<script type='text/ng-template' id='row.html'>
  <div class="klik">
    <h5 open class="open"><i ng-class="{'icon-minus-squared-small':node.open,'icon-plus-squared-small':!node.open}"></i> <b>{{node['iati-identifier']}}</b> {{node.description}}</h5>
    <div class="description">
      {{node.title}}
    </div>
    <div class="attributes">
      <div class="attribute" ng-repeat="(k,v) in node.attributes">
        <label>{{k}}</label>: {{v}}
      </div>
    </div>
    <div class="value" ng-model="node.value">{{node.value}}</div>
    <br />
    <div class="spinner" ng-show="node.loading"></div>

    
    
    <div class="rij" ng-include="'activity.html'" ng-show="node.open"></div>
  </div>
  </script>

  <script type="text/ng-template" id="activity.html">

  <div class="description-full" ng-click="toggle()">
    <p ng-hide="descriptionExpanded" ng-repeat="desc in node.sub.description | limitTo:2 ">{{desc.value}}</p>
    <p ng-hide="descriptionExpanded" ng-show="node.sub.description"><em>more ... </em></p>
    <div class="full" ng-show="descriptionExpanded">
      <p ng-repeat="desc in node.sub.description">{{desc.value}}</p>
    </div>
  </div>

  <div class="row-fluid">
    <div class="span7">
      <table class="details">
        <tr>
          <td class="span5">Activity status</td>
          <td>{{node.sub['activity-status'][0].value}}</td>
        </tr>
        <tr>
          <td>Default tied status</td>
          <td>{{node.sub['default-tied-status'][0].value}}</td>
        </tr>
        <tr>
          <td>IATI identifier</td>
          <td>{{node.sub['iati-identifier'][0].value}}</td>
        </tr>
        <tr>
          <td>Sector</td>
          <td>
            <ul>
              <li ng-repeat="sector in node.sub.sector">{{sector.attributes.percentage}}% - {{sector.value}}</li>
            </ul>
          </td>
        </tr>
        <tr>
          <td>Reporting Organisation(s)</td>
          <td>
            <ul><li ng-repeat="org in node.sub['reporting-org']">{{org.value}}</li></ul>
            </td>
        </tr>
        <tr>
          <td>Participating Organisation(s)</td>
          <td>
            <ul><li ng-repeat="org in node.sub['participating-org']">{{org.value}} ({{org.attributes.role}})</li></ul>
          </td>
        </tr>

        <tr>
          <td>Recipient Country</td>
          <td><ul><li ng-repeat="country in node.sub['recipient-country']">{{country.attributes.percentage}}% - {{country.value}}</li></ul></td>
        </tr>

        <tr>
          <td>Finance type</td>
          <td><ul><li ng-repeat="type in node.sub['default-finance-type']">{{type.value}}</li></ul></td>
        </tr>

        <tr>
          <td>Activity date</td>
          <td>
            <ul>
              <li ng-repeat="org in node.sub['activity-date']">
                {{org.attributes.type}}: {{org.attributes['iso-date']}}
              </li>
            </ul>
          </td>
        </tr>

        <tr ng-repeat="org in node.sub['related-activity']">
          <td>related activity</td>
          <td> {{org.value}}</td>
        </tr>

        <tr>
          <td>Contact info</td>
          <td>
            <ul>
              <li ng-repeat="contact in node.sub['contact-info'][0]">
              Organization: {{contact[0].organisation[0].value}}<br />
              Person name: {{contact[0]['person-name'][0].value}}<br/>
              Telephone: {{contact[0].telephone[0].value}}<br/>
              Email: {{contact[0].email[0].value}}<br/>
              Mailing address: {{contact[0]['mailing-address'][0].value}}
              </li>
            </ul>
          </td>
        </tr>

        <tr>
          <td>Activity website</td>
          <td>
            <ul>
              <li ng-repeat="site in node.sub['activity-website']">
                <a href="{{site.value}}" target="_blank">{{site.value}}</a>
              </li>
            </ul>
          </td>
        </tr>

        <tr>
          <td>Budget</td>
          <td>
            <ul>
              <li ng-repeat="budget in node.sub.budget">
                Period start: {{budget.budget[0]['period-start'][0].attributes['iso-date']}}<br/>
                Period end: {{budget.budget[0]['period-end'][0].attributes['iso-date']}}<br/>
                Value: {{budget.budget[0].value[0].value}}
              </li>
            </ul>
          </td>
        </tr>
      </table>
    </div>
  </div>

  <h5>Transactions</h5>
  <table class="transactions header">
    <tr>
      <th>Transaction date</th>
      <th>Transaction type</th>
      <th>Disbursement channel</th>
      <th>Value</th>
    </tr>
  </table>

  <table class="transactions body">
    <tr ng-repeat="item in node.sub.transaction">
      <td>{{item.transaction[0]['transaction-date'][0].attributes['iso-date']}}</td>
      <td>{{item.transaction[0]['transaction-type'][0].value}}</td>
      <td>{{item.transaction[0]['disbursement-channel'][0].value}}</td>
      <td>{{item.transaction[0].value[0].value}}</td>
    </tr>
  </table>
    

  <h5>Results</h5>
  <table class="results">
    <tr ng-repeat="item in node.sub.result" class="result">
      <td>
      <p class="title">{{item.result[0].title[0].value}}</p>
      <ul class="indicator">
        <li><p><strong>Indicator: </strong>{{item.result[0].indicator[0].indicator[0].title[0].value}}</p></li>
        <ul class="period">
          <li>
            <p><strong>Period: </strong>
            Start: {{item.result[0].indicator[0].indicator[0].period[0].period[0]['period-start'][0].attributes['iso-date']}} - 
            End: {{item.result[0].indicator[0].indicator[0].period[0].period[0]['period-end'][0].attributes['iso-date']}} - 
            Target: {{item.result[0].indicator[0].indicator[0].period[0].period[0].target[0].attributes.value}} - 
            Actual: {{item.result[0].indicator[0].indicator[0].period[0].period[0].actual[0].attributes.value}}</p/>
          </li>
        </ul>
      </ul>
      </td>
    </tr>
  </table>

  

</script>


<script src="<?php echo $root;?>/js/jquery.min.js"></script>
<script src="<?php echo $root;?>/js/angular-iati.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.10/angular-route.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/angularjs/1.2.10/angular-cookies.min.js"></script>

<script>var _gaq = _gaq || [];_gaq.push(["_setAccount", ""]);_gaq.push(["_trackPageview"]);(function() {var ga = document.createElement("script");ga.type = "text/javascript";ga.async = true;ga.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";var s = document.getElementsByTagName("script")[0];s.parentNode.insertBefore(ga, s);})();</script>

</body>
</html>