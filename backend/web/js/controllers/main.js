'use strict';

/* Controllers */
// signin controller
app.controller('MainController', function ($scope, $http, $state) {
    $http.get($state.current.siteUrl, {}).success(function (data) {
        //console.log(data);
    });
});