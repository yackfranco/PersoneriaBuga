angular.module('Personeria').service('servicios', servicios);
servicios.$inject = ['$http', 'urlBase', '$httpParamSerializerJQLike'];

function servicios($http, urlBase, $httpParamSerializerJQLike) {

    this.login = (data) => {
        return $http.post(urlBase + 'login.php', $httpParamSerializerJQLike(data));
    };

    this.Modulo = (data) => {
        return $http.post(urlBase + 'Modulo.php', $httpParamSerializerJQLike(data));
    };


    this.Servicios = (data) => {
        return $http.post(urlBase + 'Servicios.php', $httpParamSerializerJQLike(data));
    };

    this.RelacionUsuSer = (data) => {
        return $http.post(urlBase + 'RelacionUsuSer.php', $httpParamSerializerJQLike(data));
    };

    this.ConfigTurnosPerdidos = (data) => {
        return $http.post(urlBase + 'ConfigTurnosPerdidos.php', $httpParamSerializerJQLike(data));
    };
    this.TipoPoblacion = (data) => {
        return $http.post(urlBase + 'TipoPoblacion.php', $httpParamSerializerJQLike(data));
    };

    this.Usuarios = (data) => {
        return $http.post(urlBase + 'Usuarios.php', $httpParamSerializerJQLike(data));
    };
   this.ConfigTv = (data) => {
        return $http.post(urlBase + 'ConfigTv.php', $httpParamSerializerJQLike(data));
    };
      this.Ejemplo = (data) => {
        return $http.post(urlBase + 'Ejemplo.php', $httpParamSerializerJQLike(data));
    };
      this.Encuesta = (data) => {
        return $http.post(urlBase + 'Encuesta.php', $httpParamSerializerJQLike(data));
    };    
    this.Mando = (data) => {
        return $http.post(urlBase + 'Mando.php', $httpParamSerializerJQLike(data));
    };
    this.reportes = (data) => {
        return $http.post(urlBase + 'reportes.php', $httpParamSerializerJQLike(data));
    };
    /*this.logout = ($sessionStorage, $scope) => {
     delete $sessionStorage.data_user;
     $scope.$parent.show = true;
     window.location.replace("http://localhost:8080/gestiondocumental/public/");
     };*/

}
