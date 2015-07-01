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
app.controller('indexlistMenuCtrl',['$scope', 'gridSharedService','$http', '$state', function($scope, gridSharedService, $http, $state){
    //编辑按钮组
    $scope.editGroup = {
        btnNew: true,
        btnEdit: false,
    };
    $scope.btnSelect = " disabled";
    $scope.alerts = [
    ];
    //处理GridCtrl的row选择事件
    $scope.$on('GridRowSelected',function(evt){
        var rows = gridSharedService.selectedRows;
        if( rows.length > 0 && rows.length<2){
            var list = rows[0];
            $scope.editGroup.btnEdit = true;
            $scope.btnSelect = 'btn-info';
        }else{
            $scope.editGroup.btnEdit = false;
            $scope.btnSelect = " disabled";
        }
    });


    $scope.closeAlert = function (index) {
        $scope.alerts.splice(index, 1);
    };
    $scope.opendellist = function(aciton){
        var rows = gridSharedService.selectedRows;
        if(rows.length < 1){
            $scope.alerts.push({msg: '请先选择锁定项'});
            setTimeout(function(){
                $('#alertwarn').css('display','none');
                //$scope.alerts.splice(0, 1);
            }, 2000);
        }else{
            var rowid =[];
            angular.forEach(rows, function(value, key) {
                rowid.push(value.id);
            });

            if(confirm('确定要返息吗？')){
                $http.post('invest/list/return-rate', {id: rowid}).success(function (data) {
                    $state.reload();
                });
            }
        }
    }
    $scope.openlistEditor = function(action){
        var rows = gridSharedService.selectedRows;
        if( rows.length > 0 && rows.length<2){
            var list = rows[0]
        }
        if(list){
            $state.go("app.invest_list_view",{id:list.id});
        }else{
            $state.go("app.member_members_view");
        }
        gridSharedService.openlistEditor();
    }
    //搜索
    $scope.searchText = '';
    $scope.search = function(){
        gridSharedService.search(this.searchText);
    }

}]);

app.controller('indexlistCtrl', ['$scope', '$http','gridSharedService','$state','$filter', function($scope, $http,gridSharedService,$state, $filter) {
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
                $http.get('invest/list/index').success(function (largeLoad) {
                    data = largeLoad.filter(function(item) {

                        return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;

                    });
                    $scope.setPagingData(data,page,pageSize);
                });
            } else {
                $http.get('invest/list/index').success(function (largeLoad) {
                    console.log(largeLoad);
                    data = largeLoad.filter(function(item) {

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
            {field:'invest_title', displayName:'投资产品'},
            {field: 'invest_invest_date', displayName: '期限(月)'},
            {field:'investment_sum', displayName:'投资金额'},
            {field:'interest', displayName:'返息金额'},
            {field:'created_at', displayName:'投标时间'},
            {field:'pay_time', displayName:'付款时间'},
            {field:'interest_status', displayName:'返利状态'},
            {field:'interest_time', displayName:'还息时间'},
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