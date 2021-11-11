'use strict';
FittFinderApp.filter('startFrom', function() {
    return function(input, start) {
        if (input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});

FittFinderApp.controller('ClassesController', function($rootScope, $scope, $http, $timeout, $location, $state, $stateParams, Upload) {
    $scope.$on('$viewContentLoaded', function() {
        // initialize core components
        //Metronic.initAjax();
    });
    $scope.getClassesCategories = function() {
        $http.get('api/module/classes/getAllClassesCategory').success(function(data) {
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

    $scope.Add_ClassesCategory = function() {
        $http({
            url: 'api/module/classes/addClassesCategory/',
            method: "POST",
            params: $scope.category
        }).success(function(resdata) {
            if (resdata.success == 1) {
                $scope.success = true;
                $(".show-success").text('');
                $(".show-success").text(resdata.error_code);
                $scope.activePath = $location.path('/classescategories');
            } else {
                $scope.error_code = resdata.error_code;
                $scope.error = true;
                return;
            }
        });
    };
    
    $scope.addCategoryLoadData = function() {
        $scope.category = {
            status: '1'
        };
        $scope.$broadcast('dataloaded');
    };

    $scope.edit_ClassesCategory = function() {
        $scope.error = false;
        var id = $stateParams.id;
        $http({
            url: 'api/module/classes/getClassesCategory/',
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

    $scope.Update_ClassesCategory = function() {
        var id = $stateParams.id;
        $http({
            url: 'api/module/classes/editClassesCategory/',
            method: "POST",
            params: $scope.category
        }).success(function(resdata) {
            if (resdata.success == 1) {
                $scope.success = true;
                $(".show-success").text('');
                $(".show-success").text(resdata.error_code);
                $scope.activePath = $location.path('/classescategories');
            } else {
                $scope.error_code = resdata.error_code;
                $scope.error = true;
                return;
            }
        });
    };

    $scope.Delete_ClassesCategory = function(data) {
        var index = $scope.list.map(function(category) {
            return category.id;
        }).indexOf(data.id);
        
        var deleteEventCategory = confirm('Are You Absolutely Sure You Want To Delete?');
        if (deleteEventCategory) {
            $http({
                url: 'api/module/classes/deleteClassesCategory',
                method: "POST",
                params: {id: data.id}
            }).success(function(resp) {
                $scope.list.splice(index, 1);
            });
        }
    };

    $scope.getSpe = function(q) {
        if (q !== "") {
            return $http.jsonp("api/module/common/searchSpe?callback=JSON_CALLBACK&q=" + q).then(function(response) {
                return response.data.data;
            });
        }
    }

    $scope.addClassLoadData = function() {
        $scope.timezones = {
            availableOptions: $rootScope.timezoneoptions,
            selectedOption: {id: 'US/Mountain'}
        };

        $http.get('api/module/common/getclassescategories').success(function(categoriesdata) {
            $scope.classescategories = {
                availableOptions: categoriesdata.data,
                selectedOption: {id: categoriesdata.data[0].id}
            };
        });

        $http.get('api/module/common/getallinstructor').success(function(instructordata) {
            $scope.classesinstructors = {
                availableOptions: instructordata.data,
                selectedOption: {}
            };
        });

        $scope.class = {
            is_paid: '1',
            discount_type: '0',
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
            $('#instructors').select2({
                placeholder: "Select a Host/Instructor(s)",
                allowClear: true
            });
            $("#offer_start_date").datetimepicker({
                isRTL: false,
                format: "mm-dd-yyyy HH:ii P",
                autoclose: true,
                todayBtn: true,
                showMeridian: true,
                viewSelect: "decade",
                startDate: new Date(),
                pickerPosition: "bottom-left"
            }).on('hide', function(selected) {
                if (selected.date !== null) {
                    var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                    $('#offer_end_date').datetimepicker('setStartDate', fromDate);
                }
            });
            $("#offer_end_date").datetimepicker({
                isRTL: false,
                format: "mm-dd-yyyy HH:ii P",
                autoclose: true,
                todayBtn: true,
                showMeridian: true,
                viewSelect: "decade",
                startDate: new Date(),
                pickerPosition: "bottom-left"
            }).on('hide', function(selected) {
                if (selected.date !== null) {
                    var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                    $('#offer_start_date').datetimepicker('setStartDate', new Date());
                    $('#offer_start_date').datetimepicker('setEndDate', fromDate);
                }
            });
        }, 0, false);
    }

    $scope.Add_Class = function() {
        var form_data = new FormData();
        for (var key in $scope.class) {
            form_data.append(key, $scope.class[key]);
        }
        form_data.append('timezone', $scope.timezones.selectedOption.id);
        form_data.append('category_id', $scope.classescategories.selectedOption.id);
        form_data.append('spe_id', $scope.class.selectedspe.id);
        for (var key in $scope.classesinstructors.selectedOption) {
            form_data.append('instructors[]', $scope.classesinstructors.selectedOption[key].id);
        }
        $http({
            url: 'api/module/classes/addClass/',
            method: "POST",
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false},
            data: form_data
        }).success(function(resdata) {
            if (resdata.success == 1) {
                $scope.success = true;
                $(".show-success").text('');
                $(".show-success").text(resdata.error_code);
                $scope.activePath = $location.path('/classes');
            } else {
                $scope.error_code = resdata.error_code;
                $scope.error = true;
                $("html, body").animate({scrollTop: 0}, "slow");
                return;
            }
        });
    };

    $scope.edit_Class = function() {
        $scope.error = false;
        $scope.timezones = {
            availableOptions: $rootScope.timezoneoptions,
            selectedOption: {id: 'US/Mountain'}
        };
        $http.get('api/module/common/getclassescategories').success(function(categoriesdata) {
            $scope.classescategories = {
                availableOptions: categoriesdata.data,
                selectedOption: {}
            };
        });

        $http.get('api/module/common/getallinstructor').success(function(instructordata) {
            $scope.classesinstructors = {
                availableOptions: instructordata.data,
                selectedOption: {}
            };
        });
        var id = $stateParams.id;
        $http({
            url: 'api/module/classes/getClass/',
            method: "POST",
            params: {id: id}
        }).success(function(data) {
            $scope.class = data.data;
            $scope.timezones.selectedOption = {id: data.data.timezone};
            $scope.classescategories.selectedOption = {id: data.data.category_id};
            $scope.classesinstructors.selectedOption = data.data.instructor;
            $scope.classesinstructors.selectedOption_old = data.data.instructor;
            $scope.$broadcast('dataclassloaded');
        });
    };

    $scope.$on('dataclassloaded', function() {
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
            $('#instructors').select2({
                placeholder: "Select a Host/Instructor(s)",
                allowClear: true
            });
            $("#offer_start_date").datetimepicker({
                isRTL: false,
                format: "mm-dd-yyyy HH:ii P",
                autoclose: true,
                todayBtn: true,
                showMeridian: true,
                viewSelect: "decade",
                startDate: new Date(),
                pickerPosition: "bottom-left"
            }).on('hide', function(selected) {
                if (selected.date !== null) {
                    var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                    $('#offer_end_date').datetimepicker('setStartDate', fromDate);
                }
            });
            $("#offer_end_date").datetimepicker({
                isRTL: false,
                format: "mm-dd-yyyy HH:ii P",
                autoclose: true,
                todayBtn: true,
                showMeridian: true,
                viewSelect: "decade",
                startDate: new Date(),
                pickerPosition: "bottom-left"
            }).on('hide', function(selected) {
                if (selected.date !== null) {
                    var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                    $('#offer_start_date').datetimepicker('setStartDate', new Date());
                    $('#offer_start_date').datetimepicker('setEndDate', fromDate);
                }
            });
        }, 0, false);
    });

    $scope.Update_Class = function() {
        var form_data = new FormData();
        for (var key in $scope.class) {
            form_data.append(key, $scope.class[key]);
        }
        form_data.append('timezone', $scope.timezones.selectedOption.id);
        form_data.append('category_id', $scope.classescategories.selectedOption.id);
        form_data.append('spe_id', $scope.class.selectedspe.id);
        for (var key in $scope.classesinstructors.selectedOption) {
            form_data.append('instructors[]', $scope.classesinstructors.selectedOption[key].id);
        }
        for (var key in $scope.classesinstructors.selectedOption_old) {
            form_data.append('instructors_old[]', $scope.classesinstructors.selectedOption_old[key].id);
        }
        $http({
            url: 'api/module/classes/editClass/',
            method: "POST",
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false},
            data: form_data
        }).success(function(resdata) {
            if (resdata.success == 1) {
                $scope.success = true;
                $(".show-success").text('');
                $(".show-success").text(resdata.error_code);
                $scope.activePath = $location.path('/classes');
            } else {
                $scope.error_code = resdata.error_code;
                $scope.error = true;
                $("html, body").animate({scrollTop: 0}, "slow");
                return;
            }
        });
    };

    $scope.getClasses = function() {
        $scope.list = [];
        $scope.libraryTemp = {};
        $scope.totalItemsTemp = {};

        $scope.totalItems = 0;
        $scope.pageChanged = function(newPage) {
            getResultsPage(newPage);
        };
        
        getResultsPage(1);
        function getResultsPage(pageNumber) {
            if (!$.isEmptyObject($scope.libraryTemp)) {
                $http({
                    url: 'api/module/classes/getAllClasses',
                    method: "POST",
                    params: {search: $scope.search, page: pageNumber}
                }).success(function(data) {
                    $scope.list = data.data;
                    $scope.totalItems = data.total;
                });
            } else {
                $http({
                    url: 'api/module/classes/getAllClasses',
                    method: "POST",
                    params: {page: pageNumber}
                }).success(function(data) {
                    $scope.list = data.data;
                    $scope.totalItems = data.total;
                });
            }
        }
        
        $scope.searchDB = function(){
            if($scope.search.length >= 3){
                if($.isEmptyObject($scope.libraryTemp)){
                    $scope.libraryTemp = $scope.list;
                    $scope.totalItemsTemp = $scope.totalItems;
                    $scope.list = {};
                }
                getResultsPage(1);
            }else{
                if(! $.isEmptyObject($scope.libraryTemp)){
                    $scope.list = $scope.libraryTemp ;
                    $scope.totalItems = $scope.totalItemsTemp;
                    $scope.libraryTemp = {};
                }
            }
        }
    }

    $scope.Delete_Class = function(data) {
        var index = $scope.list.map(function(class_single) {
            return class_single.id;
        }).indexOf(data.id);

        var deleteEvent = confirm('Are You Absolutely Sure You Want To Delete?');
        if (deleteEvent) {
            $http({
                url: 'api/module/classes/deleteClass',
                method: "POST",
                params: {id: data.id}
            }).success(function(resp) {
                $scope.list.splice(index, 1);
            });
        }
    };

    $scope.getClassTimeSlots = function() {
        var id = $stateParams.id;
        $scope.class = {
            id: id
        };
        
        $scope.list = [];
        $scope.libraryTemp = {};
        $scope.totalItemsTemp = {};

        $scope.totalItems = 0;
        $scope.pageChanged = function(newPage) {
            getResultsPage(newPage);
        };
        
        getResultsPage(1);
        function getResultsPage(pageNumber) {
            if (!$.isEmptyObject($scope.libraryTemp)) {
                $http({
                    url: 'api/module/classes/getAllClassTimeSlots',
                    method: "POST",
                    params: {id: id, search: $scope.search, page: pageNumber}
                }).success(function(data) {
                    $scope.list = data.data;
                    $scope.totalItems = data.total;
                });
            } else {
                $http({
                    url: 'api/module/classes/getAllClassTimeSlots',
                    method: "POST",
                    params: {id: id, page: pageNumber}
                }).success(function(data) {
                    $scope.list = data.data;
                    $scope.totalItems = data.total;
                });
            }
        }
        
        $scope.searchDB = function(){
            if($scope.search.length >= 3){
                if($.isEmptyObject($scope.libraryTemp)){
                    $scope.libraryTemp = $scope.list;
                    $scope.totalItemsTemp = $scope.totalItems;
                    $scope.list = {};
                }
                getResultsPage(1);
            }else{
                if(! $.isEmptyObject($scope.libraryTemp)){
                    $scope.list = $scope.libraryTemp ;
                    $scope.totalItems = $scope.totalItemsTemp;
                    $scope.libraryTemp = {};
                }
            }
        }
    }

    $scope.addSlotLoadData = function() {
        var class_id = $stateParams.id;
        $scope.timezones = {
            availableOptions: $rootScope.timezoneoptions,
            selectedOption: {id: 'US/Mountain'}
        };

        $scope.slot = {
            status: '1',
            class_id: class_id,
            slot_type: '0',
            recurring_type: '1',
            recurring_months_base: '1',
            attendee_type: '0'
        };

        $("#start_date").datepicker({
            format: "mm-dd-yyyy",
            autoclose: true,
            startDate: '0d'
        }).on('hide', function(selected) {
            if (selected.date !== null) {
                var fromDate = new Date(selected.date);
                $('#end_date').datepicker('setStartDate', fromDate);
            }
        });
        
        $("#end_date").datepicker({
            format: "mm-dd-yyyy",
            autoclose: true,
            startDate: '0d'
        }).on('hide', function(selected) {
            if (selected.date !== null) {
                var fromDate = new Date(selected.date);
                $('#start_date').datepicker('setStartDate', new Date());
                $('#start_date').datepicker('setEndDate', fromDate);
            }
        });
        
        $('#start_time, #end_time').timepicker({
            autoclose: true,
            minuteStep: 5
        });

        $timeout(function() {
            Metronic.initAjax();
            $('#timezone').select2({
                placeholder: "Select a Timezone",
                allowClear: true
            });
        }, 0, false);
    }
    
    $scope.Add_TimeSlot = function() {
        var form_data = new FormData();
        for (var key in $scope.slot) {
            if(key === "recurring_weeks"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_weeks[]', rkey);
                    }
                }
            } else if(key === "recurring_month_date"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_month_date[]', rkey);
                    }
                }
            } else if(key === "recurring_month_every"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_month_every[]', rkey);
                    }
                }
            } else if(key === "recurring_month_day"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_month_day[]', rkey);
                    }
                }
            } else {
                form_data.append(key, $scope.slot[key]);
            }
        }
        form_data.append('timezone', $scope.timezones.selectedOption.id);
        $http({
            url: 'api/module/classes/addTimeSlot/',
            method: "POST",
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false},
            data: form_data
        }).success(function(resdata) {
            if (resdata.success == 1) {
                $scope.success = true;
                $(".show-success").text('');
                $(".show-success").text(resdata.error_code);
                $scope.activePath = $location.path('/classes/timeslots/'+$scope.slot.class_id);
            } else {
                $scope.error_code = resdata.error_code;
                $scope.error = true;
                $("html, body").animate({scrollTop: 0}, "slow");
                return;
            }
        });
    };
    
    $scope.editSlotLoadData = function() {
        var class_id = $stateParams.class_id;
        var id = $stateParams.id;
        $scope.timezones = {
            availableOptions: $rootScope.timezoneoptions,
            selectedOption: {id: 'US/Mountain'}
        };

        $scope.slot = {
            status: '1',
            class_id: class_id,
            id: id,
            slot_type: '0',
            recurring_type: '1',
            recurring_months_base: '1',
            attendee_type: '0'
        };

        $http({
            url: 'api/module/classes/getEditSlot/',
            method: "POST",
            params: {id: id, class_id: class_id}
        }).success(function(data) {
            $scope.slot = data.data;
            $scope.timezones.selectedOption = {id: data.data.timezone};
            $scope.$broadcast('dataslotloaded');
        });
    };

    $scope.$on('dataslotloaded', function() {
        $timeout(function() {
            Metronic.initAjax();
            $('#timezone').select2({
                placeholder: "Select a Timezone",
                allowClear: true
            });
            $("#start_date").datepicker({
                format: "mm-dd-yyyy",
                autoclose: true,
                startDate: '0d'
            }).on('hide', function(selected) {
                if (selected.date !== null) {
                    var fromDate = new Date(selected.date);
                    $('#end_date').datepicker('setStartDate', fromDate);
                }
            });

            $("#end_date").datepicker({
                format: "mm-dd-yyyy",
                autoclose: true,
                startDate: '0d'
            }).on('hide', function(selected) {
                if (selected.date !== null) {
                    var fromDate = new Date(selected.date);
                    $('#start_date').datepicker('setStartDate', new Date());
                    $('#start_date').datepicker('setEndDate', fromDate);
                }
            });

            $('#start_time, #end_time').timepicker({
                autoclose: true,
                minuteStep: 5
            });
        }, 0, false);
    });
    
    $scope.Edit_TimeSlot = function() {
        var form_data = new FormData();
        for (var key in $scope.slot) {
            if(key === "recurring_weeks"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_weeks[]', rkey);
                    }
                }
            } else if(key === "recurring_month_date"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_month_date[]', rkey);
                    }
                }
            } else if(key === "recurring_month_every"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_month_every[]', rkey);
                    }
                }
            } else if(key === "recurring_month_day"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_month_day[]', rkey);
                    }
                }
            } else {
                form_data.append(key, $scope.slot[key]);
            }
        }
        form_data.append('timezone', $scope.timezones.selectedOption.id);
        $http({
            url: 'api/module/classes/editTimeSlot/',
            method: "POST",
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false},
            data: form_data
        }).success(function(resdata) {
            if (resdata.success == 1) {
                $scope.success = true;
                $(".show-success").text('');
                $(".show-success").text(resdata.error_code);
                $scope.activePath = $location.path('/classes/timeslots/'+$scope.slot.class_id);
            } else {
                $scope.error_code = resdata.error_code;
                $scope.error = true;
                $("html, body").animate({scrollTop: 0}, "slow");
                return;
            }
        });
    };
    
    $scope.Delete_TimeSlot = function(data) {
        var index = $scope.list.map(function(slot_single) {
            return slot_single.id;
        }).indexOf(data.id);

        var deleteEvent = confirm('Are You Absolutely Sure You Want To Delete?');
        if (deleteEvent) {
            $http({
                url: 'api/module/classes/deleteTimeSlot',
                method: "POST",
                params: {id: data.id}
            }).success(function(resp) {
                $scope.list.splice(index, 1);
            });
        }
    };
    
    $scope.getSlots = function() {
        var id = $stateParams.id;
        var class_id = $stateParams.class_id;
        $scope.slot = {
            id: id,
            class_id: class_id
        };
        
        $scope.list = [];
        $scope.libraryTemp = {};
        $scope.totalItemsTemp = {};

        $scope.totalItems = 0;
        $scope.pageChanged = function(newPage) {
            getResultsPage(newPage);
        };
        
        getResultsPage(1);
        function getResultsPage(pageNumber) {
            if (!$.isEmptyObject($scope.libraryTemp)) {
                $http({
                    url: 'api/module/classes/getSlots',
                    method: "POST",
                    params: {id: id, class_id: class_id, search: $scope.search, page: pageNumber}
                }).success(function(data) {
                    $scope.list = data.data;
                    $scope.totalItems = data.total;
                });
            } else {
                $http({
                    url: 'api/module/classes/getSlots',
                    method: "POST",
                    params: {id: id, class_id: class_id, page: pageNumber}
                }).success(function(data) {
                    $scope.list = data.data;
                    $scope.totalItems = data.total;
                });
            }
        }
        
        $scope.searchDB = function(){
            if($scope.search.length >= 3){
                if($.isEmptyObject($scope.libraryTemp)){
                    $scope.libraryTemp = $scope.list;
                    $scope.totalItemsTemp = $scope.totalItems;
                    $scope.list = {};
                }
                getResultsPage(1);
            }else{
                if(! $.isEmptyObject($scope.libraryTemp)){
                    $scope.list = $scope.libraryTemp ;
                    $scope.totalItems = $scope.totalItemsTemp;
                    $scope.libraryTemp = {};
                }
            }
        }
    }
});