'use strict';

app.factory('gridSharedService', ['$rootScope', '$state', function ($rootScope, $http, $state) {
    var shared = {
        filters: [],      //顶部筛选器
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
app.controller('ToolBarMoreMenuCtrl', ['$scope', 'gridSharedService', '$http', '$state','$filter', function ($scope, gridSharedService, $http, $state,$filter) {
    $scope.today = function () {
        $scope.dtstart = $filter('date')(new Date(), 'yyyy-MM-dd');
        $scope.dtend = $filter('date')(new Date(), 'yyyy-MM-dd');
    };

    $scope.today();
    $scope.open = function ($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opened = true;
    };
    $scope.opentwo = function ($event) {
        $event.preventDefault();
        $event.stopPropagation();
        $scope.opentwoed = true;
    };
    $scope.dateOptions = {
        formatYear: 'yy',
        startingDay: 1,
        class: 'datepicker'
    };
    $scope.formats = ['dd-MMMM-yyyy', 'yyyy-MM-dd', 'dd.MM.yyyy', 'shortDate'];
    $scope.format = $scope.formats[1];

    $scope.export = function(){
        var datastart = $filter('date')($scope.dtstart, 'yyyy-MM-dd');
        var dataend =$filter('date')($scope.dtend, 'yyyy-MM-dd');

        var url = 'asset/recharge/export?start_time='+datastart+'&end_time='+dataend;
        $scope.export_url = url;
    }





    //搜索
    $scope.searchText = '';
    $scope.search = function () {
        gridSharedService.search(this.searchText);
    }

}]);

app.controller('rechargeindexCtrl', ['$scope', '$http', 'gridSharedService', '$state', '$filter', function ($scope, $http, gridSharedService, $state, $filter) {
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
                $http.get('asset/recharge/index').success(function (largeLoad) {

                    data = largeLoad.filter(function (item) {
                        return JSON.stringify(item).toLowerCase().indexOf(ft) != -1;
                    });
                    $scope.setPagingData(data, page, pageSize);
                });
            } else {
                $http.get('asset/recharge/index').success(function (largeLoad) {
                    data = largeLoad.filter(function (item) {
                    });
                    $scope.setPagingData(largeLoad, page, pageSize);
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
            {field: 'member_id', displayName: '会员id'},
            {field: 'member_status', displayName: '会员状态'},
            {field: 'member_username', displayName: '会员用户名',width:"100px"},
            {field: 'member_idcard_name', displayName: '会员姓名'},
            {field: 'member_idcard_status', displayName: '身份认证状态'},
            {field: 'member_email', displayName: '会员邮箱',width:"150px"},
            {field: 'member_email_status', displayName: '邮箱认证状态'},
            {field: 'member_phone', displayName: '会员电话',width:"100px"},
            {field: 'step', displayName: '金额'},
            {field: 'status', displayName: '金额状态',width:"100px"},
            {field: 'type', displayName: '类型'},
            {field: 'action', displayName: '动作'},
            {field: 'action_uid', displayName: '动作用户Id'},
            {field: 'created_at', displayName: '创建时间',width:"100px"},
            {field: 'member_invitation', displayName: '邀请码'},
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