app.factory('ngwpService', ['$http', function($http){

	var ngwpService = {};
	//base url
	var urlBase = 'wp-json/wp/v2/';
	// for menus temporaly solution
	var urlMenus = 'wp-json/wp-api-menus/v2/menu-locations/primary';

	function _makingRoute(route) {
		return urlBase + route;
	}

	ngwpService.getPosts =  function(params) {
		return $http.get(_makingRoute('posts'), { params: params });
	}

	ngwpService.getCategories = $http.get(_makingRoute('categories')).then(function(response){
		return response.data;
	});

    ngwpService.getPages = $http.get(_makingRoute('pages')).then(function(response){
    	return response.data;
	});

	ngwpService.getMenus = $http.get(urlMenus).then(function(response){
    	return response.data;
	});

	ngwpService.categoriesList = function(){
		return ngwpService.getCategories;
	};
	ngwpService.pagesList = function(){
		return ngwpService.getPages;
	};
	ngwpService.menuList = function(){
		return ngwpService.getMenus;
	};
	return ngwpService;
}]);