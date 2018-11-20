angular.module('Personeria').controller('SeguridadController', InitController);
SeguridadController.$inject = ['$scope', '$state', '$sessionStorage'];
function SeguridadController($scope, $state, $sessionStorage) {

    if ($sessionStorage.idLote === undefined) {
        $state.go('dashboard');
    } else {
        $state.go('MenuCalificar');
    }
}

