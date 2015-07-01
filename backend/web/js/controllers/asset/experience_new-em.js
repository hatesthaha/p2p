'use strict';

/* Controllers */

app.controller('experiencenewemController', ['$scope', '$http', '$state', '$stateParams', function ($scope, $http, $state, $stateParams) {


    $scope.save = function () {
        var newem = $scope.newem;
        $http.post("asset/experience/new-em",newem).success(function (data) {
            if (data == true ) {
                alert("操作成功");
                $scope.newem = {};
            } else {
                alert(data);
            }
        });
    }
}]);

