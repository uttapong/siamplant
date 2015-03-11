'use strict';

/**
 * @ngdoc overview
 * @name siamPlant
 * @description
 * # siamPlant
 *
 * Main module of the application.
 */
var app=angular
  .module('siamPlant', [
    'ngAnimate',
    'ngCookies',
    'ngResource',
    'ngSanitize',
    'ngTouch',
    'mgcrea.ngStrap',
  'ui.router',
  'angular-loading-bar',
  'LocalStorageModule',
  'authenticate.js'
  ]);

var rooturl='http://localhost/siamplant/public';

app.directive('siamplantHeader',function(){
  return {
    restrict: 'E',
    templateUrl:'views/header.html',
    controller: function($scope){
      $scope.test='xxx';
    },
    controllerAs: 'headerCtrl'
  };
});

app.config(['$stateProvider','$urlRouterProvider',function($stateProvider, $urlRouterProvider) {
  //
  // For any unmatched url, redirect to /state1
  $urlRouterProvider.otherwise('/');
  //
  // Now set up the states
  $stateProvider
    .state('home', {
      url: '/',
      templateUrl: 'views/home.html'
    })
    .state('login', {
      url: '/login',
      templateUrl: 'views/login.html'
    })
    ;

}]);

app.config(['AuthenticateJSProvider', function ($auth) {

    $auth.setConfig({
        host: rooturl+'api/',                  // your base api url
        loginUrl: 'auth/login',        // login api url
        logoutUrl: 'auth/logout',      // logout api url
        loggedinUrl: 'auth/loggedin',  // api to get the user profile and roles

        unauthorizedPage: '/unauthorized',  // url (frontend) of the unauthorized page
        targetPage: '/dashboard',           // url (frontend) of the target page on login success
        loginPage: '/login'                 // url (frontend) of the login page
    });

}]);
