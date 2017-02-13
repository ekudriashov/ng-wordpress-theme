angular.module('ngWordpress', ['ngRoute', 'ngSanitize'])
.config(function($routeProvider, $locationProvider) {
	$locationProvider.html5Mode({
		enabled: true
	});
	$routeProvider
	.when('/', {
		templateUrl: localized.partials + 'posts-main.html',
		controller: 'postsMain'
	})
	.when('/:slug', {
		templateUrl: localized.partials + 'single-post.html',
		controller: 'singlePost'
	})
	.otherwise({
		redirectTo: '/'
	});
})
.controller('postsMain', function($scope, $http, $routeParams) {
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
.controller('singlePost', function($scope, $http, $routeParams) {
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
.controller('topMenu', function($scope, $http){
	$http({
		url: 'wp-json/wp-api-menus/v2/menu-locations/primary',
		method:'GET',
	}).then(function(response){
		$scope.topmenu = response.data;
	});
});