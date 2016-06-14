app.factory('dataService', ['$http', '$q', '$rootScope', function($http, $q, $rootScope) { 

	var dataService = {};

  var language = "";

	  // Get Text
  	dataService.getInterfaceText = function() {
  		var deferred = $q.defer();
  		$http.get('php/getLanguages.php')
  		.success(function(data){
  			deferred.resolve(data);
  		})
  		.error(function(data){
  			deferred.reject(data);
  		});
  		return deferred.promise;
  	}

  	// Get Users
  	dataService.getUsers = function() {
  		var deferred = $q.defer();
  		$http.get('php/getUsers.php')
  		.success(function(data){
  			deferred.resolve(data);
  		})
  		.error(function(data){
  			deferred.reject(data);
  		});
  		return deferred.promise;
  	}

  	// Get Dealers
  	dataService.getDealers = function() {
  		var deferred = $q.defer();
  		$http.get('php/getDealers.php')
  		.success(function(data){
  			deferred.resolve(data);
  		})
  		.error(function(data){
  			deferred.reject(data);
  		});
  		return deferred.promise;
  	}

  	// Get Locations
  	dataService.getLocations = function() {
  		var deferred = $q.defer();
  		$http.get('php/getLocations.php')
  		.success(function(data){
  			deferred.resolve(data);
  		})
  		.error(function(data){
  			deferred.reject(data);
  		});
  		return deferred.promise;
  	}

    // Get Admins
    dataService.getAdmins = function() {
      var deferred = $q.defer();
      $http.get('php/getAdmins.php')
      .success(function(data){
        deferred.resolve(data);
      })
      .error(function(data){
        deferred.reject(data);
      });
      return deferred.promise;
    }

  	// Update Data

  	dataService.updateData = function(action, type, item) {

  		var serverScript = ''

  		var inputData = item.inputData;

      var encodedString = {};

  		encodedString.user = 
  		'username='      +	 encodeURIComponent(inputData.user_name) +
			'&starsid='      +	 encodeURIComponent(inputData.user_stars) +
			'&dealerid='     +   encodeURIComponent(inputData.dealer_id) +
			'&userid='       +	 encodeURIComponent(inputData.user_id);

      encodedString.dealer = 
      'dealerid='      +  encodeURIComponent(inputData.dealer_id) +
      '&dealercode='   +  encodeURIComponent(inputData.dealer_code) +
      '&locationid='   +  encodeURIComponent(inputData.location_id);

      encodedString.location = 
      'locationname='  +  encodeURIComponent(inputData.location_name) +
      '&locationurl='  +  encodeURIComponent(inputData.location_url) +
      '&locationid='   +  encodeURIComponent(inputData.location_id);

      encodedString.admin = 
      'adminID='  +  encodeURIComponent(inputData.admin_id) +
      '&username='  +  encodeURIComponent(inputData.admin_name) +
      '&password='   +  encodeURIComponent(inputData.admin_password);


		if 		  ( action=='update' & type=='user' ){
			serverScript = 'php/updateUser.php';
		} else if ( action=='add' & type=='user') {
			serverScript = 'php/addUser.php';
		} else if ( action=='delete' & type=='user' ) {
			serverScript = 'php/deleteUser.php';


		} else if ( action=='update' & type=='dealer') {
			serverScript = 'php/updateDealer.php';
		} else if ( action=='add' & type=='dealer') {
			serverScript = 'php/addDealer.php';
		} else if ( action=='delete' & type=='dealer') {
			serverScript = 'php/deleteDealer.php';
		

		} else if ( action=='update' & type=='location') {
			serverScript = 'php/updateLocation.php';
		} else if ( action=='add' & type=='location') {
			serverScript = 'php/addLocation.php';
		} else if ( action=='delete' & type=='location') {
			serverScript = 'php/deleteLocation.php';


    } else if ( action=='update' & type=='admin') {
      serverScript = 'php/updateAdmin.php';
    } else if ( action=='add' & type=='admin') {
      serverScript = 'php/addAdmin.php';
    } else if ( action=='delete' & type=='admin') {
      serverScript = 'php/deleteAdmin.php';
    }

  		$http({
			method: 'POST',
			url: serverScript,
			data: encodedString[type],
			headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		})
		.success( function(data, status, headers, config) {
			console.log(data);
			$rootScope.$broadcast('update');

		})
		.error( function(data, status, headers, config) {
			console.log('Unable to submit form');
		})
  	}

  	return dataService;
	
}]);