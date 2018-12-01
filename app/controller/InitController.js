angular.module('Personeria').controller('InitController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function InitController($scope, $state, $sessionStorage, servicios) {


    $scope.mostrar = true;
    $scope.usuario = {};
    var datos = {};
    $scope.mostrarModulo = false;
//    console.log($sessionStorage.nombreUsuario);
    datos = {accion: "cargarUsuarios"};
    servicios.login(datos).then(function success(response) {
        console.log(response);
        $scope.usuarios = response.data;
    });

    datos = {accion: "cargarModulos"};
    servicios.login(datos).then(function success(response) {
        console.log(response);
        $scope.modulos = response.data;
    });

    $scope.submitLogin = function () {
//        console.log($scope.usuario);
        $scope.usuario.accion = "entrar";
          console.log($scope.usuario);
        servicios.login($scope.usuario).then(function success(response) {
            console.log(response.data[0]);
            if(response.data[0].Rol == "ASESOR"){
                 
                $state.go('Mando');
            }
            else if(response.data[0].Rol == "ADMINISTRADOR"){
                $state.go('dashboard');
            }
            $sessionStorage.nombreUsuario = response.data[0].NombreCompleto;
            $sessionStorage.idusuario = response.data[0].IdUsuario;
            $sessionStorage.rol = response.data[0].Rol;
//            $state.go('dashboard');
        });
    }

    $scope.UsuarioSeleccionado = function () {
        console.log($scope.usuario.usuario);
        datos = {accion: "traerRol", idUsuario: $scope.usuario.usuario};
        servicios.login(datos).then(function success(response) {
            if (response.data == "ASESOR") {
                $scope.mostrarModulo = true;
            }
            else{
                $scope.mostrarModulo = false;
            }
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
