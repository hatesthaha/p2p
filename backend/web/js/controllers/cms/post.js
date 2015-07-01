'use strict';

/* Controllers */

app.controller('PostController', ['$scope', '$http', '$state', '$stateParams', function ($scope, $http, $state, $stateParams) {
    $http.get('cms/category/index').success(function (data) {
        $scope.catrgoryItem = data;
        var id = $stateParams.id;
        if (id !== undefined) {
            $http.post('cms/post/view', {id: id}).success(function (data) {
                var category = data.category_id;
                $.each($scope.catrgoryItem.data, function (i, n) {
                    if (category == n.category_id) {
                        category = n;
                    }
                });
                $scope.post = data;
                $scope.post.category_id = category;
                $scope.title = '编辑文章';
            });
        } else {
            $scope.post = {};
            $scope.post.status = '10';
            $scope.title = '添加文章';
        }
    });

    $scope.save = function () {
        var post = $scope.post;
        post.category_id = post.category_id.category_id;
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

app.controller('CategoryController', ['$scope', '$http', '$state', '$stateParams', function ($scope, $http, $state, $stateParams) {
    $http.get('cms/category/index').success(function (data) {
        $scope.catrgoryItem = data;
        console.log($scope.catrgoryItem);
        $scope.catrgoryItem.data.push({category_id: "0", name: "根目录", parent: "0", status: "10"});
        var id = $stateParams.id;
        if (id !== undefined) {
            $http.post('cms/category/view', {id: id}).success(function (data) {
                var category = data.parent;
                $.each($scope.catrgoryItem.data, function (i, n) {
                    if (category == n.category_id) {
                        category = n;
                    }
                });
                $scope.post = data;
                $scope.post.parent = category;
                $scope.title = '编辑栏目';
            });
        } else {
            $scope.post = {};
            $scope.post.status = '10';
            $scope.title = '添加栏目';
        }

    });

    $scope.save = function () {
        var post = $scope.post;
        post.parent = post.parent.category_id;

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