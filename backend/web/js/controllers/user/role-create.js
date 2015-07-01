'use strict';

/* Controllers */

app.controller('roleController', ['$scope', '$http', '$state', '$stateParams', function ($scope, $http, $state, $stateParams) {
    Array.prototype.indexOf = function (val) {
        for (var i = 0; i < this.length; i++) {
            if (this[i] == val) return i;
        }
        return -1;
    };
    Array.prototype.remove = function (val) {
        var index = this.indexOf(val);
        if (index > -1) {
            this.splice(index, 1);
        }
    };

    $scope.title = '添加角色';
    var role_name = $stateParams.role_name;
    var permissions = [];

    $http.get('user/rbac/role-view').success(function (data) {

        $scope.roledata = data.allPerissions;
        $scope.checked = "";

        if (role_name !== undefined) {
            $http.post('user/rbac/role-view', {role_name: role_name}).success(function (user) {

                $scope.post = user.role;
                $scope.post.role_name = user.role.name;
                $scope.title = '编辑角色';

                $.each(user.perissions, function (i, v) {
                    $(".permissions[value='" + v + "']").attr("checked", "");
                    permissions.push(v);
                });


            });
        } else {
            $scope.title = '添加角色';
            $scope.checked = "";
        }

    });

    $scope.toggle = function (select) {
        if (in_array(select, permissions)) {
            permissions.remove(select);
        } else {
            permissions.push(select);
        }
    };

    $scope.save = function () {
        $scope.post.permissions = permissions;
        $http.post('user/rbac/role-setting', $scope.post).success(function (data) {
            $state.reload();
        });
    }
}]);


app.controller('roleCreateCtr', ['$scope', '$http', '$state', '$stateParams', function ($scope, $http, $state, $stateParams) {
    $scope.title = '添加角色';
    $scope.save = function () {
        $http.post('user/rbac/role-create', $scope.post).success(function (data) {
            if (data == true) {
                alert('保存成功');
                $scope.post = {};
            } else {
                alert(data);
            }
        });
    }
}]);

