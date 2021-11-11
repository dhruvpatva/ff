/* Setup Rounting For All Pages */
FittFinderApp.config(['$stateProvider', '$urlRouterProvider','$locationProvider','$httpProvider', 'USER_ROLES', function($stateProvider, $urlRouterProvider,$locationProvider, $httpProvider , USER_ROLES) {
    
    // Redirect any unmatched url
    $urlRouterProvider.otherwise("/");
     
    $stateProvider
        // Dashboard
        .state('dashboard', {
            url: "/",
            templateUrl: "views/dashboard.html",            
            data: {pageTitle: 'Dashboard', pageSubTitle: 'statistics & reports',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "DashboardController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                            './assets/global/plugins/morris/morris.css',
                            './assets/admin/pages/css/tasks.css',
                            
                            './assets/global/plugins/morris/morris.min.js',
                            './assets/global/plugins/morris/raphael-min.js',
                            './assets/global/plugins/jquery.sparkline.min.js',

                            './assets/admin/pages/scripts/index3.js',
                            './assets/admin/pages/scripts/tasks.js',

                             'app/controllers/DashboardController.js'
                        ] 
                    });
                }]
            }
        })
        
        // User Listing
        .state('users', {
            url: "/users",
            templateUrl: "views/users/index.html",            
            data: {pageTitle: 'Users', pageSubTitle: 'All users',authorizedRoles: [USER_ROLES.admin]},
            controller: "UsersController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/UsersController.js'
                        ] 
                    });
                }]
            }
        })
        
         // User Add
        .state('addspeadmin', {
            url: "/users/addspeadmin",
            templateUrl: "views/users/addspeadmin.html",            
            data: {pageTitle: 'Users', pageSubTitle: 'Add SPE Admin',authorizedRoles: [USER_ROLES.admin]},
            controller: "UsersController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/UsersController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                             './assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
                        ] 
                    });
                }]
            }
        })
        
        .state('addspi', {
            url: "/users/addspi",
            templateUrl: "views/users/addspi.html",            
            data: {pageTitle: 'Users', pageSubTitle: 'Add SPI',authorizedRoles: [USER_ROLES.admin]},
            controller: "UsersController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/UsersController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                             './assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
                             './assets/global/plugins/ckeditor/ckeditor.js',
                        ] 
                    });
                }]
            }
        })
        
        
        // SPI Edit
        .state('useredit', {
            url: "/users/edit/:id",
            templateUrl: "views/users/edit.html",            
            data: {pageTitle: 'Users', pageSubTitle: 'All users',authorizedRoles: [USER_ROLES.admin]},
            controller: "UsersController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/UsersController.js'
                        ] 
                    });
                }]
            }
        })
        
        .state('editspi', {
            url: "/users/editspi/:id",
            templateUrl: "views/users/editspi.html",            
            data: {pageTitle: 'Users', pageSubTitle: 'Edit SPI',authorizedRoles: [USER_ROLES.admin]},
            controller: "UsersController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/UsersController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                             './assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
                             './assets/global/plugins/ckeditor/ckeditor.js',
                        ] 
                    });
                }]
            }
        })
        
        .state('editspe', {
            url: "/users/editspe/:id",
            templateUrl: "views/users/editspe.html",            
            data: {pageTitle: 'Users', pageSubTitle: 'Edit SPE',authorizedRoles: [USER_ROLES.admin]},
            controller: "UsersController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/UsersController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                             './assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
                             './assets/global/plugins/ckeditor/ckeditor.js',
                        ] 
                    });
                }]
            }
        })
        
        
        // Acitivity Level
        .state('activitylevel', {
            url: "/activitylevel",
            templateUrl: "views/activitylevel/index.html",            
            data: {pageTitle: 'Activity Level', pageSubTitle: 'All Activity Level',authorizedRoles: [USER_ROLES.admin]},
            controller: "ActivityLevelController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/ActivityLevelController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Acitivity Add
        .state('activityadd', {
            url: "/activitylevel/add",
            templateUrl: "views/activitylevel/add.html",            
            data: {pageTitle: 'Activity Level', pageSubTitle: 'Add Activity Level',authorizedRoles: [USER_ROLES.admin]},
            controller: "ActivityLevelController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/ActivityLevelController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Acitivity Edit
        .state('activityedit', {
            url: "/activitylevel/edit/:id",
            templateUrl: "views/activitylevel/edit.html",            
            data: {pageTitle: 'Activity Level', pageSubTitle: 'Edit Activity Level',authorizedRoles: [USER_ROLES.admin]},
            controller: "ActivityLevelController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/ActivityLevelController.js'
                        ] 
                    });
                }]
            }
        })
        
        // All Goals
        .state('goals', {
            url: "/goals",
            templateUrl: "views/goals/index.html",            
            data: {pageTitle: 'Goals', pageSubTitle: 'All Goals',authorizedRoles: [USER_ROLES.admin]},
            controller: "GoalsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/GoalsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Goal Add
        .state('goaladd', {
            url: "/goals/add",
            templateUrl: "views/goals/add.html",            
            data: {pageTitle: 'Goal', pageSubTitle: 'Add Goal',authorizedRoles: [USER_ROLES.admin]},
            controller: "GoalsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/GoalsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Goal Edit
        .state('goaledit', {
            url: "/goals/edit/:id",
            templateUrl: "views/goals/edit.html",            
            data: {pageTitle: 'Goal', pageSubTitle: 'Edit Goal',authorizedRoles: [USER_ROLES.admin]},
            controller: "GoalsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/GoalsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // All Amenities
        .state('amenities', {
            url: "/amenities",
            templateUrl: "views/amenities/index.html",            
            data: {pageTitle: 'Amenities', pageSubTitle: 'All Amenities',authorizedRoles: [USER_ROLES.admin]},
            controller: "AmenitiesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/AmenitiesController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Amenities Add
        .state('amenitiesadd', {
            url: "/amenities/add",
            templateUrl: "views/amenities/add.html",            
            data: {pageTitle: 'Amenities', pageSubTitle: 'Add Amenities',authorizedRoles: [USER_ROLES.admin]},
            controller: "AmenitiesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/AmenitiesController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Amenities Edit
        .state('amenitiesedit', {
            url: "/amenities/edit/:id",
            templateUrl: "views/amenities/edit.html",            
            data: {pageTitle: 'Amenities', pageSubTitle: 'Edit Amenities',authorizedRoles: [USER_ROLES.admin]},
            controller: "AmenitiesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/AmenitiesController.js'
                        ] 
                    });
                }]
            }
        })
        
         // All SPE
        .state('allspe', {
            url: "/allspe",
            templateUrl: "views/spe/index.html",            
            data: {pageTitle: 'SPE', pageSubTitle: 'All SPE',authorizedRoles: [USER_ROLES.admin]},
            controller: "SpeController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/SpeController.js'
                        ] 
                    });
                }]
            }
        })
        
        
        
        //  Add SPE
        .state('speadd', {
            url: "/spe/add",
            templateUrl: "views/spe/add.html",            
            data: {pageTitle: 'SPE', pageSubTitle: 'Add SPE',authorizedRoles: [USER_ROLES.admin]},
            controller: "SpeController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/SpeController.js',
                             './assets/global/plugins/ckeditor/ckeditor.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/select2/select2.min.js',
                        ] 
                    });
                }]
            }
        })
        
        // Edit SPE
        .state('speedit', {
            url: "/spe/edit/:id",
            templateUrl: "views/spe/edit.html",            
            data: {pageTitle: 'SPE', pageSubTitle: 'Edit SPE',authorizedRoles: [USER_ROLES.admin]},
            controller: "SpeController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/SpeController.js',
                             './assets/global/plugins/ckeditor/ckeditor.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/select2/select2.min.js',
                        ] 
                    });
                }]
            }
        })
        
         // All Amenities
        .state('specialities', {
            url: "/specialities",
            templateUrl: "views/specialities/index.html",            
            data: {pageTitle: 'Specialities', pageSubTitle: 'All Specialities',authorizedRoles: [USER_ROLES.admin]},
            controller: "SpecialitiesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/SpecialitiesController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Amenities Add
        .state('specialitiesadd', {
            url: "/specialities/add",
            templateUrl: "views/specialities/add.html",            
            data: {pageTitle: 'Specialities', pageSubTitle: 'Add Specialities',authorizedRoles: [USER_ROLES.admin]},
            controller: "SpecialitiesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/SpecialitiesController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Amenities Edit
        .state('specialitiesedit', {
            url: "/specialities/edit/:id",
            templateUrl: "views/specialities/edit.html",            
            data: {pageTitle: 'Specialities', pageSubTitle: 'Edit Specialities',authorizedRoles: [USER_ROLES.admin]},
            controller: "SpecialitiesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/SpecialitiesController.js'
                        ] 
                    });
                }]
            }
        })
        
     // Event Categories
        .state('eventcategories', {
            url: "/events/eventcategories",
            templateUrl: "views/events/category_list.html",            
            data: {pageTitle: 'Event Categories', pageSubTitle: 'All Event Categories',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Event Category Add
        .state('addeventcategory', {
            url: "/eventcategories/add_category",
            templateUrl: "views/events/add_category.html",            
            data: {pageTitle: 'Event Categories', pageSubTitle: 'Add Event Category',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Event Category Edit
        .state('editeventcategory', {
            url: "/eventcategories/edit_category/:id",
            templateUrl: "views/events/edit_category.html",            
            data: {pageTitle: 'Event Categories', pageSubTitle: 'Edit Event Category',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // All Events
        .state('events', {
            url: "/events",
            templateUrl: "views/events/index.html",            
            data: {pageTitle: 'Events', pageSubTitle: 'All Events',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Add Event
        .state('addevent', {
            url: "/events/addevent",
            templateUrl: "views/events/add.html",            
            data: {pageTitle: 'Events', pageSubTitle: 'Add Event',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
                             './assets/global/plugins/ckeditor/ckeditor.js',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'
                        ] 
                    });
                }]
            }
        })
        
        // Edit Event
        .state('editevent', {
            url: "/events/editevent/:id",
            templateUrl: "views/events/edit.html",            
            data: {pageTitle: 'Events', pageSubTitle: 'Edit Event',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
                             './assets/global/plugins/ckeditor/ckeditor.js',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'
                        ] 
                    });
                }]
            }
        })
        
        // Event Attendees
        .state('eventattendees', {
            url: "/events/eventattendees/:id",
            templateUrl: "views/events/attendees.html",            
            data: {pageTitle: 'Events', pageSubTitle: 'Event Attendees',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Add Event Attendees
        .state('addeventattendee', {
            url: "/events/addeventattendee/:id",
            templateUrl: "views/events/addeventattendee.html",            
            data: {pageTitle: 'Events', pageSubTitle: 'Add Event Attendee',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'
                        ] 
                    });
                }]
            }
        })
        
        // Classes Categories
        .state('classescategories', {
            url: "/classescategories",
            templateUrl: "views/classes/category_list.html",            
            data: {pageTitle: 'Classes Categories', pageSubTitle: 'All Classes Categories',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "ClassesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/ClassesController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Classes Category Add
        .state('addclassescategory', {
            url: "/classescategories/add_category",
            templateUrl: "views/classes/add_category.html",            
            data: {pageTitle: 'Classes Categories', pageSubTitle: 'Add Classes Category',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "ClassesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/ClassesController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Classes Category Edit
        .state('editclassescategory', {
            url: "/classescategories/edit_category/:id",
            templateUrl: "views/classes/edit_category.html",            
            data: {pageTitle: 'Classes Categories', pageSubTitle: 'Edit Classes Category',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "ClassesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/ClassesController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Add Classes
        .state('addclass', {
            url: "/classes/addclass",
            templateUrl: "views/classes/add.html",            
            data: {pageTitle: 'Classes', pageSubTitle: 'Add Class',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "ClassesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/ClassesController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
                             './assets/global/plugins/ckeditor/ckeditor.js',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'
                        ] 
                    });
                }]
            }
        })
        
        // All Classes
        .state('classes', {
            url: "/classes",
            templateUrl: "views/classes/index.html",            
            data: {pageTitle: 'Classes', pageSubTitle: 'All Classes',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "ClassesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/ClassesController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Edit Class
        .state('editclass', {
            url: "/classes/editclass/:id",
            templateUrl: "views/classes/edit.html",            
            data: {pageTitle: 'Classes', pageSubTitle: 'Edit Class',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "ClassesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/ClassesController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
                             './assets/global/plugins/ckeditor/ckeditor.js',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'
                        ] 
                    });
                }]
            }
        })
        
        // All Timeslots
        .state('timeslots', {
            url: "/classes/timeslots/:id",
            templateUrl: "views/classes/timeslots.html",            
            data: {pageTitle: 'Classes Timeslots', pageSubTitle: 'All Classes Timeslots',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "ClassesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/ClassesController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Add Slot
        .state('addslot', {
            url: "/classes/addslot/:id",
            templateUrl: "views/classes/addslot.html",            
            data: {pageTitle: 'Classes', pageSubTitle: 'Add Slot',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "ClassesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/ClassesController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                             './assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
                             './assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
                             './assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js',
                             './assets/global/plugins/ckeditor/ckeditor.js'
                        ] 
                    });
                }]
            }
        })
        
        // Edit Slot
        .state('editslot', {
            url: "/classes/editslot/:id/:class_id",
            templateUrl: "views/classes/editslot.html",            
            data: {pageTitle: 'Classes', pageSubTitle: 'Edit Slot',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "ClassesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/ClassesController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                             './assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
                             './assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
                             './assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js',
                             './assets/global/plugins/ckeditor/ckeditor.js'
                        ] 
                    });
                }]
            }
        })
        
        // Class Slot Time Slots
        .state('slots', {
            url: "/classes/slots/:id/:class_id",
            templateUrl: "views/classes/slots.html",            
            data: {pageTitle: 'Classes', pageSubTitle: 'Slots',authorizedRoles: [USER_ROLES.admin, USER_ROLES.SPEadmin]},
            controller: "ClassesController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'FittFinderApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/ClassesController.js'
                        ] 
                    });
                }]
            }
        })
}]);
