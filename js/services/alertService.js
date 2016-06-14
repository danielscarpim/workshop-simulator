app.factory('alertService', ['$q', '$rootScope', function($q, $rootScope) { 

	var alertService = {};
	    alertService.currentItem = {};
      alertService.type = "";
	    alertService.confirm = false;

	alertService.openAlert = function(item,type){
  		alertService.currentItem = item;
      alertService.type = type;
  		$rootScope.$broadcast('openAlert');	
  	}

  	alertService.closeAlert = function(item){
  		$rootScope.$broadcast('closeAlert');	
  	}

  	alertService.alertResponse = function(answer){
  		
		alertService.confirm = answer;
		$rootScope.$broadcast('alertResponse');	
  		
  	}

  	return alertService;
}]);