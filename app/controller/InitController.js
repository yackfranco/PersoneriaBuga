angular.module('Personeria').controller('InitController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function InitController($scope, $state, $sessionStorage, servicios) {

    
    $scope.mostrar = true;
    $scope.usuario = {};
//    console.log($sessionStorage.nombreUsuario);

    $scope.submitLogin = function () {
//        console.log($scope.usuario);
        servicios.login($scope.usuario).then(function success(response) {
            console.log(response.data[0]);
            $sessionStorage.nombreUsuario = response.data[0].usu_nombres;
            $sessionStorage.apellidoUsuario = response.data[0].usu_apellidos;
            $sessionStorage.idusuario = response.data[0].usu_codigo;
            $sessionStorage.rol = response.data[0].rol;
            $state.go('dashboard');
        }, function error(response) {
            console.log("no entró");
            $scope.loginError = true;
        });
    }






//	if ($sessionStorage.autenticado === undefined) {
//		$scope.mostrar = true;
//	} else {
//                console.log($state.router.urlRouter);
//		$scope.mostrar = false;
//                $scope.dga = 'diseñoGeneralAll';
//	}
}