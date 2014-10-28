var mySite = angular.module('mySite', ['ngRoute']);

mySite.factory('mySharedService', function ($rootScope, $http, $filter) {
    var shared = {};
    
    shared.magazines = {};
    shared.languages = ['it', 'en', 'fr', 'es', 'de'];
    shared.selectedLanguage = 'it';
    
    shared.getMagazines = function(){
        $http.get('http://127.0.0.1:8210/Techmate/api/magazine/all')
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
        return shared.magazines[id];
    };

    shared.saveMagazine = function (m, callback) {
        $http.post('http://127.0.0.1:8210/Techmate/api/magazine/', m)
        .success(function (data) {
            shared.magazines[data._id.$id] = data;
            shared.notifyPropertyChanged('magazines');
            callback(true);
        })
        .error(function (xhr) {
            console.log(xhr);
            callback(false);
        });
    };

    shared.deleteMagazine = function (id) {
        $http.delete('http://127.0.0.1:8210/Techmate/api/magazine/' + id)
        .success(function (data) {
            if(data == "true")
                delete shared.magazines[id];
            
            shared.notifyPropertyChanged('magazines');
        })
        .error(function(xhr) {
            console.log(xhr);
        });
    };
    
    shared.publish = function(id) {
        $http.get('http://127.0.0.1:8210/Techmate/api/magazine/publish/' + id)
        .success(function(data) {
            if(data == "true")
                shared.magazines[id].published = true;
            shared.notifyPropertyChanged('magazines');
        })
        .error(function(xhr) {
            console.log(xhr);
        });
    }

    // per i messaggi broadcast
    shared.notifyPropertyChanged = function (propertyName) {
        $rootScope.$broadcast(propertyName+'Changed');
    };
    
    return shared;
});

mySite.filter('getByNumber', function () {
    return function (input, id) {
        var i = 0, len = input.length;
        for (; i < len; i++) {
            if (+input[i].number == +id) {
                return input[i];
            }
        }
        return null;
    };
});
mySite.filter('getIndexByNumber', function () {
    return function (input, id) {
        var i = 0, len = input.length;
        for (; i < len; i++) {
            if (+input[i].number == +id) {
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
    $scope.selectedId;

    // listener
    $scope.$on('magazinesChanged', function () {
        $scope.magazines = mySharedService.magazines;
    });
    
    $scope.openDetail = function(id){
        $scope.selectedId = id;
    };
    
    $scope.deleteMagazine = function(id) {
        mySharedService.deleteMagazine(id);
        $scope.selectedId = null;
    };
    
    $scope.publish = function() {
        mySharedService.publish($scope.selectedId);
    }
}

function MagazineCtrl ($scope, $routeParams, mySharedService) {
     if ($routeParams.id)
        $scope.magazine = mySharedService.getMagazine($routeParams.id);
    else
        $scope.magazine = {};
    
    $scope.allowedLanguages = mySharedService.languages;
    $scope.language = mySharedService.selectedLanguage;
    
    $scope.save = function() {
        mySharedService.saveMagazine($scope.magazine, function(result) {
            if(result) {
                $("#box_result").html("<p>Saved</p>");
                $("#box_result").addClass("alert-success");
                $("#box_result").removeClass("hidden");
            } else {
                $("#box_result").html("<p>An error occured</p>");
                $("#box_result").addClass("alert-danger");
                $("#box_result").removeClass("hidden");
            }
        });
    };
}

function ArticleCtrl ($scope, $routeParams, mySharedService) {

}

HomeCtrl.$inject = ['$scope', 'mySharedService'];
MagazineCtrl.$inject = ['$scope', '$routeParams', 'mySharedService'];
ArticleCtrl.$inject = ['$scope', '$routeParams', 'mySharedService'];
