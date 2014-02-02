"use strict";

angular.module('iati_widget',[
  'ngRoute',
  'iati_widget.controllers',
  'iati_widget.services',
  'iati_widget.directives',
  'iati_widget.filters'])

.config(function($routeProvider, $locationProvider, $httpProvider) {
  $httpProvider.defaults.useXDomain = true;
  delete $httpProvider.defaults.headers.common['X-Requested-With'];

  $routeProvider
  .when('/', {templateUrl:"iatitemplate.html",controller:'iatiCtrl'})
  .otherwise( { redirectTo: '/'});
})

.run(function($window, $rootScope){
  var a;
  if(typeof Drupal!="undefined"){
    a = Drupal.settings.basePath;
  } else {
    if($window.location.hostname.match("localhost")){
      a ="/columby/api/";
    } else if(window.location.hostname.match("widgets.columby.local")) {
      a = "http://columby.local/";
    } else {
      a = "/";
    }
  }
  
  $rootScope.apiRoot = a;
  $rootScope.uuid = jQuery(".iati").attr("uuid");

});

angular.module('iati_widget.controllers',[])
.controller('iatiCtrl', ['$http','$scope','$rootScope','cmsService','dataService', function($http,$scope,$rootScope,cmsService,dataService){
  
  $scope.node = {};
  $scope.loading = true;
  $scope.actloading = true;
  $scope.descriptionExpanded = false;

  $scope.toggle = function(){
    if ($scope.descriptionExpanded === false){
      $scope.descriptionExpanded = true;
    } else {
      $scope.descriptionExpanded = false;
    }
  }

  // First get some info about the requested dataset (title, download)
  cmsService.retrieve($rootScope.uuid).then(function(response){
    //console.log('cms data loaded');
    //console.log(response);
    $rootScope.loaded = true;
    if(response.data){
      $rootScope.title = response.data.title;
      $rootScope.download = response.data.file.url;
      $rootScope.download_text = "Download";
      $rootScope.link_to_columby = $rootScope.root+"explore/dataset/"+response.data.nid;
      $rootScope.error = false;
    } else {
      console.log('error, no data');
      $rootScope.error = true;
      $rootScope.error_message = "Something went wrong loading the data.";
    }
  });

  dataService.retrieve($rootScope.uuid, '?type=info').then(function(response){
    //console.log(response);
    $scope.info = response;
    $scope.loading = false;
  });
  

  dataService.retrieve($rootScope.uuid, '?xpath=//iati-activity').then(function(response){
    //console.log('data retrieved');
    //console.log(response);
    $scope.pager = response.pager;
    $scope.data = response.result;
    //console.log($scope.data);
    $scope.node.sub = response.result;
    $scope.actloading = false;
  });

  $scope.gotopage = function(plus){
    
    var page = parseInt($scope.pager.page)+parseInt(plus);
    var last = parseInt($scope.pager.pages);
    
    if((page<1 || page>last) == false){
      $scope.actloading = true;
      console.log(page);
      dataService.retrieve($rootScope.uuid, '?xpath=//iati-activity&page='+page).then(function(response){
        console.log(response.result);
        $scope.pager = response.pager;
        $scope.data = response.result;
        $scope.node.sub = response.result;
        $scope.actloading = false;
      });
    }
  }
}]);



angular.module('iati_widget.services', [])
.factory('cmsService', ['$rootScope','$http', function ($rootScope,$http) {
  return {
    retrieve:function(uuid){
      var url = $rootScope.apiRoot + 'api/v1/cms/' + uuid + ".json?type=info";

      var promise = $http({
        method: 'GET',
        url: url
      })
      .then(function(response){
        return response.data;
      }, function(error){
        console.log(error);
        return error;
      });
      return promise;
    },
  }
}])
.factory('dataService', ['$rootScope','$http',function($rootScope,$http){
  return{
    retrieve:function(uuid,params){
      var url = $rootScope.apiRoot + 'api/v1/data/' + uuid + ".json" + params;

      var promise = $http({
        method: 'GET',
        url: url
      })
      .then(function(response){
        return response.data;
      }, function(error){
        console.log(error)
        return error;
      });
      return promise;
    },
  }
}])
;



angular.module('iati_widget.filters',[])
  .filter('key', function() {
    return function(items, field) {
      var result = {};
      angular.forEach(items, function(value, key) {
        if (key==field) {
          result[key] = value;
        }
      });
      return result;
    };
  });


angular.module('iati_widget.directives',[])
  .directive('toggle', function() {
    return function(scope, elem, attrs) {
        scope.$on('event:toggle', function() {
            elem.slideToggle();
        });
    };
  })

  .directive("open",function($rootScope, $http,$timeout,dataService){
  return {
    link: function(scope,element,attrs){
      element.on("click",function(){
        if(scope.node.open){
          scope.node.open = false;
          scope.$apply();
        } else if(scope.node.sub) {
          scope.node.open = true;
          scope.$apply();
        } else {
          jQuery.each(scope.$parent.$parent.node.sub,function(k,v){
            v.open = false;
          })
          scope.node.open = true;
          var xpath = scope.node.xpath;
          var index = xpath.charAt(xpath.length-3); 
          if (index == '[') {
            index = '';
          }
          index += xpath.charAt(xpath.length-2);
          scope.node.loading = true;
          scope.$apply();
          jQuery('html, body').animate({
                  scrollTop: jQuery(element).offset().top-80
              }, 500);
          console.log(xpath);
          console.log(index);
          dataService.retrieve($rootScope.uuid,'?activity='+index).then(function(response){
            //console.log(response);
            scope.node.sub = response.result;
            scope.node.loading = false;
            $timeout(function(){scope.$apply();},0);
          });
        }
      })
    }
  }
})

