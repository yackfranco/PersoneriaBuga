angular.module('Personeria').controller('ServiciosController', InitController);
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


    var idServicioEliminar = 0;
    var datos = {};
    llenarTabla();

    $scope.serv = {};
    $scope.servEditar = {};
    $scope.MostrarAlerta = false;

    function llenarTabla() {
        datos = {accion: "cargarTabla"};
        servicios.Servicios(datos).then(function success(response) {
            console.log(response.data);
            $scope.servicio = response.data.respuesta;
        });
    }

    $scope.guardarServicio = function () {
        $scope.serv.accion = "guardarServicio";
//        console.log($scope.serv);
        servicios.Servicios($scope.serv).then(function success(response) {
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

    $scope.EliminarServicio = function (idservicio, nombreServicio, opcion) {
        console.log(idservicio);
        console.log(nombreServicio);
        if (opcion == "var") {
            idServicioEliminar = idservicio;
            $scope.ServicioEliminar = nombreServicio;
        }

        if (opcion == "Eliminar") {
            datos = {accion: "EliminarServicio", IdServicio: idServicioEliminar};
            servicios.Servicios(datos).then(function success(response) {
                if (response.data.respuesta == "Eliminado") {
                    $('#eliminarServicio').modal('hide');
                    llenarTabla();
                }
            });
        }
    }

    $scope.EditarServicio = function (idservicio) {

        datos = {accion: "TraerDatosEditar", IdServicio: idservicio};
        servicios.Servicios(datos).then(function success(response) {
//            console.log(response.data.respuesta[0]);
            $scope.servEditar = {
                IdServicio: response.data.respuesta[0].IdServicio,
                Servicio: response.data.respuesta[0].Servicio,
                Prefijo: response.data.respuesta[0].Prefijo,
                Cmin: response.data.respuesta[0].Cont_min,
                Cmax: response.data.respuesta[0].Cont_max,
                Secuencia: response.data.respuesta[0].Secuencia,
                prioridad: response.data.respuesta[0].Prioridad,
                tv: response.data.respuesta[0].LlamadoTv
            };
        });

    }

    $scope.FormEditarServicio = function () {
        $scope.servEditar.accion = "editarServicio";
//        console.log($scope.servEditar);
        servicios.Servicios($scope.servEditar).then(function success(response) {
            if (response.data.respuesta == "Editado Correctamente") {
                console.log("EDITADO");
                $scope.alerta = response.data.respuesta;
                $scope.tipoAlerta = "alert-success";
                $scope.MostrarAlerta = true;
                llenarTabla();
                $('#exampleModal').modal('hide');
            }
        });
    }

    $scope.cerrarAlerta = function () {
        $scope.MostrarAlerta = false;
    }

    $scope.EliminarTurnoEspera = function (IdServicio, Servicio, opcion) {
        console.log(IdServicio);
        console.log(Servicio);
        if (opcion == "var") {
            idServicioEliminar = IdServicio;
            $scope.ServicioEliminar = Servicio;
            datos = {accion: "TraerTurnosEspera", IdServicio: idServicioEliminar};
            servicios.Servicios(datos).then(function success(response) {
                $scope.numTurnosEspera = response.data.respuesta;
            });

        }

        if (opcion == "Eliminar") {
            datos = {accion: "EliminarTurnoEspera", IdServicio: idServicioEliminar};
            servicios.Servicios(datos).then(function success(response) {
                if (response.data.respuesta == "Eliminado") {
                    $('#EliminarTurnosEspera').modal('hide');
                    llenarTabla();
                }
            });
        }
    }
}
