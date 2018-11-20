angular.module('Personeria').controller('ModuloController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function InitController($scope, $state, $sessionStorage, servicios) {
  if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    }
    $scope.TipoUsuario = $sessionStorage.rol;

    $scope.NombreUsuario = $sessionStorage.nombreUsuario + " " + $sessionStorage.apellidoUsuario;
}