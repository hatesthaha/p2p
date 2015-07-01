'use strict';

/* Controllers */

app.controller('productcreateController', ['$scope', '$http', '$state', '$stateParams', "$filter", 'FileUploader', function ($scope, $http, $state, $stateParams, $filter, FileUploader) {
    var uploader = $scope.uploader = new FileUploader({
        url: 'site/upload',
    });
    var img = [];
    // FILTERS

    uploader.filters.push({
        name: 'customFilter',
        fn: function (item /*{File|FileLikeObject}*/, options) {

            return this.queue.length < 10;
        }
    });
    // CALLBACKS

    uploader.onWhenAddingFileFailed = function (item /*{File|FileLikeObject}*/, filter, options) {
        //console.info('onWhenAddingFileFailed', item, filter, options);
    };
    uploader.onAfterAddingFile = function (fileItem) {
        //console.info('onAfterAddingFile', fileItem);
    };
    uploader.onAfterAddingAll = function (addedFileItems) {
        //console.info('onAfterAddingAll', addedFileItems);
    };
    uploader.onBeforeUploadItem = function (item) {
        //console.info('onBeforeUploadItem', item);
    };
    uploader.onProgressItem = function (fileItem, progress) {
        //console.info('onProgressItem', fileItem, progress);
    };
    uploader.onProgressAll = function (progress) {
        //console.info('onProgressAll', progress);
    };
    uploader.onSuccessItem = function (fileItem, response, status, headers) {

        //console.info('onSuccessItem', fileItem, response, status, headers);
    };
    uploader.onErrorItem = function (fileItem, response, status, headers) {
        //console.info('onErrorItem', fileItem, response, status, headers);
    };
    uploader.onCancelItem = function (fileItem, response, status, headers) {
        // console.info('onCancelItem', fileItem, response, status, headers);
    };
    uploader.onCompleteItem = function (fileItem, response, status, headers) {
        angular.forEach($scope.uploader.queue, function (value, key) {
            if (response['oldname'] == value.file.name) {
                value.file.newname = response['newname'];
            }
        });

    };
    uploader.onCompleteAll = function () {
        // console.info('onCompleteAll');
    };

    $scope.today = function () {
        $scope.buy_time_start = $filter('date')(new Date(), 'yyyy-MM-dd HH:mm');
        $scope.buy_time_end = $filter('date')(new Date(), 'yyyy-MM-dd HH:mm');
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
    $scope.formats = ['dd-MMMM-yyyy', 'yyyy-MM-dd HH:mm', 'dd.MM.yyyy', 'shortDate'];
    $scope.format = $scope.formats[1];

    $scope.typedata = [
        {name: '体验标', shade: '10'},
        {name: '真实标', shade: '20'},
        {name: '注册专属标', shade: '30'}
    ];

    $scope.imgremove= function(img){
        var index = $scope.imgs.indexOf(img);

        if (index > -1) {
            $scope.imgs.splice(index, 1);
        }

    }
    var id = $stateParams.id;
    if (id !== undefined) {
        $http.post('invest/product/view', {id: id}).success(function (data) {
            var posttype = data.type;
            $.each($scope.typedata, function (i, n) {
                if (posttype == n.shade) {
                    posttype = n;
                }
            });
            var d = new Date(parseInt(data.buy_time_start) * 1000); //必须乘以1000，否则出现NaN
            var dateStr = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate() + " " + d.getHours() + ":" + d.getMinutes();
            var e = new Date(parseInt(data.buy_time_end) * 1000); //必须乘以1000，否则出现NaN
            var dateStrend = e.getFullYear() + '-' + (e.getMonth() + 1) + '-' + e.getDate() + " " + d.getHours() + ":" + d.getMinutes();

            $scope.buy_time_start = dateStr;
            $scope.buy_time_end = dateStrend;
            $scope.post = data;
            $scope.post.type = posttype;


            $scope.imgs =   data.imgs;

            $('#contenttext').html($scope.post.introduce);
            $scope.title = '编辑标';
        });
    } else {
        $scope.post = {};

        $scope.title = '添加标';
    }


    $scope.save = function () {

        img = [];
        if ($scope.uploader.queue.length > 0) {
            angular.forEach($scope.uploader.queue, function (value, key) {
                img.push(value.file.newname);
            });

        } else {
            img = [];
        }
        if($scope.imgs){
            angular.forEach($scope.imgs, function (value, key) {
                img.push(value);
            });
        }



        $scope.post.buy_time_start = $filter('date')($scope.buy_time_start, 'yyyy-MM-dd HH:mm');
        $scope.post.buy_time_end = $filter('date')($scope.buy_time_end, 'yyyy-MM-dd HH:mm');

        $scope.post.img = img;
        $scope.post.introduce = $('#contenttext').html();
        $scope.post.type = $scope.post.type.shade;


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

