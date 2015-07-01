'use strict';

/* Controllers */

app.controller('ViewController', ['$scope', '$http', '$state', '$stateParams', function ($scope, $http, $state, $stateParams) {
    $scope.title = '会员详情';
    var id = $stateParams.id;
    $http.post('member/members/view', {id: id}).success(function (data) {

        $scope.data = data;
        $scope.selectstatus = "bg-danger";
        $scope.memothers= data.MemberOther;
        $scope.friends = data.friends;
        var newfriends = [];
        angular.forEach($scope.friends, function(value, key) {
            if(value.status == 10){
                $scope.activestatus = "Suspended";
                value.status = '正常';
            }else{
                $scope.activestatus = "Active";
                value.status = '锁定';
            };
        });
        var d = new Date(parseInt(data.member.created_at) * 1000); //必须乘以1000，否则出现NaN
        var dateStr = d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate();
        data.member.created_at= dateStr;
        if(data.member.status ==10){
            data.member.status="正常";
            $scope.selectstatus = "bg-success";
        }else{
            data.member.status="锁定";
            $scope.selectstatus = "bg-danger";
        }
    });
}]);

