'use strict';

/* Controllers */

app.controller('ViewController', ['$scope', '$http', '$state', '$stateParams', function ($scope, $http, $state, $stateParams) {
    $scope.title = '投资详情';
    var id = $stateParams.id;
    $http.post('invest/list/view', {id: id}).success(function (data) {
        console.log(data);
        $scope.data = data;
    });
}]);

