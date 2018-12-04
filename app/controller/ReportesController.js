angular.module('Personeria').controller('ReportesController', InitController);
InitController.$inject = ['$scope', '$state', '$sessionStorage', 'servicios'];
function InitController($scope, $state, $sessionStorage, servicios) {
    if ($sessionStorage.idusuario === undefined) {
        $state.go('login');
    }
    $scope.TipoUsuario = $sessionStorage.rol;

    $scope.NombreUsuario = $sessionStorage.nombreUsuario + " " + $sessionStorage.apellidoUsuario;
    var datos = {};

    $scope.DescargarPDF = function (ContenidoID, nombre) {
        console.log("perro");
        var pdf = new jsPDF('p', 'pt', 'letter');

        html = $('#' + ContenidoID).html();

        specialElementHandlers = {};

        margins = {top: 10, bottom: 20, left: 20, width: 522};

        pdf.fromHTML(html, margins.left, margins.top, {'width': margins.width}, function (dispose) {
            pdf.save(nombre + '.pdf');
        }, margins);

    }

    $scope.DescargarEXCEL = function () {
        servicios.reportes().then(function success(response) {
            console.log(response.data);
            $scope.servicio = response.data.respuesta;
        });
    }
}
