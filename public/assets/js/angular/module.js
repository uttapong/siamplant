'use strict';

angular.module('siamplant.authen', ['ui.router','angular-loading-bar','LocalStorageModule']).config(['$routeProvider', 
  function($routeProvider) {
  

  }]).controller('View1Ctrl', [function() {



}]);


angular.module('siamplant-authen').controller('AuthenController', ['$scope', 'ui.router','angular-loading-bar','LocalStorageModule','siamplant.authen'
  function($scope, $stateParams, $location,siamplant.authen) {
    $scope.authentication = Authentication;

    $scope.isAuthen = function() {
      var article = new Articles({
        title: this.title,
        content: this.content
      });
      article.$save(function(response) {
        $location.path('articles/' + response._id);

        $scope.title = '';
        $scope.content = '';
      }, function(errorResponse) {
        $scope.error = errorResponse.data.message;
      });
    };

    $scope.remove = function(article) {
      if (article) {
        article.$remove();

        for (var i in $scope.articles) {
          if ($scope.articles[i] === article) {
            $scope.articles.splice(i, 1);
          }
        }
      } else {
        $scope.article.$remove(function() {
          $location.path('articles');
        });
      }
    };

    $scope.update = function() {
      var article = $scope.article;

      article.$update(function() {
        $location.path('articles/' + article._id);
      }, function(errorResponse) {
        $scope.error = errorResponse.data.message;
      });
    };

    $scope.find = function() {
      $scope.articles = Articles.query();
    };

    $scope.findOne = function() {
      $scope.article = Articles.get({
        articleId: $stateParams.articleId
      });
    };
  }
]);

angular.module('siamplant.authen').factory('Articles', ['$resource',
  function($resource) {
    return $resource('articles/:articleId', {
      articleId: '@_id'
    }, {
      update: {
        method: 'PUT'
      }
    });
  }
]);