'use strict';

app.factory('gridSharedService',['$rootScope','$state',function($rootScope, $http, $state){
    var shared = {
        filters: [],      //顶部筛选器
    };
    shared.rowSelected = function(rows) {
        this.list = rows[0];
        this.selectedRows = rows;
        $rootScope.$broadcast('GridRowSelected');
    };
    shared.openlistEditor = function(){
        $rootScope.$broadcast('OpenlistEditor');
    }
    shared.search = function(text){
        this.keyWord = text;
        $rootScope.$broadcast('Search');
    };
    shared.state = function(state){
        $rootScope.$broadcast('state');
    };
    shared.reload = function(){
        $rootScope.$broadcast('GridReload');
    }
    return shared;
}]);
app.controller('ToolBarMoreMenuCtrl',['$scope', 'gridSharedService','$http', '$state', function($scope, gridSharedService, $http, $state){
    //搜索
    $scope.searchText = '';
    $scope.search = function(){
        gridSharedService.search(this.searchText);
    }

}]);

app.controller('phoneCtrl', ['$scope', '$http','gridSharedService','$state','$filter', function($scope, $http,gridSharedService,$state, $filter) {
    $scope.state = function(){
        gridSharedService.state($state);
    }
    $scope.filterOptions = {
        filterText: "",
        useExternalFilter: true
    };
    $scope.totalServerItems = 0;
    $scope.selectedRow = [];
    $scope.pagingOptions = {
        pageSizes: [20,250, 500, 1000],
        pageSize: 20,
        currentPage: 1
    };
    $scope.setPagingData = function(data, page, pageSize){
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
                $http.get('member/verification/phone').success(function (largeLoad) {
                    data = largeLoad.filter(function(item) {
                        var d = new Date(parseInt(item.created_at) * 1000); //必须乘以1000，否则出现NaN
                        var dateStr = d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
                        item.created_at= dateStr;
                        if(item.status==10){
                            item.status ="成功";
                        }else{
                            item.status ="失败";
                        }
                        return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;

                    });
                    $scope.setPagingData(data,page,pageSize);
                });
            } else {
                $http.get('member/verification/phone').success(function (largeLoad) {
                    data = largeLoad.filter(function(item) {
                        var d = new Date(parseInt(item.created_at) * 1000); //必须乘以1000，否则出现NaN
                        var dateStr = d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
                        item.created_at= dateStr;
                        if(item.status==10){
                            item.status ="成功";
                        }else{
                            item.status ="失败";
                        }
                    });
                    $scope.setPagingData(largeLoad,page,pageSize);
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
            {field:'id', displayName:'ID'},
            {field:'field', displayName:'电话'},
            {field:'code', displayName:'验证码'},
            {field:'status', displayName:'状态'},
            {field:'created_at', displayName:'创建时间'},
        ]
    };
    //搜索
    $scope.$on('Search',function(){
        var key = gridSharedService.keyWord;
        $scope.filterOptions.filterText = key;
        $scope.getPagedDataAsync($scope.pagingOptions.pageSize, $scope.pagingOptions.currentPage, $scope.filterOptions.filterText);
    });
    //监视行选择,传播事件
    $scope.$watch('selectedRow', function(data){
        gridSharedService.rowSelected(data);
    },true);
}]);