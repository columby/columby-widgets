"use strict";

var app = angular.module('myApp',[]);

app.config(function($routeProvider, $locationProvider) {
	$routeProvider
	.when('/', {templateUrl:"iatitemplate.html",controller:'iati'})
	.otherwise( { redirectTo: '/'});
})

app.run(function($rootScope,$http,$timeout){
	jQuery("#message").hide();
})

app.controller("iati",function($http,$scope){
	
	$scope.node = {};

	if(typeof Drupal!="undefined"){
		$scope.root = Drupal.settings.basePath;
	} else {
		$scope.root = "/"
	}
	$scope.uuid = jQuery("#table-js").attr("uuid");
	$scope.apiurl = $scope.root+"api/v1/data/"+$scope.uuid;

	$http.get($scope.root+"api/v1/cms/"+jQuery("#table-js").attr("uuid")+".json").success(function(data){
		console.log(jQuery("#head h1").text(data.data.title));
	})
	
	$scope.loading = true;
	$scope.actloading = true;

	$http.get($scope.apiurl+".json?type=info").success(function(data){
		$scope.info = data;
		$scope.loading = false;
	}).error(function(){
		console.log("error");
		$scope.loading = false;
	});
		
	$http.get($scope.apiurl+".json?xpath=//iati-activity").success(function(data){
		$scope.data = data;
		$scope.node.sub = data.data;
		$scope.actloading = false;
	}).error(function(){
		console.log("error");
		$scope.actloading = false;
	});

	$scope.gotopage = function(plus){
		var page = parseInt($scope.data.page)+parseInt(plus);
		var last = parseInt($scope.data.pages);
		if((page<1 || page>last) == false){
			$scope.actloading = true;
			//$scope.node.sub = "";
			//$scope.$apply();
			$http.get($scope.apiurl+".json",{params:{xpath:$scope.data.xpath,page:page}}).success(function(data){
				$scope.data = data;
				$scope.node.sub = data.data;
				$scope.actloading = false;
			}).error(function(){
				console.log("error");
				$scope.actloading = false;
			});
		}
	}
})

app.filter('key', function() {
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

app.directive("open",function($http,$timeout){
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
					scope.node.loading = true;
					scope.$apply();
					jQuery('html, body').animate({
					        scrollTop: jQuery(element).offset().top-80
					    }, 500);
					$http.get(scope.apiurl+".json?xpath="+xpath+"&children=true&type=activity").success(function(data){
						//console.log(data.data[0].children);
						scope.node.sub = data.data[0].children;
						scope.node.loading = false;
						$timeout(function(){scope.$apply();},0);
					}).error(function(){
						scope.node.loading = false;
						console.log("error");
					});
				}
			})
		}
	}

})
