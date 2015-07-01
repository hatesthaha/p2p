'use strict';

/* Controllers */
// signin controller
app.controller('SigninFormController', ['$scope', '$http', '$state', function ($scope, $http, $state) {
    var $currentState = $state.current;
    $http.post($currentState.logoutUrl, {}).error(function () {
        $state.go('app.main');
    });
    $scope.user = {};
    $scope.authError = null;
    $scope.login = function () {
        $scope.authError = null;
        $http.post($currentState.siteUrl, {username: $scope.user.email, password: $scope.user.password})
            .success(function (data) {
                if (angular.isString(data)) {
                    $scope.authError = data;
                } else if (data === true) {
                    $state.go('app.main');
                }
            })
            .error(function () {
                $scope.authError = '服务器错误';
            });
    };
}]);