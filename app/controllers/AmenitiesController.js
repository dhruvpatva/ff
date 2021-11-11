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

FittFinderApp.controller('AmenitiesController', function($scope, $http, $timeout, $location, $state, $stateParams, Upload) {
     $scope.$on('$viewContentLoaded', function() {
          // initialize core components
          Metronic.initAjax();
     });
     $scope.getRecords = function() {
          $http.get('api/module/amenities/getAll').success(function(data) {
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


     $scope.edit_Amenities = function() {
          $scope.error = false;
          var id = $stateParams.id;
          $http({
               url: 'api/module/amenities/getAmenities/',
               method: "POST",
               params: {id: id}
          }).success(function(data) {
               $scope.amenities = data.data;
               $scope.$broadcast('dataloaded');
          });
     };
     $scope.$on('dataloaded', function() {
          $timeout(function() {
               Metronic.initAjax();
          }, 0, false);
     });

     
     $scope.Update_Amenities = function() {
          var id = $stateParams.id;
          $http({
               url: 'api/module/amenities/editAmenities/',
               method: "POST",
               params: $scope.amenities
          }).success(function(resdata) {
               if(resdata.success == 1){
                   $scope.success = true; 
                   $(".show-success").text('');
                   $(".show-success").text(resdata.error_code);
                   $scope.activePath = $location.path('/amenities'); 
               } else {
                    $scope.error_code=resdata.error_code;
                    $scope.error = true;
                    return;
               }
          });
     };
     
     $scope.Add_Amenities = function() {
          $http({
               url: 'api/module/amenities/addAmenities/',
               method: "POST",
               params: $scope.amenities
          }).success(function(resdata) {
               if(resdata.success == 1){
                   $scope.success = true; 
                   $(".show-success").text('');
                   $(".show-success").text(resdata.error_code);
                   $scope.activePath = $location.path('/amenities'); 
               } else {
                    $scope.error_code=resdata.error_code;
                    $scope.error = true;
                    return;
               }
          });
     };
     
     
     $scope.Delete_Amenities = function(data) {
          var deleteAmenities = confirm('Are You Absolutely Sure You Want To Delete?');
          if (deleteAmenities) {
                $http({
                    url: 'api/module/amenities/deleteAmenities',
                    method: "POST",
                    params: {id: data.id}
                }).success(function(resp) {
                    $scope.getRecords();
                });
          }        
      };
});