'use strict';

FittFinderApp.controller('DashboardController', function($scope, $http, $timeout) {
    $scope.$on('$viewContentLoaded', function() {   
        // initialize core components
        Metronic.initAjax();
    });
});