angular.module('Personeria').controller('InitController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function InitController($scope, $state, $sessionStorage, servicios) {
//    console.log($sessionStorage.idusuario);
    var datos = {};
    if ($sessionStorage.idusuario != undefined) {
        if ($sessionStorage.rol == "ASESOR") {
            $state.go('Mando');
        } else if ($sessionStorage.rol == "ADMINISTRADOR") {
            $state.go('dashboard');
        }
    }

    datos = {accion: "FechasReiniciarDatos"};
    servicios.login(datos);


    $scope.mostrar = true;
    $scope.usuario = {};

    $scope.mostrarModulo = true;
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

        $sessionStorage.modulo = $scope.usuario.modulo;
//        console.log($scope.usuario);
        $scope.usuario.accion = "entrar";
        console.log($scope.usuario);
        servicios.login($scope.usuario).then(function success(response) {
            console.log(response.data);
            if (response.data == "Bloqueado") {
                alert("Su lincencia ha expirado, por favor comuniquese con Ingetronik");
                return;
            }
            if (response.data == "No Entro") {
                alert("La contraseña es Incorrecta para el Usuario ");
            } else {
                console.log(response.data[0]);
                if (response.data[0].Rol == "ASESOR") {
                    if ($scope.usuario.modulo === undefined) {
                        alert("debe ejelir un MODULO");
                    } else {
                        $state.go('Mando');

                    }
                } else if (response.data[0].Rol == "ADMINISTRADOR") {
                    $state.go('dashboard');
                }
                $sessionStorage.nombreUsuario = response.data[0].NombreCompleto;
                $sessionStorage.idusuario = response.data[0].IdUsuario;
                $sessionStorage.rol = response.data[0].Rol;
            }
        });
    }

    $scope.UsuarioSeleccionado = function () {
        console.log($scope.usuario.usuario);
        datos = {accion: "traerRol", idUsuario: $scope.usuario.usuario};
        servicios.login(datos).then(function success(response) {
            if (response.data == "ASESOR") {
                $scope.mostrarModulo = false;
            } else {
                $scope.mostrarModulo = true;
            }
        });
    }


    var contarLicencia = 0;
    $scope.abrirLicencia = function () {
        console.log("holi");
        if (contarLicencia == 10) {
            $scope.mostrarContra = true;
            $('#modalCambiarFecha').modal('show'); // abrir
        }
        contarLicencia++;
    }
    $scope.algo = "";
    $scope.EntrarLicencia = function () {
        console.log($scope.algo);
        datos = {accion: "ValidarEntrarLicencia", contrase: $scope.contraLicencia};
        servicios.login(datos).then(function success(response) {
            if (response.data == "Entro") {
                $scope.mostrarContra = false;
                $scope.mostrarFechaLicencia = true;
            } else {
                alert("No Entro");
            }
        });
    }
}