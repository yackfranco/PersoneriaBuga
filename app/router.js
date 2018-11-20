angular.module('Personeria').config(['$stateProvider', '$urlRouterProvider', '$locationProvider', '$httpProvider',
    function ($stateProvider, $urlRouterProvider, $locationProvider, $httpProvider) {

        $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=UTF-8';

        $locationProvider.hashPrefix('');
        $urlRouterProvider.otherwise('/');

        $stateProvider.state('index', {
            url: '/',
            templateUrl: 'app/template/login.html',
            controller: 'InitController',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                        return $ocLazyLoad.load([
                            {
                                serie: true,
                                files: [
                                    'app/controller/InitController.js'
                                ]
                            }
                        ]);
                    }]
            }
        }).state('dashboard', {
            url: '/dashboard',
            templateUrl: 'app/template/dashboard.html',
            controller: 'dashboardController',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                        return $ocLazyLoad.load([
                            {
                                serie: true,
                                files: [
                                    'app/controller/dashboardController.js'
                                ]
                            }
                        ]);
                    }]
            }
        }).state('Modulo', {
            url: '/Modulo',
            templateUrl: 'app/template/Modulo.html',
            controller: 'ModuloController',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                        return $ocLazyLoad.load([
                            {
                                serie: true,
                                files: [
                                    'app/controller/ModuloController.js'
                                ]
                            }
                        ]);
                    }]
            }
        }).state('ConfigTipoPoblacion', {
            url: '/ConfigTipoPoblacion',
            templateUrl: 'app/template/ConfigTipoPoblacion.html',
            controller: 'ConfigTipoPoblacionController',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                        return $ocLazyLoad.load([
                            {
                                serie: true,
                                files: [
                                    'app/controller/ConfigTipoPoblacionController.js'
                                ]
                            }
                        ]);
                    }]
            }
        }).state('login', {
            url: '/login',
            templateUrl: 'app/template/login.html',
            controller: 'InitController',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                        return $ocLazyLoad.load([
                            {
                                serie: true,
                                files: [
                                    'app/controller/InitController.js'
                                ]
                            }
                        ]);
                    }]
            }
        }).state('Servicios', {
            url: '/Servicios',
            templateUrl: 'app/template/Servicios.html',
            controller: 'ServiciosController',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                        return $ocLazyLoad.load([
                            {
                                serie: true,
                                files: [
                                    'app/controller/ServiciosController.js'
                                ]
                            }
                        ]);
                    }]
            }
        }).state('AsigServicios', {
            url: '/AsigServicios',
            templateUrl: 'app/template/AsigServicios.html',
            controller: 'AsigServiciosController',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                        return $ocLazyLoad.load([
                            {
                                serie: true,
                                files: [
                                    'app/controller/AsigServiciosController.js'
                                ]
                            }
                        ]);
                    }]
            }
        }).state('Usuarios', {
            url: '/Usuarios',
            templateUrl: 'app/template/Usuarios.html',
            controller: 'UsuariosController',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                        return $ocLazyLoad.load([
                            {
                                serie: true,
                                files: [
                                    'app/controller/UsuariosController.js'
                                ]
                            }
                        ]);
                    }]
            }
        }).state('ConfigTurnosPerdidos', {
            url: '/ConfigTurnosPerdidos',
            templateUrl: 'app/template/ConfigTurnosPerdidos.html',
            controller: 'ConfigTurnosPerdidosController',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                        return $ocLazyLoad.load([
                            {
                                serie: true,
                                files: [
                                    'app/controller/ConfigTurnosPerdidosController.js'
                                ]
                            }
                        ]);
                    }]
            }
        }).state('Reportes', {
            url: '/Reportes',
            templateUrl: 'app/template/Reportes.html',
            controller: 'ReportesController',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                        return $ocLazyLoad.load([
                            {
                                serie: true,
                                files: [
                                    'app/controller/ReportesController.js'
                                ]
                            }
                        ]);
                    }]
            }
        }).state('ConfigTv', {
            url: '/ConfigTv',
            templateUrl: 'app/template/ConfigTv.html',
            controller: 'ConfigTvController',
            resolve: {
                deps: ['$ocLazyLoad', function ($ocLazyLoad) {
                        return $ocLazyLoad.load([
                            {
                                serie: true,
                                files: [
                                    'app/controller/ConfigTvController.js'
                                ]
                            }
                        ]);
                    }]
            }
        })
    }]);
