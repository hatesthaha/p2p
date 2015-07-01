'use strict';

/* Controllers */

app.controller('ActivecreateController', ['$scope', '$http', '$state', '$stateParams', function ($scope, $http, $state, $stateParams) {


        var id = $stateParams.id;
    console.log(id);
        if (id !== undefined) {
            $http.post('invest/activity/view', {id: id}).success(function (data) {

                $scope.post = data;

                $scope.title = '编辑活动';
            });
        } else {
            $scope.post = {};
            $scope.title = '添加活动';
        }


    $scope.save = function () {

        var post = $scope.post;
        $http.post($state.current.siteUrl, post).success(function (data) {
            if (data == true) {
                alert('保存成功');
                $scope.post = {};
            } else {
                alert(data);
            }
        });
    }
}]);

