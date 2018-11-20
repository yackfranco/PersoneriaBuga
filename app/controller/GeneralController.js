angular.module('Personeria').controller('GeneralController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function InitController($scope, $state, $sessionStorage, servicios) {
    $scope.mostrar = true;

    function arranque() {
        console.log("HOLI");
        if ($sessionStorage.idusuario === undefined) {
            $state.go('login');
        }
        $scope.TipoUsuario = $sessionStorage.rol;

        $scope.NombreUsuario = $sessionStorage.nombreUsuario + " " + $sessionStorage.apellidoUsuario;
    }
    $scope.ClicItem = function (es) {
        console.log(es);
        switch (es) {
            case 'Modulo':
                $state.go('Modulo');
                break;
            case 'Servicios':
                $state.go('Servicios');
                break;
            case 'ConfigTipoPoblacion':
                $state.go('ConfigTipoPoblacion');
                break;
            case 'AsigServicios':
                $state.go('AsigServicios');
                break;
            case 'Usuarios':
                $state.go('Usuarios');
                break;
            case 'ConfigTurnosPerdidos':
                $state.go('ConfigTurnosPerdidos');
                break;
            case 'Reportes':
                $state.go('Reportes');
                break;
                case 'ConfigTv':
                $state.go('ConfigTv');
                break;
        }
        ;
    }


    $scope.cerrarSesion = function (es) {
        delete $sessionStorage.apellidoUsuario;
        delete $sessionStorage.idusuario;
        delete $sessionStorage.nombreUsuario;
        delete $sessionStorage.rol;
        $state.go('login');
    }


}