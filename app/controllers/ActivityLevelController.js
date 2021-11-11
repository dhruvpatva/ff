'use strict';
//angular.module('FittFinderApp', ['ngFileUpload']);
FittFinderApp.filter('startFrom', function() {
     return function(input, start) {
          if (input) {
               start = +start; //parse to int
               return input.slice(start);
          }
          return [];
     }
});

FittFinderApp.controller('ActivityLevelController', function($scope, $http, $timeout, $location, $state, $stateParams, Upload) {
     $scope.$on('$viewContentLoaded', function() {
          // initialize core components
          Metronic.initAjax();
     });
     $scope.getRecords = function() {
          $http.get('api/module/activitylevel/getAll').success(function(data) {
               $scope.list = data.data;
               $scope.currentPage = 1; //current page
               $scope.entryLimit = 10; //max no of items to display in a page
               $scope.filteredItems = $scope.list.length; //Initially for no filter  
               $scope.totalItems = $scope.list.length;
          });
          $scope.setPage = function(pageNo) {
               $scope.currentPage = pageNo;
          };
          $scope.filter = function() {
               $timeout(function() {
                    $scope.filteredItems = $scope.filtered.length;
               }, 10);
          };
          $scope.sort_by = function(predicate) {
               $scope.predicate = predicate;
               $scope.reverse = !$scope.reverse;
          };
     }


     $scope.edit_ActivityLevel = function() {
          $scope.error = false;
          var id = $stateParams.id;
          $http({
               url: 'api/module/activitylevel/getActivitydetail/',
               method: "POST",
               params: {id: id}
          }).success(function(data) {
               $scope.activity = data.data;
               console.log($scope.activity); 
               $scope.$broadcast('dataloaded');
          });
     };
     $scope.$on('dataloaded', function() {
          $timeout(function() {
               Metronic.initAjax();
          }, 0, false);
     })

     
     $scope.Update_ActivityLevel = function() {
          var id = $stateParams.id;
          $http({
               url: 'api/module/activitylevel/editActivity/',
               method: "POST",
               params: $scope.activity
          }).success(function(resdata) {
               if(resdata.success == 1){
                   $scope.success = true; 
                   $(".show-success").text('');
                   $(".show-success").text(resdata.error_code);
                   $scope.activePath = $location.path('/activitylevel'); 
               } else {
                    $scope.error_code=resdata.error_code;
                    $scope.error = true;
                    return;
               }
          });
     };
     
     $scope.Add_ActivityLevel = function() {
          $http({
               url: 'api/module/activitylevel/addActivity/',
               method: "POST",
               params: $scope.activity
          }).success(function(resdata) {
               if(resdata.success == 1){
                   $scope.success = true; 
                   $(".show-success").text('');
                   $(".show-success").text(resdata.error_code);
                   $scope.activePath = $location.path('/activitylevel'); 
               } else {
                    $scope.error_code=resdata.error_code;
                    $scope.error = true;
                    return;
               }
          });
     };
     
     
     $scope.Delete_ActivityLevel = function(data) {
          var deleteActivityLevel = confirm('Are You Absolutely Sure You Want To Delete?');
          if (deleteActivityLevel) {
                $http({
                    url: 'api/module/activitylevel/deleteActivity',
                    method: "POST",
                    params: {id: data.id}
                }).success(function(resp) {
                    $scope.getRecords();
                });
          }        
      };
});