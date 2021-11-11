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

FittFinderApp.controller('SpeController', function($rootScope,$scope, $http, $timeout, $location, $state, $stateParams,Upload) {
     $scope.$on('$viewContentLoaded', function() {
          // initialize core components
          Metronic.initAjax();
     });
     $scope.getRecords = function() {
          $http.get('api/module/spe/getAllSpe').success(function(data) {
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


     $scope.editSpe = function() {
          $scope.error = false;
          var id = $stateParams.id;
          $http({
               url: 'api/module/spe/getSpedetail/',
               method: "POST",
               params: {speid: id}
          }).success(function(data) {
               if (data.error == 1) {
                    $scope.error_code = data.error_code;
                    $scope.error = true;
                    return;
               } else {
                    $scope.spe = data.data;
                    $scope.timezones = {
                         availableOptions: $rootScope.timezoneoptions,
                         selectedOption: {id: data.data.timezone}
                    };
                    $http.get('api/module/common/getamenities').success(function(amenitiesdata) {
                         $scope.amenities = {
                              availableOptions: amenitiesdata.data,
                              selectedOption: data.data.amenities
                         };
                         $scope.$broadcast('dataloaded');
                         $scope.oldamenities = data.data.amenities;
                    });
               }
          });
     };
     $scope.$on('dataloaded', function() {
          $timeout(function() {
               Metronic.initAjax();
               $('#timezone').select2({
                    placeholder: "Select a Timezone",
                    allowClear: true
               });
               $('#amenities').select2({
                    placeholder: "Select a Amenities",
                    allowClear: true
               });

          }, 0, false);
     });


     $scope.Update_Spe = function() {
          var id = $stateParams.id;
          /*$scope.selectedamenities = [];
          $scope.spe.amenities = $scope.amenities.selectedOption;
          $scope.spe.timezone = $scope.timezones.selectedOption.id;
          $scope.spe.oldamenities = $scope.oldamenities;*/
          var form_data = new FormData();
          $scope.spe.amenities = $scope.amenities.selectedOption;
          $scope.spe.timezone = $scope.timezones.selectedOption.id;
          var oldamenities = [];
          angular.forEach($scope.oldamenities, function(value, key) {
                oldamenities.push(value.id);
          });
          form_data.append("oldamenities", oldamenities);
          for ( var key in $scope.spe ) {
              if(key == "amenities"){
                   var amenities = [];
                   angular.forEach($scope.spe.amenities, function(value, key) {
                         amenities.push(value.id);
                   });
                   form_data.append("amenities", amenities);
              } else {
                   form_data.append(key, $scope.spe[key]);
              }
          }
          $http({
               url: 'api/module/spe/editSpe/',
               method: "POST",
               transformRequest: angular.identity,
               headers: {'Content-Type': undefined,'Process-Data': false},
               data: form_data
          }).success(function(resdata) {
               if (resdata.success == 1) {
                    $scope.success = true;
                    $(".show-success").text('');
                    $(".show-success").text(resdata.error_code);
                    $scope.activePath = $location.path('/allspe');
               } else {
                    $scope.error_code = resdata.error_code;
                    $scope.error = true;
                    return;
               }
          });
     };
     $scope.onFileSelect = function(file) {
          var id = $stateParams.id;
          $(".profile_error").text('');
          if (!file) {
               $(".profile_error").text('* File Type Invalid');
               return
          }
          ;
          Upload.upload({
               url: 'api/module/spe/setSpeLogo',
               data: {logo: file, speid: id}
          }).then(function(resp) {
               $scope.editSpe();
          });
     };

     $scope.Delete_Spe = function(spe) {
          var deleteUser = confirm('Are You Absolutely Sure You Want To Delete?');
          if (deleteUser) {
               $http({
                    url: 'api/module/spe/deleteSpe',
                    method: "POST",
                    params: {speid: spe.id}
               }).success(function(resp) {
                    $scope.getRecords();
               });
          }
     };
     $scope.Add_init = function() {
          $scope.timezones = {
               availableOptions: [
                    {id: 'Pacific/Midway', name: "(GMT-11:00) Midway Island"},
                    {id: 'US/Samoa', name: "(GMT-11:00) Samoa"},
                    {id: 'US/Hawaii', name: "(GMT-10:00) Hawaii"},
                    {id: 'US/Alaska', name: "(GMT-09:00) Alaska"},
                    {id: 'US/Pacific', name: "(GMT-08:00) Pacific Time (US &amp; Canada)"},
                    {id: 'America/Tijuana', name: "(GMT-08:00) Tijuana"},
                    {id: 'US/Arizona', name: "(GMT-07:00) Arizona"},
                    {id: 'US/Mountain', name: "(GMT-07:00) Mountain Time (US &amp; Canada)"},
                    {id: 'America/Chihuahua', name: "(GMT-07:00) Chihuahua"},
                    {id: 'America/Mazatlan', name: "(GMT-07:00) Mazatlan"},
                    {id: 'America/Mexico_City', name: "(GMT-06:00) Mexico City"},
                    {id: 'America/Monterrey', name: "(GMT-06:00) Monterrey"},
                    {id: 'Canada/Saskatchewan', name: "(GMT-06:00) Saskatchewan"},
                    {id: 'US/Central', name: "(GMT-06:00) Central Time (US &amp; Canada)"},
                    {id: 'US/Eastern', name: "(GMT-05:00) Eastern Time (US &amp; Canada)"},
                    {id: 'US/East-Indiana', name: "(GMT-05:00) Indiana (East)"},
                    {id: 'America/Bogota', name: "(GMT-05:00) Bogota"},
                    {id: 'America/Lima', name: "(GMT-05:00) Lima"},
                    {id: 'America/Caracas', name: "(GMT-04:30) Caracas"},
                    {id: 'Canada/Atlantic', name: "(GMT-04:00) Atlantic Time (Canada)"},
                    {id: 'America/La_Paz', name: "(GMT-04:00) La Paz"},
                    {id: 'America/Santiago', name: "(GMT-04:00) Santiago"},
                    {id: 'Canada/Newfoundland', name: "(GMT-03:30) Newfoundland"},
                    {id: 'America/Buenos_Aires', name: "(GMT-03:00) Buenos Aires"},
                    {id: 'Greenland', name: "(GMT-03:00) Greenland"},
                    {id: 'Atlantic/Stanley', name: "(GMT-02:00) Stanley"},
                    {id: 'Atlantic/Azores', name: "(GMT-01:00) Azores"},
                    {id: 'Atlantic/Cape_Verde', name: "(GMT-01:00) Cape Verde Is."},
                    {id: 'Africa/Casablanca', name: "(GMT) Casablanca"},
                    {id: 'Europe/Dublin', name: "(GMT) Dublin"},
                    {id: 'Europe/Lisbon', name: "(GMT) Lisbon"},
                    {id: 'Europe/London', name: "(GMT) London"},
                    {id: 'Africa/Monrovia', name: "(GMT) Monrovia"},
                    {id: 'Europe/Amsterdam', name: "(GMT+01:00) Amsterdam"},
                    {id: 'Europe/Belgrade', name: "(GMT+01:00) Belgrade"},
                    {id: 'Europe/Berlin', name: "(GMT+01:00) Berlin"},
                    {id: 'Europe/Bratislava', name: "(GMT+01:00) Bratislava"},
                    {id: 'Europe/Brussels', name: "(GMT+01:00) Brussels"},
                    {id: 'Europe/Budapest', name: "(GMT+01:00) Budapest"},
                    {id: 'Europe/Copenhagen', name: "(GMT+01:00) Copenhagen"},
                    {id: 'Europe/Ljubljana', name: "(GMT+01:00) Ljubljana"},
                    {id: 'Europe/Madrid', name: "(GMT+01:00) Madrid"},
                    {id: 'Europe/Paris', name: "(GMT+01:00) Paris"},
                    {id: 'Europe/Prague', name: "(GMT+01:00) Prague"},
                    {id: 'Europe/Rome', name: "(GMT+01:00) Rome"},
                    {id: 'Europe/Sarajevo', name: "(GMT+01:00) Sarajevo"},
                    {id: 'Europe/Skopje', name: "(GMT+01:00) Skopje"},
                    {id: 'Europe/Stockholm', name: "(GMT+01:00) Stockholm"},
                    {id: 'Europe/Vienna', name: "(GMT+01:00) Vienna"},
                    {id: 'Europe/Warsaw', name: "(GMT+01:00) Warsaw"},
                    {id: 'Europe/Zagreb', name: "(GMT+01:00) Zagreb"},
                    {id: 'Europe/Athens', name: "(GMT+02:00) Athens"},
                    {id: 'Europe/Bucharest', name: "(GMT+02:00) Bucharest"},
                    {id: 'Africa/Cairo', name: "(GMT+02:00) Cairo"},
                    {id: 'Africa/Harare', name: "(GMT+02:00) Harare"},
                    {id: 'Europe/Helsinki', name: "(GMT+02:00) Helsinki"},
                    {id: 'Europe/Istanbul', name: "(GMT+02:00) Istanbul"},
                    {id: 'Asia/Jerusalem', name: "(GMT+02:00) Jerusalem"},
                    {id: 'Europe/Kiev', name: "(GMT+02:00) Kyiv"},
                    {id: 'Europe/Minsk', name: "(GMT+02:00) Minsk"},
                    {id: 'Europe/Riga', name: "(GMT+02:00) Riga"},
                    {id: 'Europe/Sofia', name: "(GMT+02:00) Sofia"},
                    {id: 'Europe/Tallinn', name: "(GMT+02:00) Tallinn"},
                    {id: 'Europe/Vilnius', name: "(GMT+02:00) Vilnius"},
                    {id: 'Asia/Baghdad', name: "(GMT+03:00) Baghdad"},
                    {id: 'Asia/Kuwait', name: "(GMT+03:00) Kuwait"},
                    {id: 'Africa/Nairobi', name: "(GMT+03:00) Nairobi"},
                    {id: 'Asia/Riyadh', name: "(GMT+03:00) Riyadh"},
                    {id: 'Europe/Moscow', name: "(GMT+03:00) Moscow"},
                    {id: 'Asia/Tehran', name: "(GMT+03:30) Tehran"},
                    {id: 'Asia/Baku', name: "(GMT+04:00) Baku"},
                    {id: 'Europe/Volgograd', name: "(GMT+04:00) Volgograd"},
                    {id: 'Asia/Muscat', name: "(GMT+04:00) Muscat"},
                    {id: 'Asia/Tbilisi', name: "(GMT+04:00) Tbilisi"},
                    {id: 'Asia/Yerevan', name: "(GMT+04:00) Yerevan"},
                    {id: 'Asia/Kabul', name: "(GMT+04:30) Kabul"},
                    {id: 'Asia/Karachi', name: "(GMT+05:00) Karachi"},
                    {id: 'Asia/Tashkent', name: "(GMT+05:00) Tashkent"},
                    {id: 'Asia/Kolkata', name: "(GMT+05:30) Kolkata"},
                    {id: 'Asia/Kathmandu', name: "(GMT+05:45) Kathmandu"},
                    {id: 'Asia/Yekaterinburg', name: "(GMT+06:00) Ekaterinburg"},
                    {id: 'Asia/Almaty', name: "(GMT+06:00) Almaty"},
                    {id: 'Asia/Dhaka', name: "(GMT+06:00) Dhaka"},
                    {id: 'Asia/Novosibirsk', name: "(GMT+07:00) Novosibirsk"},
                    {id: 'Asia/Bangkok', name: "(GMT+07:00) Bangkok"},
                    {id: 'Asia/Jakarta', name: "(GMT+07:00) Jakarta"},
                    {id: 'Asia/Krasnoyarsk', name: "(GMT+08:00) Krasnoyarsk"},
                    {id: 'Asia/Chongqing', name: "(GMT+08:00) Chongqing"},
                    {id: 'Asia/Hong_Kong', name: "(GMT+08:00) Hong Kong"},
                    {id: 'Asia/Kuala_Lumpur', name: "(GMT+08:00) Kuala Lumpur"},
                    {id: 'Australia/Perth', name: "(GMT+08:00) Perth"},
                    {id: 'Asia/Singapore', name: "(GMT+08:00) Singapore"},
                    {id: 'Asia/Taipei', name: "(GMT+08:00) Taipei"},
                    {id: 'Asia/Ulaanbaatar', name: "(GMT+08:00) Ulaan Bataar"},
                    {id: 'Asia/Urumqi', name: "(GMT+08:00) Urumqi"},
                    {id: 'Asia/Irkutsk', name: "(GMT+09:00) Irkutsk"},
                    {id: 'Asia/Seoul', name: "(GMT+09:00) Seoul"},
                    {id: 'Asia/Tokyo', name: "(GMT+09:00) Tokyo"},
                    {id: 'Australia/Adelaide', name: "(GMT+09:30) Adelaide"},
                    {id: 'Australia/Darwin', name: "(GMT+09:30) Darwin"},
                    {id: 'Asia/Yakutsk', name: "(GMT+10:00) Yakutsk"},
                    {id: 'Australia/Brisbane', name: "(GMT+10:00) Brisbane"},
                    {id: 'Australia/Canberra', name: "(GMT+10:00) Canberra"},
                    {id: 'Pacific/Guam', name: "(GMT+10:00) Guam"},
                    {id: 'Australia/Hobart', name: "(GMT+10:00) Hobart"},
                    {id: 'Australia/Melbourne', name: "(GMT+10:00) Melbourne"},
                    {id: 'Pacific/Port_Moresby', name: "(GMT+10:00) Port Moresby"},
                    {id: 'Australia/Sydney', name: "(GMT+10:00) Sydney"},
                    {id: 'Asia/Vladivostok', name: "(GMT+11:00) Vladivostok"},
                    {id: 'Asia/Magadan', name: "(GMT+12:00) Magadan"},
                    {id: 'Pacific/Auckland', name: "(GMT+12:00) Auckland"},
                    {id: 'Pacific/Fiji', name: "(GMT+12:00) Fiji"},
               ]
          };
          $http.get('api/module/common/getamenities').success(function(amenitiesdata) {
               $scope.amenities = {
                    availableOptions: amenitiesdata.data,
               };
               $scope.$broadcast('dataloaded');
          });
     };
     $scope.Add_Spe = function() {
          var form_data = new FormData();
          $scope.spe.amenities = $scope.amenities.selectedOption;
          $scope.spe.timezone = $scope.timezones.selectedOption.id;
          for ( var key in $scope.spe ) {
              if(key == "amenities"){
                   var amenities = [];
                   angular.forEach($scope.spe.amenities, function(value, key) {
                         amenities.push(value.id);
                   });
                   form_data.append("amenities", amenities);
              } else {
                   form_data.append(key, $scope.spe[key]);
              }
          }
          $http({
               url: 'api/module/spe/AddSpe/',
               method: "POST",
               transformRequest: angular.identity,
               headers: {'Content-Type': undefined,'Process-Data': false},
               data: form_data
          }).success(function(resdata) {
               if (resdata.success == 1) {
                    $scope.success = true;
                    $(".show-success").text('');
                    $(".show-success").text(resdata.error_code);
                    $scope.activePath = $location.path('/allspe');
               } else {
                    $scope.error_code = resdata.error_code;
                    $scope.error = true;
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    return;
               }
          });
     };
});

