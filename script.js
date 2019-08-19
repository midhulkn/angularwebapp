var app = angular.module('main', ['ngRoute','angularUtils.directives.dirPagination']);

app.config(function($routeProvider, $locationProvider) {
	$routeProvider.when('/', {
		templateUrl: './components/login.html',
		controller: 'loginCtrl'
	}).when('/logout', {
		resolve: {
			deadResolve: function($location, user) {
				user.clearData();
				$location.path('/');
			}
		}
	}).when('/adminpanel', {
		templateUrl: './components/adminpanel.html',
		controller: 'loginCtrl'
		
	}).when('/update', {
		templateUrl: './components/update.html',
		controller: 'modifyCtrl'
		
	}).when('/login', {
		templateUrl: './components/login.html',
		controller: 'loginCtrl'
		
	}).when('/register', {
		templateUrl: './components/register.html',
		controller: 'registerCtrl'
	})
	.when('/dashboard', {
		resolve: {
			check: function($location, user) {
				if(!user.isUserLoggedIn()) {
					$location.path('/login');
				}
			},
		},
		templateUrl: './components/dashboard.html',
		controller: 'loginCtrl'
	})
	.otherwise({
		template: '404'
	});

	$locationProvider.html5Mode(true);
});

app.service('user', function() {
	var username;
	var loggedin = false;
	var id;

	this.getName = function() {
		return username;
	};

	this.setID = function(userID) {
		id = userID;
	};
	this.getID = function() {
		return id;
	};

	this.isUserLoggedIn = function() {
		if(!!localStorage.getItem('login')) {
			loggedin = true;
			var data = JSON.parse(localStorage.getItem('login'));
			username = data.username;
			id = data.id;
		}
		return loggedin;
	};

	this.saveData = function(data) {
		username = data.user;
		id = data.id;
		loggedin = true;
		localStorage.setItem('login', JSON.stringify({
			username: username,
			id: id
		}));
	};

	this.clearData = function() {
		localStorage.removeItem('login');
		username = "";
		id = "";
		loggedin = false;
	}
})

app.controller('homeCtrl', function($scope, $location) {
	$scope.goToLogin = function() {
		$location.path('/login');
	};
	$scope.goToRegister = function() {
		$location.path('/register');
	}
});


app.controller('loginCtrl', function($scope, $http, $location, user) {
	$scope.login = function() {
		var username = $scope.username;
		var password = $scope.password;
		$http({
			url: 'http://localhost:8000/server.php',
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			},
			data: 'username='+username+'&password='+password
		}).then(function(response) {
			if(response.data.status == 'adminlogged') {
				user.saveData(response.data);
				$location.path('/adminpanel');
		}
		else if(response.data.status == 'loggedin') 
			{	user.saveData(response.data);
				$location.path('/dashboard');
			 }
			 
			 else {
				alert('invalid login');
			}
		})
	}
});
app.controller('registerCtrl', function($scope, $http, $location, user) {
	$scope.register = function() {
		var username = $scope.username;
		var password = $scope.password;
		var email = $scope.email;
		$http({
			url: 'http://localhost:8000/register.php',
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			},
			data: 'username='+username+'&email='+email+'&password='+password
		}).then(function(response) {
			if(response.data.status == 'loggedin') {
				user.saveData(response.data);
				$location.path('/login');
				alert('Account Created');
				
			} else {
				alert('Username Exist');
			}
		})
	}
});

app.controller('tableCtrl', function($scope, $http, $location, user) {
	$scope.createtable = function(isValid) {
		var lotno = $scope.lotno;
		$http({
			url: 'http://localhost:8000/createtable.php',
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			},
			data:'Tablename='+lotno
		}).then(function(response) {
			if(response.data.status == 'posted') {
				alert('Table Created!');
			} else {
				alert('Error in Creation');
			}
		})
	}
});

app.controller('displayCtrl', ['$scope', '$http', function ($scope, $http) {
 $http({
  method: 'get',
  url: 'http://localhost:8000/displaytable.php'
 }).then(function successCallback(response) {
  $scope.users = response.data;
 });
}]);
  
app.controller('backCtrl', ['$scope', function($scope) {
  $scope.$back = function() { 
    window.history.back();
  };
}]);
app.controller('dashboardCtrl', function($scope, user) {
	$scope.user = user.getName();
	$scope.sessionid = user.getID();

});


  