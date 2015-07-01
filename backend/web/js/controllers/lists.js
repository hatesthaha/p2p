'use strict';

app.factory('gridSharedService', ['$rootScope', '$state', function ($rootScope, $http, $state) {
    var shared = {
        filters: []     //顶部筛选器
    };
    shared.rowSelected = function (rows) {
        this.list = rows[0];
        this.selectedRows = rows;
        $rootScope.$broadcast('GridRowSelected');
    };
    shared.openlistEditor = function () {
        $rootScope.$broadcast('OpenlistEditor');
    }
    shared.search = function (text) {
        this.keyWord = text;
        $rootScope.$broadcast('Search');
    };
    shared.state = function (state) {
        $rootScope.$broadcast('state');
    };
    shared.reload = function () {
        $rootScope.$broadcast('GridReload');
    }
    return shared;
}]);
app.controller('ToolBarMoreMenuCtrl', ['$scope', 'gridSharedService', '$http', '$state', function ($scope, gridSharedService, $http, $state) {
    //编辑按钮组
    $scope.editGroup = {
        btnNew: true,
        btnEdit: false
    };
    $scope.alerts = [];
    //处理GridCtrl的row选择事件
    $scope.$on('GridRowSelected', function (evt) {
        var rows = gridSharedService.selectedRows;
        if (rows.length > 0 && rows.length < 2) {
            var list = rows[0];
            $scope.editGroup.btnEdit = true;
        } else {
            $scope.editGroup.btnEdit = false;
        }
    });


    $scope.closeAlert = function (index) {
        $scope.alerts.splice(index, 1);
    };
    $scope.opendellist = function (aciton) {
        var rows = gridSharedService.selectedRows;
        if (rows.length < 1) {
            $scope.alerts.push({msg: '请先选择删除项'});
            setTimeout(function () {
                $('#alertwarn').css('display', 'none');
                //$scope.alerts.splice(0, 1);
            }, 2000);
        } else {
            var rowid = [];
            angular.forEach(rows, function (value, key) {
                rowid.push(value.id);
            });

            if (confirm('确定要删除此文章吗，删除后将无法恢复，请慎重')) {
                $http.post('cms/post/delete', {id: rowid}).success(function (data) {
                    if (data) {
                        alert("删除成功！");
                        $state.reload();
                    } else {
                        alert("删除失败，请联系管理员");
                    }
                });
            }
        }
    };
    $scope.openlistEditor = function (action) {
        var rows = gridSharedService.selectedRows;
        if (rows.length > 0 && rows.length < 2) {
            var list = rows[0]
        }
        if (list) {
            $state.go("app.cms_post_update", {id: list.id});
        } else {
            $state.go("app.cms_post_create");
        }
        gridSharedService.openlistEditor();
    };
    //搜索
    $scope.searchText = '';
    $scope.search = function () {
        gridSharedService.search(this.searchText);
    }

}]);

app.controller('ListsCtrl', ['$scope', '$http', 'gridSharedService', '$state', '$filter', function ($scope, $http, gridSharedService, $state, $filter) {
    $scope.state = function () {
        gridSharedService.state($state);
    }
    $scope.filterOptions = {
        filterText: "",
        useExternalFilter: true
    };
    $scope.totalServerItems = 0;
    $scope.selectedRow = [];
    $scope.pagingOptions = {
        pageSizes: [20, 250, 500, 1000],
        pageSize: 20,
        currentPage: 1
    };
    $scope.setPagingData = function (data, page, pageSize) {
        var pagedData = data.slice((page - 1) * pageSize, page * pageSize);
        $scope.myData = pagedData;
        $scope.totalServerItems = data.length;
        if (!$scope.$$phase) {
            $scope.$apply();
        }
    };
    $scope.getPagedDataAsync = function (pageSize, page, searchText) {
        setTimeout(function () {
            var data;
            if (searchText) {
                var ft = searchText.toLowerCase();
                $http.get('cms/post/index').success(function (largeLoad) {
                    data = largeLoad.model.filter(function (item) {
                        return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                    });
                    $scope.setPagingData(data, page, pageSize);
                });
            } else {
                $http.get('cms/post/index').success(function (largeLoad) {
                    data = largeLoad.model.filter(function (item) {
                        if (item.status == 10) {
                            item.status = "正常";
                        }
                    });
                    $scope.setPagingData(largeLoad.model, page, pageSize);
                });
            }
        }, 100);
    };

    $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage);

    $scope.$watch('pagingOptions', function (newVal, oldVal) {
        if (newVal !== oldVal && newVal.currentPage !== oldVal.currentPage) {
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
        }
    }, true);
    $scope.$watch('filterOptions', function (newVal, oldVal) {
        if (newVal !== oldVal) {
            $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
        }
    }, true);

    $scope.gridOptions = {
        data: 'myData',
        enablePaging: true,
        showFooter: true,
        showSelectionCheckbox: true,
        totalServerItems: 'totalServerItems',
        selectedItems: $scope.selectedRow,
        pagingOptions: $scope.pagingOptions,
        filterOptions: $scope.filterOptions,
        columnDefs: [
            {field: 'id', displayName: 'ID'},
            {field: 'alias', displayName: '别名'},
            {field: 'title', displayName: '标题'},
            {field: 'status', displayName: '状态'},
            {field: 'created_at', displayName: '创建时间'},
            {field: 'updated_at', displayName: '更新时间'}
        ]
    };
    //搜索
    $scope.$on('Search', function () {
        var key = gridSharedService.keyWord;
        $scope.filterOptions.filterText = key;
        $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
    });
    //监视行选择,传播事件
    $scope.$watch('selectedRow', function (data) {
        gridSharedService.rowSelected(data);
    }, true);
}]);