angular.module('Personeria').service('servicios', servicios);
servicios.$inject = ['$http', 'urlBase', '$httpParamSerializerJQLike'];

  function servicios($http, urlBase, $httpParamSerializerJQLike) {

    this.login = (data) => {
        return $http.post(urlBase + 'login.php', $httpParamSerializerJQLike(data));
    };
    
    this.ConfigSistema = (data) => {
        return $http.post(urlBase + 'ConfigSistema.php', $httpParamSerializerJQLike(data));
    };
    
     this.MenuCalificar = (data) => {
        return $http.post(urlBase + 'MenuCalificar.php', $httpParamSerializerJQLike(data));
    };
    
    /*this.logout = ($sessionStorage, $scope) => {
      delete $sessionStorage.data_user;
      $scope.$parent.show = true;
      window.location.replace("http://localhost:8080/gestiondocumental/public/");
    };*/

  }