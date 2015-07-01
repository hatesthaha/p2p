'use strict';

/* Controllers */

app.controller('roleController', ['$scope', '$http', '$state', '$stateParams', function ($scope, $http, $state, $stateParams) {

    $scope.title = '添加用户';
    $http.post('user/management/view', {id: $stateParams.id}).success(function (data) {
        $scope.post = data.user;
        $scope.roles = data.roles;
        $.each(data.roles, function (i, v) {
            if (data.role.name === v.name) {
                $scope.post.role = v;
            }
        });
        if (data) {
            $scope.title = '编辑角色';
        } else {
            $scope.title = '添加角色';
        }
    });

    $scope.save = function () {
        var post = $scope.post;
        post.role = $scope.post.role.name;
        $http.post('user/management/setting', post).success(function (data) {
            if (data == true) {
                alert('保存成功');
                $state.reload();
            } else if (data == false) {
                alert('保存成功');
                $scope.post = {};
            } else {
                alert(data);
            }
        });
    }
}]);
app.controller('userCreateCtr', ['$scope', '$http', '$state', '$stateParams', function ($scope, $http, $state, $stateParams) {

    $scope.title = '添加用户';
    $scope.save = function () {
        var post = $scope.post;
        $http.post('user/management/create', post).success(function (data) {
            if (data == true) {
                alert('保存成功');
                $scope.post = {};
            } else {
                alert(data);
            }
        });
    }
}]);

