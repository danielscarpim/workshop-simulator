var app = angular.module('workshopApp', ['ngRoute'])

.config(['$routeProvider',
  	function($routeProvider) {
	    $routeProvider
	    	.when('/', {
	    		templateUrl: 'templates/home.html',
	    	})
	    	.when('/admin', {
	        	templateUrl: 'templates/admin.html',
	        	controller: 'adminController'
	      	})
	      	.otherwise({
	        	redirectTo: '/'
	      });
}])
.run(function(dataService, $location, $rootScope) {
    $rootScope.$on( "$routeChangeStart", function(event, next, current) {
      if (dataService.admin == null) {
        // no logged user, redirect to /home
        if ( next.templateUrl === "templates/home.html") {
        } else {
          $location.path("/");
        }
      }
    });
})
.run(function(dataService, $rootScope) {

	$rootScope.interFace = [];
	$rootScope.defaultLanguage = 'English';
	$rootScope.languages = [];
	
	// Get Text
	$rootScope.getInterfaceText = function(language){
		dataService.getInterfaceText()
		.then(function(data){
			$rootScope.languages = data;
			$rootScope.interFace = data[language];
		})
	}

	$rootScope.changeLang = function(selectedLanguage){
		$rootScope.interFace = selectedLanguage;
	}

	$rootScope.getInterfaceText($rootScope.defaultLanguage);	
});