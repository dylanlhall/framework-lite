var app = angular.module("sysSpecs", []);

app.controller("fwData", function($scope, $http) {

	$http.get("/index.php", { "params": { "config": "all" } }).then(function (response) {
	
		var config = response.data.config;
		var content = response.data.content;
		
		$scope.pages = content.pages;
		$scope.layout = content.layout;
		
		$scope.folders = config.dir.core;
		$scope.meta = config.meta;
		
		$scope.errors = content.errors;
		console.log(content.errors[404]);
		
		// $scope.config = config;
		$scope.config = new Object;
		
		for (setting in config) {
		
			if (typeof config[setting] == "string") {
			
				$scope.config[setting] = config[setting];
			
			}
			
		}
	
	});

});
