var mySite = angular.module('mySite', ['ngRoute']);

mySite.factory('mySharedService', function ($rootScope, $http, $filter) {
    var shared = {};
    
    shared.magazines = [];
    shared.languages = ['it', 'en', 'fr', 'es', 'de'];
    
    shared.selectedLanguage = 'it';
    
    shared.getMagazines = function(){
        $http.get('http://127.0.0.1:8210/Techmate/api/magazine')
        .success(function(data){
            shared.magazines = data;
            shared.notifyPropertyChanged('magazines');
        })
        .error(function(xhr){
            console.log(xhr);
        });
    	return shared.magazines;
    };

    shared.getMagazine = function (id) {
        return $filter('getById')(shared.magazines, id);
    };

    shared.saveMagazine = function (m) {
        $http.post('http://127.0.0.1:8210/Techmate/api/magazine/', m)
        .success(function (data) {
            shared.magazines.push(data);
            shared.notifyPropertyChanged('magazines');
        })
        .error(function (xhr) {
            console.log(xhr);
        });
    };

    shared.deleteMagazine = function (id) {
        $http.delete('http://127.0.0.1:8210/Techmate/api/magazine/' + id)
        .success(function (data) {
            index = $filter('getIndexById')(shared.magazines, id);
            shared.magazines.splice(index, 1);
            shared.updateMagazines();
        })
        .error(function(xhr) {
            console.log(xhr);
        });
    };

    // per i messaggi broadcast
    shared.notifyPropertyChanged = function (propertyName) {
        $rootScope.$broadcast(propertyName+'Changed');
    };
    
    return shared;
});

mySite.filter('getById', function () {
    return function (input, id) {
        var i = 0, len = input.length;
        for (; i < len; i++) {
            if (+input[i].id == +id) {
                return input[i];
            }
        }
        return null;
    };
});
mySite.filter('getIndexById', function () {
    return function (input, id) {
        var i = 0, len = input.length;
        for (; i < len; i++) {
            if (+input[i].id == +id) {
                return i;
            }
        }
        return null;
    };
});

mySite.config(function ($routeProvider) {
    $routeProvider
    	.when('/', {
            controller: 'HomeCtrl',
    	    templateUrl: 'views/home.html'
    	})
        .when('/magazine/new', {
            controller: 'MagazineCtrl',
            templateUrl: 'views/editor_magazine.html'
        })
        .when('/magazine/edit/:id', {
            controller: 'MagazineCtrl',
            templateUrl: 'views/editor_magazine.html'
        })
        .when('/article/new', {
            controller: 'ArticleCtrl',
            templateUrl: 'views/editor_article.html'
        })
        .when('/article/edit/:id', {
            controller: 'ArticleCtrl',
            templateUrl: 'views/editor_article.html'
        })
        .when('/pushnotifications', {
            templateUrl: 'views/push_notifications.html'
        })
    	.otherwise({
    	    redirectTo: '/'
    	});
});


function HomeCtrl($scope, mySharedService) {
    $scope.magazines = mySharedService.getMagazines();
    $scope.language = mySharedService.selectedLanguage;
    $scope.selectedMagazine;

    // listener
    $scope.$on('magazinesChanged', function () {
        $scope.magazines = mySharedService.magazines;
    });
    
    $scope.openDetail = function(id){
        $scope.selectedMagazine = mySharedService.getMagazine(id);
    };
}

function MagazineCtrl ($scope, $routeParams, mySharedService) {
     if ($routeParams.id)
        $scope.magazine = mySharedService.getMagazine($routeParams.id);
    else
        $scope.magazine = {};
    
    $scope.save = function() {
        mySharedService.saveMagazine($scope.magazine);
    };
}

function ArticleCtrl ($scope, $routeParams, mySharedService) {

}

HomeCtrl.$inject = ['$scope', 'mySharedService'];
MagazineCtrl.$inject = ['$scope', '$routeParams', 'mySharedService'];
ArticleCtrl.$inject = ['$scope', '$routeParams', 'mySharedService'];