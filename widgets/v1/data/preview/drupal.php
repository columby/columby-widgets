<?php

// get url -> uuid
$urlsplit = preg_split("/\//",trim($_SERVER['REQUEST_URI'],"/"));
$uuid = array_pop($urlsplit);
$root = "/".join($urlsplit,"/");

$template = '<script type="text/ng-template" id="row.html">
                  <div class="klik" open>
                    <h5><i ng-class="{\'icon-minus-squared-small\':node.open,\'icon-plus-squared-small\':!node.open}"></i> <b>{{node[\'iati-identifier\']}}</b> {{node.title}}</h5>
                    <div class="description">{{node.description}}</div>
                    <div class="attributes">
                      <div class="attribute" ng-repeat="(k,v) in node.attributes"><label>{{k}}</label>: {{v}}</div>
                    </div>
                    <div class="value" ng-model="node.value">{{node.value}}</div>
                    <Br>
                    <div class="spinner" ng-show="node.loading"></div>
                    <div class="rij" ng-include="\'activity.html\'" ng-hide="!node.open"></div>
                  </div>
                  </script>

                  <script type="text/ng-template" id="activity.html">
                    <div class="row-fluid"><div class="span7"><table>
                      <tr ng-repeat="org in node.sub | filter:\'activity-status\'"><td class="span5">activity status</td><td> {{org.value}}</td></tr>
                      <tr ng-repeat="org in node.sub | filter:\'default-tied-status\'"><td>default-tied-status</td><td>{{org.value}}</td></tr>
                      <tr ng-repeat="org in node.sub | filter:\'iati-identifier\'"><td>iati identifier</td><td> {{org.value}}</td></tr>
                      <tr ng-repeat="org in node.sub | filter:\'other-identifier\'"><td>other identifier</td><td> {{org.value}}</td></tr>
                      <tr ng-repeat="org in node.sub | filter:\'reporting-org\'"><td>reporting Organisation</td><td> {{org.value}}</tr>
                      <tr ng-repeat="org in node.sub | filter:\'participating-org\'"><td>Participating Organisation</td><td> {{org.value}}</td></tr>
                      <tr ng-repeat="org in node.sub | filter:\'activity-date\'"><td>activity date</td><td> {{org.value}}</td></tr>
                      <tr ng-repeat="org in node.sub | filter:\'related-activity\'"><td>related activity</td><td> {{org.value}}</td></tr>
                    </table></div></div>
                    <div ng-repeat="node in node.sub | filter:\'transaction\'" class="transaction">
                        <h5>Transaction:</h5>
                        <div class="row-fluid"><div class="span7"><table>
                          <tr ng-repeat="t in node.transactions | filter:\'transaction-date\'"><td class="span5">transaction date</td><td> {{t.attributes[\'iso-date\']}}</td></tr>
                          <tr ng-repeat="t in node.transactions | filter:\'transaction-type\'"><td>transaction type</td><td>{{t.value}}</td></tr>
                          <tr ng-repeat="t in node.transactions | filter:\'disbursement-channel\'"><td>disbursement-channel</td><td> {{t.value}}</td></tr>
                          <tr ng-repeat="t in node.transactions | filter:\'value\'"><td>value</td><td> {{t.value}}</td></tr>
                        </table></div></div>
                    </div>
                  </script>';

    $angular = '<div class="iati">
                  <div class="iati-frame">
                    <div ng-app="myApp" class="app">
                    '.$template.'
                      <div ng-controller="iati">
                        <div class="download">
                          <br>
                          <a class="btn grey" ng-href="{{root+\'download/\'+data.request.uuid}}"><i class="icon-download"></i>Download</a>
                        </div>
                        <div class="spinner" ng-show="loading"></div>

                        <div class="rij"><h3>Contact:</h3></div>
                        <div class="row-fluid"><div class="span7"><table>
                          <tr ng-repeat="(k,v) in info.data | key:\'contact-email\'"><td class="span5">Contact e-mail</td><td>{{v}}</td></tr>
                        </table></div></div>

                        <div class="rij"><h3>Details:</h3></div>

                        <div class="row-fluid"><div class="span7"><table>
                          <tr ng-repeat="(k,v) in info.data | key:\'file_type\'"><td class="span5">File Type</td><td>{{v}}</td></tr>
                          <tr ng-repeat="(k,v) in info.data | key:\'xml:lang\'"><td>language</td><td>{{v}}</td></tr>
                          <tr ng-repeat="(k,v) in info.data | key:\'recipient\'"><td>Recipient Country</td><td>{{v}}</td></tr>
                          <tr ng-repeat="(k,v) in info.data | key:\'publisher-type\'"><td>Publisher type</td><td>{{v}}</td></tr>
                          <tr ng-repeat="(k,v) in info.data | key:\'publisher-org-type\'"><td>Publisher Organization Type </td><td>{{v}}</td></tr>
                          <tr ng-repeat="(k,v) in info.data | key:\'publisher-id\'"><td>Publisher Identifier</td><td>{{v}}</td></tr>
                          <tr ng-repeat="(k,v) in info.data | key:\'publisher-country\'"><td>Publisher Country</td><td>{{v}}</td></tr>
                          <tr ng-repeat="(k,v) in info.data | key:\'verification-status\'"><td>Verified</td><td>{{v}}</td></tr>
                          <tr ng-repeat="(k,v) in info.data | key:\'archive-file\'"><td>Archive File</td><td>{{v}}</td></tr>
                          <tr ng-repeat="(k,v) in info.data | key:\'generated-datetime\'"><td>Data Updated</td><td>{{v}}</td></tr>
                          <tr ng-repeat="(k,v) in info.data | key:\'last-updated-datetime\'"><td>Record Updated</td><td>{{v}}</td></tr>
                          <tr ng-repeat="(k,v) in info.data | key:\'license\'"><td>License</td><td>{{v}}</td></tr>
                        </table></div></div>
                        <!--<<div class="span7">table>
                          <tr ng-repeat="(k,v) in info.data"><td>{{k}}</td><td>{{v}}</td></tr>
                        </table></div>-->
                        <div class="rij"><h3>Iati-Activities:</h3></div>
                        <div id="paging">
                          <div class="spinner" ng-show="actloading">&nbsp;</div>
                          <div id="total">total activities: {{data.total}}</div>
                          <div id="prev" ng-click="gotopage(-1)" ng-class="{\'active\':data.page>1}">&laquo;</div>
                          <div id="page">{{data.page}}/{{data.pages}}</div>
                          <div id="next" ng-click="gotopage(1)" ng-class="{\'active\':data.page<data.pages}">&raquo;</div>
                        </div>
                        <div class="rij activity" ng-repeat="node in node.sub | filter:\'iati-activity\'" ng-include="\'row.html\'"></div>
                        <div id="paging">
                          <div class="spinner" ng-show="actloading"></div>
                          <div id="total">total activities: {{data.total}}</div>
                          <div id="prev" ng-click="gotopage(-1)" ng-class="{\'active\':data.page>1}">&laquo;</div>
                          <div id="page">{{data.page}}/{{data.pages}}</div>
                          <div id="next" ng-click="gotopage(1)" ng-class="{\'active\':data.page<data.pages}">&raquo;</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>';

?>