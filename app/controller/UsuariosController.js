angular.module('Personeria').controller('UsuariosController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function InitController($scope, $state, $sessionStorage, servicios) {
    if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    }
    $scope.TipoUsuario = "a" + $sessionStorage.rol;

    $scope.NombreUsuario = $sessionStorage.nombreUsuario + " " + $sessionStorage.apellidoUsuario;
    var IdUsuario = "";
    var idUsuarioEliminar = "";

    llenarTabla();
    function llenarTabla() {
        datos = {accion: "cargarTabla"};
        servicios.Usuarios(datos).then(function success(response) {
            console.log(response.data);
            $scope.usuario = response.data.respuesta;
        });
    }

    $scope.guardarUsuario = function () {
        $scope.usu.accion = "guardarUsuario";
//        console.log($scope.serv);
        servicios.Usuarios($scope.usu).then(function success(response) {
            console.log(response.data);
            if (response.data.respuesta == "Registro Guardado Correctamente") {
                $scope.alerta = response.data.respuesta;
                $scope.tipoAlerta = "alert-success";
                $scope.MostrarAlerta = true;
                llenarTabla();
                $scope.serv = {};
            } else {
                $scope.alerta = response.data.respuesta;
                $scope.tipoAlerta = "alert-danger";
                $scope.MostrarAlerta = true;
            }
            $scope.servicio = response.data.respuesta;
        });

    }

    $scope.EliminarUsuario = function (IdUsuario,nombreUsuario, opcion) {
        if (opcion == "var") {
            idUsuarioEliminar = IdUsuario;
            $scope.UsuarioEliminar = nombreUsuario;
        }
    
        if (opcion == "Eliminar") {
                console.log(idUsuarioEliminar);
            datos = {accion: "eliminarUsuario", IdUsuario: idUsuarioEliminar};
            servicios.Usuarios(datos).then(function success(response) {
                if (response.data.respuesta == "Eliminado") {
                    $('#eliminarUsuario').modal('hide');
                    llenarTabla();
                }
            });
        }
    }

    $scope.EditarUsuario = function (idusuario) {

        datos = {accion: "TraerDatosEditar", IdUsuario: idusuario};
        servicios.Usuarios(datos).then(function success(response) {
//            console.log(response.data.respuesta[0]);
            
            $scope.usuEditar = {
                NombreCompleto: response.data.respuesta[0].NombreCompleto,
                Cedula: response.data.respuesta[0].Cedula,
                Correo: response.data.respuesta[0].Correo,
                Usuario: response.data.respuesta[0].NombreUsuario,
                Rol: response.data.respuesta[0].Rol,
                Contrasena: response.data.respuesta[0].Cotrasena,
                IdUsuario : idusuario
            };
            console.log($scope.usuEditar);
        });

    }

    $scope.FormEditarUsuario = function () {
        console.log($scope.usuEditar);
        $scope.usuEditar.accion = "editarUsuario";
//        console.log($scope.servEditar);
        servicios.Usuarios($scope.usuEditar).then(function success(response) {
            if (response.data.respuesta == "Editado Correctamente") {
                console.log("EDITADO");
                llenarTabla();
                $('#exampleModal').modal('hide');
            }
        });
    }

    $scope.cerrarAlerta = function () {
        $scope.MostrarAlerta = false;
    }

}
