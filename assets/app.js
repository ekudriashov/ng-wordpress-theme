var app = angular.module('ngWordpress', ['ngRoute', 'ngSanitize']);

app.config(function($routeProvider, $locationProvider) {
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
		controller: 'archiveCtr'
	})
	.otherwise({
		redirectTo: '/'
	});
});

app.controller('mainCtr', function(ngwpService, $scope, $routeParams) {
	// making rows
	function chunk_data(array, size){
		var dataArr = [];
		for (var i=0; i<array.length; i+=size) {
			dataArr.push(array.slice(i, i+size));
		}
		return dataArr;
	}
	// Posts list data
	ngwpService.getPosts({per_page: 6}).then(function(resp) {
		$scope.posts = chunk_data(resp.data, 2);
		// console.log(resp);
	});
});

app.controller('singlePostCtr', function(ngwpService, $scope, $routeParams) {
	ngwpService.getPosts({slug: $routeParams.slug}).then(function(resp) {
		$scope.post = resp.data[0];
		// console.log($scope.post);
	});
});

app.controller('singlePageCtr', function(ngwpService, $scope, $routeParams) {
	ngwpService.pagesList().then(function(resp) {
		$scope.page = resp.filter(function(entry){
			return entry.slug === $routeParams.slug;
		})[0];
		// console.log($scope.page);
	});
});

app.controller('archiveCtr', function(ngwpService, $scope, $routeParams) {
	var current_category;
	function chunk_data(array, size){
		var dataArr = [];
		for (var i=0; i<array.length; i+=size) {
			dataArr.push(array.slice(i, i+size));
		}
		return dataArr;
	}

	ngwpService.categoriesList().then(function(resp) {
		current_category = resp.filter(function(entry){
			//take care of all slugs, even setted with weird languages (like russian)
			return entry.slug === angular.lowercase(encodeURI($routeParams.slug));
		})[0];
		// console.log($routeParams.slug);
		//console.log(current_category);
		$scope.category = current_category;
		ngwpService.getPosts({categories: current_category.id, per_page: 6}).then(function(resp) {
			$scope.posts = chunk_data(resp.data, 2);
			// console.log(resp);
		});
	});
});

app.controller('topMenuCtr', function(ngwpService, $scope, $location) {
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
		if (decodeURI(item.url) == $location.path()) {
			return true;
		}
		return false;
	}
	ngwpService.menuList().then(function(response){
		$scope.topmenu = changeDataUrls(response);
		// console.log($scope.topmenu);
	});
});