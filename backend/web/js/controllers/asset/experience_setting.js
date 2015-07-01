'use strict';

/* Controllers */

app.controller('experienceController', ['$scope', '$http', '$state', '$stateParams', function ($scope, $http, $state, $stateParams) {
    $http.get('asset/experience/setting').success(function (data) {
        $scope.experience = angular.fromJson(data);
    });

    $scope.save = function () {
        var experience = $scope.experience;

        $http.post("asset/experience/setting",experience).success(function (data) {
            if (data == true) {
                alert('保存成功');
                $scope.experience = {};
            } else {
                alert('保存成功');
            }
        });
    }
}]);

