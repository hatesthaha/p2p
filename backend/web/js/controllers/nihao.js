'use strict';

/* Controllers */
// signin controller
app.controller('NihaoController', function ($scope, $http, $state) {
    //console.log($http);
    $http.post($state.current.goUrl, {}, {}).success(function (data) {
        $scope.nihao = {"sdf": data};
    });
});