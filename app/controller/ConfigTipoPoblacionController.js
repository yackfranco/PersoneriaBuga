angular.module('Personeria').controller('ConfigTipoPoblacionController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function InitController($scope, $state, $sessionStorage, servicios) {
    if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    }
    $scope.TipoUsuario = $sessionStorage.rol;

    $scope.NombreUsuario = $sessionStorage.nombreUsuario + " " + $sessionStorage.apellidoUsuario;
    $scope.SelecArea = true;
    $scope.btnCrearArea2 = true;
    $scope.crudcrearArea = false;
    $scope.tabla = false;
    $scope.Atras = false;
    var datos = {};
//    alert("HOLA");
    CargarTabla();
    
    $scope.elegirArea = function (x) {
        console.log($scope.AreaSeleccionada);
        $scope.btnCrearArea2 = false;
        $scope.Atras = true;
    }


    $scope.btnCrearArea = function () {
        $scope.crudcrearArea = true;
        $scope.SelecArea = false;
        $scope.tabla = true;
        $scope.btnCrearArea2 = false;
        $scope.Atras = true;

        CargarTabla();
    }

    function CargarTabla() {
        datos = {accion: "cargarArea"};
        servicios.ConfigSistema(datos).then(function success(response) {
            console.log(response.data);
            $scope.areas = response.data.areas;
        });
    }

    $scope.btnEditarArea = true;
    $scope.btnEliminarArea = true;

    var idAreaNueva;

    $scope.EditarArea = function (x) {
        $scope.ModelArea = x.are_nombre;
        idAreaNueva = x.are_codigo;
    }

    $scope.ConfirmarEditarArea = function (x) {
        console.log(idAreaNueva);
        console.log($scope.ModelArea);

        datos = {accion: "EditarArea", idArea: idAreaNueva, textoArea: $scope.ModelArea};
        servicios.ConfigSistema(datos).then(function success(response) {
            if (response.data.estado == "hecho") {
                CargarTabla();
                $('#editarArea').modal('hide');
            } else {
                alert("error al editar Registro");
            }
//            $scope.areas = response.data.areas;
        });
    }
    $scope.btnEliminarArea = function (x) {
        idAreaNueva = x.are_codigo;
        var respuesta = confirm("Desea Eliminar el Registro?");
        if (respuesta) {
            datos = {accion: "EliminarArea", idArea: idAreaNueva};
            servicios.ConfigSistema(datos).then(function success(response) {
                if (response.data.estado == "hecho") {
                    CargarTabla();
                } else {
                    alert("error al editar Registro");
                }
            });
        }
    }

    $scope.CrearRegistroArea = function () {
        if ($scope.NombreNuevaArea == undefined) {
            alert("por favor llene el campo requerido");
            return;
        }
//        console.log($scope.NombreNuevaArea);
        var nombreArea = $scope.NombreNuevaArea;
        datos = {accion: "CrearArea", TextoArea: nombreArea, idUsuario: $sessionStorage.idusuario};
        servicios.ConfigSistema(datos).then(function success(response) {
            if (response.data.estado == "hecho") {
                CargarTabla();
                $scope.NombreNuevaArea = "";
            } else {
                alert("error al editar Registro");
            }
        });
    }
    $scope.btnAtras = function () {
        $scope.Atras = false;
        $scope.crudcrearArea = false;
        $scope.tabla = false;
        $scope.SelecArea = true;
        $scope.btnCrearArea2 = true;
    }
}