angular.module('ngWordpress', ['ngRoute', 'ngSanitize'])
.config(function($routeProvider, $locationProvider) {
	$locationProvider.html5Mode({
		enabled: true
	});
	$routeProvider
	.when('/', {
		templateUrl: localized.partials + 'main.html',
		controller: 'mainCtr'
	})
	.when('/post/:slug', {
		templateUrl: localized.partials + 'single-post.html',
		controller: 'singlePostCtr'
	})
	.when('/:slug', {
		templateUrl: localized.partials + 'single-page.html',
		controller: 'singlePageCtr'
	})
	.when('/category/:slug', {
		templateUrl: localized.partials + 'archive.html',
		// controller: 'archiveCtr'
	})
	.otherwise({
		redirectTo: '/'
	});
})
.controller('mainCtr', function($scope, $http, $routeParams) {
	// making rows
	function chunk_data(array, size){
		var dataArr = [];
		for (var i=0; i<array.length; i+=size) {
			dataArr.push(array.slice(i, i+size));
		}
		return dataArr;
	}
	$http({
		url: 'wp-json/wp/v2/posts',
		method:'GET',
 		params: {
			'per_page': 6
		}
	}).then(function(response){
		$scope.posts = chunk_data(response.data, 2);
	});
})
.controller('singlePostCtr', function($scope, $http, $routeParams) {
	$http({
		url: 'wp-json/wp/v2/posts',
		method:'GET',
 		params: {
			'slug': $routeParams.slug
		}
	}).then(function(response){
		$scope.post = response.data[0];
	});
})
.controller('singlePageCtr', function($scope, $http, $routeParams) {
	$http({
		url: 'wp-json/wp/v2/pages',
		method:'GET',
 		params: {
			'slug': $routeParams.slug
		}
	}).then(function(response){
		$scope.page = response.data[0];
		//console.log(response.data);
	});
})
.controller('topMenuCtr', function($scope, $http, $location) {
	// convert URLs to SLUGs
	function changeDataUrls(data) {
		for(var i = 0; i < data.length; i++){
			if(data[i].hasOwnProperty("url")){
				var l = document.createElement("a");
				l.href = data[i].url;
				data[i].url = l.pathname.slice(0, -1);
			}
		}
		return data;
	}

	$scope.isActive = function(item) {
      if (item.url == $location.path()) {
        return true;
      }
      return false;
    }

	$http({
		url: 'wp-json/wp-api-menus/v2/menu-locations/primary',
		method:'GET',
	}).then(function(response){
		$scope.topmenu = changeDataUrls(response.data);
		//console.log($scope.topmenu);
	});
});