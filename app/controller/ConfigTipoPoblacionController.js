angular.module('Personeria').controller('ConfigTipoPoblacionController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function InitController($scope, $state, $sessionStorage, servicios) {
    if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    }
    $scope.TipoUsuario = $sessionStorage.rol;

   
}