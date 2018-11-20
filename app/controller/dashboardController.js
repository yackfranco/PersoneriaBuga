angular.module('Personeria').controller('dashboardController', dashboardController);
dashboardController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function dashboardController($scope, $state, $sessionStorage, $servicios) {
    if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    }
    $scope.TipoUsuario = $sessionStorage.rol;

    $scope.NombreUsuario = $sessionStorage.nombreUsuario + " " + $sessionStorage.apellidoUsuario;

}