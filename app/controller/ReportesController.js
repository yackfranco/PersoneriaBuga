angular.module('Personeria').controller('ReportesController', InitController);
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
    var ip = "";
    $scope.NombreUsuario = $sessionStorage.nombreUsuario;
    var datos = {};

    datos = {accion: 'DatosEmpresa'};
    servicios.ModuloReportes(datos).then(function success(response) {
        console.log(response.data);
        $scope.NombreEmpresa = response.data.respuesta[0].NombreEmpresa;
        $scope.NIT = response.data.respuesta[0].nit;
//        $scope.servicio = response.data.respuesta;
    });
    datos = {accion: 'traerip'};
    servicios.ModuloReportes(datos).then(function success(response) {
        ip = response.respuesta;
    });

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

        location.href = "http://" + ip + "/PersoneriaBuga/app/model/reportes.php";

    }

    $scope.SacarReporte = function () {
        convertDatePickerTimeToMySQLTime($scope.fechaFinal);

        location.href = "http://" + ip + "/PersoneriaBuga/app/model/reportes.php?fechafinal=" + convertDatePickerTimeToMySQLTime($scope.fechaFinal) + "&fechaInicial=" + convertDatePickerTimeToMySQLTime($scope.fechaInicial) + "";
//        console.log($scope.fechaFinal);
    }

    function convertDatePickerTimeToMySQLTime(str) {
        var month, day, year, hours, minutes, seconds;
        var date = new Date(str),
                month = ("0" + (date.getMonth() + 1)).slice(-2),
                day = ("0" + date.getDate()).slice(-2);
        hours = ("0" + date.getHours()).slice(-2);
        minutes = ("0" + date.getMinutes()).slice(-2);
        seconds = ("0" + date.getSeconds()).slice(-2);

        var mySQLDate = [date.getFullYear(), month, day].join("-");
        var mySQLTime = [hours, minutes, seconds].join(":");
        return [mySQLDate, mySQLTime].join(" ");
    }

}
