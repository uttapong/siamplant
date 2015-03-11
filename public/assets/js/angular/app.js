'use strict';

var app;
(function() {

// Declare app level module which depends on views, and components
app=angular.module('siamPlant', [
  'mgcrea.ngStrap',
  'ui.router',
  'angular-loading-bar',
  'LocalStorageModule'
]);


app.directive('siamplantHeader',function(){
	return {
		restrict: 'E',
		templateUrl:'assets/js/angular/view/header.html',
		controller: function($scope){
			$scope.test='xxx';
		},
		controllerAs: 'headerCtrl'
	}

});


})();


