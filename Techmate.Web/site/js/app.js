var mySite = angular.module('mySite', ['ngRoute', 'ngSanitize']);

mySite.factory('mySharedService', function ($rootScope, $http, $filter) {
    var shared = {};
    
    shared.magazines = {};
    shared.articles = {};
    shared.languages = ['it', 'en'];
    shared.selectedLanguage = 'it';
    
    shared.changeLanguage = function(lang) {
        shared.selectedLanguage = lang;
        shared.notifyPropertyChanged("selectedLanguage");
    };
    
    shared.getMagazines = function(){
        $http.get('http://localhost:8210/Techmate/api/magazine/all')
        .success(function(data){
            if (angular.isArray(data.Response)) {
                data.Response.forEach(function(m) {
                    shared.magazines[m.number] = m;
                });
                shared.notifyPropertyChanged('magazines');
            } else {
                console.log(data);
            }
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
        $http.post('http://localhost:8210/Techmate/api/magazine/', m)
        .success(function (data) {
            shared.magazines[data.number] = data;
            shared.notifyPropertyChanged('magazines');
            callback(true);
        })
        .error(function (xhr) {
            console.log(xhr);
            callback(false);
        });
    };

    shared.deleteMagazine = function (id) {
        $http.delete('http://localhost:8210/Techmate/api/magazine/' + id)
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
        $http.get('http://localhost:8210/Techmate/api/magazine/publish/' + id)
        .success(function(data) {
            if(data == "true")
                shared.magazines[id].published = true;
            shared.notifyPropertyChanged('magazines');
        })
        .error(function(xhr) {
            console.log(xhr);
        });
    };
    
    shared.getArticles = function(idMagazine){
        if(!(idMagazine in shared.articles)){
            $http.get('http://localhost:8210/Techmate/api/article/' + idMagazine)
            .success(function(data){
                if (angular.isArray(data.Response)) {
                    shared.articles[idMagazine] = data.Response;
                    shared.notifyPropertyChanged('articles');
                } else {
                    console.log(data);
                }
            })
            .error(function(xhr){
                console.log(xhr);
            });
        } else 
            return shared.articles[idMagazine];
    };
    
    shared.getArticle = function(idMagazine, idArticle) {
        return $filter('findByNumber')(shared.articles[idMagazine], idArticle);
    };
    
    shared.saveArticle = function (a, callback) {
        $http.post('http://localhost:8210/Techmate/api/article/', a)
        .success(function (data) {
            console.log(data);
            if(!(data.magazine in shared.articles))
                shared.articles[data.magazine] = [];
            
            var i = $filter('findIndexByNumber')(shared.articles[data.magazine], data.number);
            if(i)
                shared.articles[data.magazine][i] = data;
            else
                shared.articles[data.magazine].push(data);
            
            shared.notifyPropertyChanged('articles');
            callback(true);
        })
        .error(function (xhr) {
            console.log(xhr);
            callback(false);
        });
    };

    // per i messaggi broadcast
    shared.notifyPropertyChanged = function (propertyName) {
        $rootScope.$broadcast(propertyName+'Changed');
    };
    
    return shared;
});

mySite.filter('findByNumber', function () {
    return function (input, number) {
        var i = 0, len = input.length;
        for (; i < len; i++) {
            if (+input[i].number == +number) {
                return input[i];
            }
        }
        return null;
    };
});

mySite.filter('findIndexByNumber', function () {
    return function (input, number) {
        var i = 0, len = input.length;
        for (; i < len; i++) {
            if (+input[i].number == +number) {
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
        .when('/article/new/:idMagazine', {
            controller: 'ArticleCtrl',
            templateUrl: 'views/editor_article.html'
        })
        .when('/article/edit/:idMagazine/:id', {
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
    
    $scope.selectedId = null;
    $scope.articles = null;

    // listener
    $scope.$on('magazinesChanged', function () {
        $scope.magazines = mySharedService.magazines;
    });
    $scope.$on('articlesChanged', function () {
        if($scope.selectedId in mySharedService.articles)
            $scope.articles = mySharedService.articles[$scope.selectedId];
    });
    $scope.$on('selectedLanguageChanged', function () {
        $scope.language = mySharedService.selectedLanguage;
    });
    
    $scope.openDetail = function(id){
        $scope.selectedId = id;
        $scope.articles = mySharedService.getArticles(id);
    };
    
    $scope.deleteMagazine = function(id) {
        mySharedService.deleteMagazine(id);
        $scope.selectedId = null;
    };
    
    $scope.publish = function() {
        mySharedService.publish($scope.selectedId);
    };
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
    if($routeParams.id)
        $scope.article = mySharedService.getArticle($routeParams.idMagazine, $routeParams.id);
    else
        $scope.article = {};
    
    $scope.allowedLanguages = mySharedService.languages;
    $scope.language = mySharedService.selectedLanguage;
    
    $scope.save = function() {
        $scope.article.magazine = $routeParams.idMagazine;
        mySharedService.saveArticle($scope.article, function(result) {
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

function LanguageCtrl ($scope, mySharedService) {
    $scope.languages = mySharedService.languages;
    
    $scope.changeLanguage = function(lang) {
        mySharedService.changeLanguage(lang);
    };
}

HomeCtrl.$inject = ['$scope', 'mySharedService'];
MagazineCtrl.$inject = ['$scope', '$routeParams', 'mySharedService'];
ArticleCtrl.$inject = ['$scope', '$routeParams', 'mySharedService'];

mySite.directive('ckeditor', function() {
    return {
        require : '?ngModel',
        link : function($scope, elm, attr, ngModel) {

            var ck = CKEDITOR.replace(elm[0]);

            ck.on('instanceReady', function() {
                ck.setData(ngModel.$viewValue);
            });

            ck.on('pasteState', function() {
                $scope.$apply(function() {
                    ngModel.$setViewValue(ck.getData());
                });
            });

            ngModel.$render = function(value) {
                ck.setData(ngModel.$modelValue);
            };
        }
    };
});