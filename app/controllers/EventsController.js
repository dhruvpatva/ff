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

FittFinderApp.controller('EventsController', function($rootScope, $scope, $http, $timeout, $location, $state, $stateParams, Upload) {
     $scope.$on('$viewContentLoaded', function() {
          // initialize core components
          Metronic.initAjax();
     });
     $scope.getEventCategories = function() {
          $http.get('api/module/events/getAllEventCategory').success(function(data) {
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

     $scope.Add_EventCategory = function() {
          $http({
               url: 'api/module/events/addEventCategory/',
               method: "POST",
               params: $scope.category
          }).success(function(resdata) {
               if (resdata.success == 1) {
                    $scope.success = true;
                    $(".show-success").text('');
                    $(".show-success").text(resdata.error_code);
                    $scope.activePath = $location.path('/events/eventcategories');
               } else {
                    $scope.error_code = resdata.error_code;
                    $scope.error = true;
                    return;
               }
          });
     };

     $scope.edit_EventCategory = function() {
          $scope.error = false;
          var id = $stateParams.id;
          $http({
               url: 'api/module/events/getEventCategory/',
               method: "POST",
               params: {id: id}
          }).success(function(data) {
               $scope.category = data.data;
               $scope.$broadcast('dataloaded');
          });
     };

     $scope.$on('dataloaded', function() {
          $timeout(function() {
               Metronic.initAjax();
          }, 0, false);
     });

     $scope.Update_EventCategory = function() {
          var id = $stateParams.id;
          $http({
               url: 'api/module/events/editEventCategory/',
               method: "POST",
               params: $scope.category
          }).success(function(resdata) {
               if (resdata.success == 1) {
                    $scope.success = true;
                    $(".show-success").text('');
                    $(".show-success").text(resdata.error_code);
                    $scope.activePath = $location.path('/events/eventcategories');
               } else {
                    $scope.error_code = resdata.error_code;
                    $scope.error = true;
                    return;
               }
          });
     };

     $scope.Delete_EventCategory = function(data) {
          var index = $scope.list.map(function(category) {
               return category.id;
          }).indexOf(data.id);

          var deleteEventCategory = confirm('Are You Absolutely Sure You Want To Delete?');
          if (deleteEventCategory) {
               $http({
                    url: 'api/module/events/deleteEventCategory',
                    method: "POST",
                    params: {id: data.id}
               }).success(function(resp) {
                    $scope.list.splice(index, 1);
               });
          }
     };

     $scope.addEventLoadData = function() {
          $scope.timezones = {
               availableOptions: $rootScope.timezoneoptions,
               selectedOption: {id: 'US/Mountain'}
          };
          $http.get('api/module/common/geteventcategories').success(function(categoriesdata) {
                if (categoriesdata.data.length !== 0){ 
                    $scope.eventcategories = {
                         availableOptions: categoriesdata.data,
                         selectedOption: { id: categoriesdata.data[0].id }
                    };
                 }
          });
          
          $scope.event = {
               event_timezone: 'US/Mountain',
               event_category_id: '0',
               signup_more_time: '1',
               event_discount_type: '0',
               event_difficulty: '1',
               selectedspe: undefined,
               status: '1'
          };
          $timeout(function() {
               Metronic.initAjax();
               $('#timezone').select2({
                    placeholder: "Select a Timezone",
                    allowClear: true
               });
               $('#category').select2({
                    placeholder: "Select a Category",
                    allowClear: true
               });
               $("#event_start_time").datetimepicker({
                    isRTL: false,
                    format: "mm-dd-yyyy HH:ii P",
                    autoclose: true,
                    todayBtn: true,
                    showMeridian: true,
                    viewSelect: "decade",
                    startDate: new Date(),
                    pickerPosition: "bottom-left"
               }).on('hide', function(selected) {
                    if(selected.date !== null){
                         var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                         $('#event_end_time').datetimepicker('setStartDate', fromDate);
                    }
               });
               $("#event_end_time").datetimepicker({
                    isRTL: false,
                    format: "mm-dd-yyyy HH:ii P",
                    autoclose: true,
                    todayBtn: true,
                    showMeridian: true,
                    viewSelect: "decade",
                    startDate: new Date(),
                    pickerPosition: "bottom-left"
               }).on('hide', function(selected) {
                    if(selected.date !== null){
                         var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                         $('#event_start_time').datetimepicker('setStartDate', new Date());
                         $('#event_start_time').datetimepicker('setEndDate', fromDate);
                    }
               });
               $("#event_offer_start").datetimepicker({
                    isRTL: false,
                    format: "mm-dd-yyyy HH:ii P",
                    autoclose: true,
                    todayBtn: true,
                    showMeridian: true,
                    viewSelect: "decade",
                    startDate: new Date(),
                    pickerPosition: "bottom-left"
               }).on('hide', function(selected) {
                    if(selected.date !== null){
                         var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                         $('#event_offer_end').datetimepicker('setStartDate', fromDate);
                    }
               });
               $("#event_offer_end").datetimepicker({
                    isRTL: false,
                    format: "mm-dd-yyyy HH:ii P",
                    autoclose: true,
                    todayBtn: true,
                    showMeridian: true,
                    viewSelect: "decade",
                    startDate: new Date(),
                    pickerPosition: "bottom-left"
               }).on('hide', function(selected) {
                    if(selected.date !== null){
                         var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                         $('#event_offer_start').datetimepicker('setStartDate', new Date());
                         $('#event_offer_start').datetimepicker('setEndDate', fromDate);
                    }
               });
          }, 0, false);
     }
     
     $scope.getEvents = function() {
          $http.get('api/module/events/getAllEvents').success(function(data) {
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
     
     $scope.getSpe = function(q) {
          if(q !== ""){
               return $http.jsonp("api/module/common/searchSpe?callback=JSON_CALLBACK&q="+q).then(function(response){
                    return response.data.data;
               });
          }
     }

     $scope.Add_Event = function() {
          var form_data = new FormData();
          for ( var key in $scope.event ) {
              form_data.append(key, $scope.event[key]);
          }
          form_data.append('event_timezone', $scope.timezones.selectedOption.id);
          form_data.append('event_category_id', $scope.eventcategories.selectedOption.id);
          form_data.append('spe_id', $scope.event.selectedspe.id);
          $http({
               url: 'api/module/events/addEvent/',
               method: "POST",
               transformRequest: angular.identity,
               headers: {'Content-Type': undefined,'Process-Data': false},
               data: form_data
          }).success(function(resdata) {
               if (resdata.success == 1) {
                    $scope.success = true;
                    $(".show-success").text('');
                    $(".show-success").text(resdata.error_code);
                    $scope.activePath = $location.path('/events');
               } else {
                    $scope.error_code = resdata.error_code;
                    $scope.error = true;
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    return;
               }
          });
     };
     
     $scope.edit_Event = function() {
          $scope.error = false;
          $scope.timezones = {
               availableOptions: $rootScope.timezoneoptions,
               selectedOption: {id: 'US/Mountain'}
          };
          $http.get('api/module/common/geteventcategories').success(function(categoriesdata) {
               $scope.eventcategories = {
                    availableOptions: categoriesdata.data,
                    selectedOption: { id: '0' }
               };
          });
          var id = $stateParams.id;
          $http({
               url: 'api/module/events/getEvent/',
               method: "POST",
               params: {id: id}
          }).success(function(data) {
               $scope.event = data.data;
               $scope.timezones.selectedOption = { id: $scope.event.event_timezone };
               $scope.eventcategories.selectedOption = { id: $scope.event.event_category_id };
               $scope.$broadcast('dataeventloaded');
          });
     };
     
     $scope.$on('dataeventloaded', function() {
          $timeout(function() {
               Metronic.initAjax();
               $('#timezone').select2({
                    placeholder: "Select a Timezone",
                    allowClear: true
               });
               $('#category').select2({
                    placeholder: "Select a Timezone",
                    allowClear: true
               });
               $("#event_start_time").datetimepicker({
                    isRTL: false,
                    format: "mm-dd-yyyy HH:ii P",
                    autoclose: true,
                    todayBtn: true,
                    showMeridian: true,
                    viewSelect: "decade",
                    startDate: new Date(),
                    pickerPosition: "bottom-left"
               }).on('hide', function(selected) {
                    if(selected.date !== null){
                         var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                         $('#event_end_time').datetimepicker('setStartDate', fromDate);
                    }
               });
               $("#event_end_time").datetimepicker({
                    isRTL: false,
                    format: "mm-dd-yyyy HH:ii P",
                    autoclose: true,
                    todayBtn: true,
                    showMeridian: true,
                    viewSelect: "decade",
                    startDate: new Date(),
                    pickerPosition: "bottom-left"
               }).on('hide', function(selected) {
                    if(selected.date !== null){
                         var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                         $('#event_start_time').datetimepicker('setStartDate', new Date());
                         $('#event_start_time').datetimepicker('setEndDate', fromDate);
                    }
               });
               $("#event_offer_start").datetimepicker({
                    isRTL: false,
                    format: "mm-dd-yyyy HH:ii P",
                    autoclose: true,
                    todayBtn: true,
                    showMeridian: true,
                    viewSelect: "decade",
                    startDate: new Date(),
                    pickerPosition: "bottom-left"
               }).on('hide', function(selected) {
                    if(selected.date !== null){
                         var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                         $('#event_offer_end').datetimepicker('setStartDate', fromDate);
                    }
               });
               $("#event_offer_end").datetimepicker({
                    isRTL: false,
                    format: "mm-dd-yyyy HH:ii P",
                    autoclose: true,
                    todayBtn: true,
                    showMeridian: true,
                    viewSelect: "decade",
                    startDate: new Date(),
                    pickerPosition: "bottom-left"
               }).on('hide', function(selected) {
                    if(selected.date !== null){
                         var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                         $('#event_offer_start').datetimepicker('setStartDate', new Date());
                         $('#event_offer_start').datetimepicker('setEndDate', fromDate);
                    }
               });
          }, 0, false);
     });
     
     $scope.Update_Event = function() {
          var form_data = new FormData();
          for ( var key in $scope.event ) {
              form_data.append(key, $scope.event[key]);
          }
          form_data.append('event_timezone', $scope.timezones.selectedOption.id);
          form_data.append('event_category_id', $scope.eventcategories.selectedOption.id);
          form_data.append('spe_id', $scope.event.selectedspe.id);
          $http({
               url: 'api/module/events/editEvent/',
               method: "POST",
               transformRequest: angular.identity,
               headers: {'Content-Type': undefined,'Process-Data': false},
               data: form_data
          }).success(function(resdata) {
               if (resdata.success == 1) {
                    $scope.success = true;
                    $(".show-success").text('');
                    $(".show-success").text(resdata.error_code);
                    $scope.activePath = $location.path('/events');
               } else {
                    $scope.error_code = resdata.error_code;
                    $scope.error = true;
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    return;
               }
          });
     };
     
     $scope.Delete_Event = function(data) {
          var index = $scope.list.map(function(event) {
               return event.id;
          }).indexOf(data.id);

          var deleteEvent = confirm('Are You Absolutely Sure You Want To Delete?');
          if (deleteEvent) {
               $http({
                    url: 'api/module/events/deleteEvent',
                    method: "POST",
                    params: {id: data.id}
               }).success(function(resp) {
                    $scope.list.splice(index, 1);
               });
          }
     };
     
     $scope.getEventAttendees = function() {
          var id = $stateParams.id;
          $scope.event = {
               id: id
          };
          $http({
               url: 'api/module/events/getAllEventAttendees',
               method: "POST",
               params: {id: id}
          }).success(function(data) {
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
     
     $scope.getUsers = function(q) {
          if(q !== ""){
               return $http.jsonp("api/module/common/searchUser?callback=JSON_CALLBACK&q="+q).then(function(response){
                    return response.data.data;
               });
          }
     }
     
     $scope.getAdd_EventAttendee = function() {
          var event_id = $stateParams.id;
          $scope.eventattendee = {
               event_id: event_id,
               status: '1'
          };
          $scope.eventattendee.selected = undefined;
          $timeout(function() {
               Metronic.initAjax();
               $("#registration_date").datetimepicker({
                    isRTL: false,
                    format: "mm-dd-yyyy HH:ii P",
                    autoclose: true,
                    todayBtn: true,
                    showMeridian: true,
                    viewSelect: "decade",
                    startDate: new Date(),
                    pickerPosition: "bottom-left"
               });
          }, 0, false);
     }
     
     $scope.Add_EventAttendee = function() {
          var form_data = new FormData();
          for ( var key in $scope.eventattendee ) {
              form_data.append(key, $scope.eventattendee[key]);
          }
          form_data.append('user_id', $scope.eventattendee.selected.id);
          $http({
               url: 'api/module/events/addEventAttendee/',
               method: "POST",
               transformRequest: angular.identity,
               headers: {'Content-Type': undefined,'Process-Data': false},
               data: form_data
          }).success(function(resdata) {
               if (resdata.success == 1) {
                    $scope.success = true;
                    $(".show-success").text('');
                    $(".show-success").text(resdata.error_code);
                    $scope.activePath = $location.path('/events/eventattendees/'+$scope.eventattendee.event_id);
               } else {
                    $scope.error_code = resdata.error_code;
                    $scope.error = true;
                    $("html, body").animate({ scrollTop: 0 }, "slow");
                    return;
               }
          });
     };
     
     $scope.Delete_EventAttendee = function(data) {
          var index = $scope.list.map(function(event) {
               return event.id;
          }).indexOf(data.id);

          var deleteEvent = confirm('Are You Absolutely Sure You Want To Delete?');
          if (deleteEvent) {
               $http({
                    url: 'api/module/events/deleteEventAttendee',
                    method: "POST",
                    params: {id: data.id}
               }).success(function(resp) {
                    $scope.list.splice(index, 1);
               });
          }
     };
});