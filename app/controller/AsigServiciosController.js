angular.module('Personeria').controller('AsigServiciosController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function InitController($scope, $state, $sessionStorage, servicios) {
   if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    } else {
        if ($sessionStorage.rol == "ASESOR") {
            $state.go('Mando');
        }
    }
    $scope.TipoUsuario = $sessionStorage.rol;

    $scope.NombreUsuario = $sessionStorage.nombreUsuario;
    $scope.usuarios = {};
    CargarUsuario();
    var datos = {};

    function CargarUsuario() {
        datos = {accion: "CargarUsuarios"};
        servicios.RelacionUsuSer(datos).then(function success(response) {
            console.log(response.data.respuesta);
            $scope.usuarios = response.data.respuesta;
        });
    }
    function LlenarTablas(idUsuario) {
        datos = {accion: "ServicioRelacionados", idUsuario: idUsuario};
        servicios.RelacionUsuSer(datos).then(function success(response) {
            console.log(response.data.respuesta);
            $scope.ServicioRelacionado = response.data.respuesta;
        });
        datos = {accion: "ServicioNoRelacionados", idUsuario: idUsuario};
        servicios.RelacionUsuSer(datos).then(function success(response) {
            console.log(response.data.respuesta);
            $scope.ServicioNoRelacionado = response.data.respuesta;
        });
    }


    $scope.UsuarioSeleccionado = function () {
        LlenarTablas($scope.usuarioSelect);
    }

    $scope.AgregarServicio = function (idservicio) {
        datos = {accion: "RelacionarServicio", idUsuario: $scope.usuarioSelect, idServicio: idservicio};
        servicios.RelacionUsuSer(datos).then(function success(response) {
            LlenarTablas($scope.usuarioSelect);
        });
    }

    $scope.EliminarServicio = function (idservicio) {
        datos = {accion: "EliminarRelacionarServicio", idUsuario: $scope.usuarioSelect, idServicio: idservicio};
        servicios.RelacionUsuSer(datos).then(function success(response) {
            LlenarTablas($scope.usuarioSelect);
        });
//        console.log(idservicio);
    }
}
