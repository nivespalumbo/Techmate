<!DOCTYPE html>
<html lang="it">
    <head>
        <title>Techmate</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
        
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" >
        <link rel="stylesheet" href="style/bootstrap.min.css">
        
        <link rel="stylesheet" href="style/dashboard.css">
    </head>
    <body ng-app="mySite">
        <div class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" ng-href="#">Techmate</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a ng-href="#/pushnotifications">Push notifications</a></li>
                        <li><a ng-href="#/sponsors">Sponsors</a></li>
                    </ul>
                    <form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                <input type="text" class="form-control" placeholder="Cerca">
                            </div>
                        </div>
                    </form>
                    <div class="nav navbar-right" ng-controller="LanguageCtrl">
                        <button type="button" ng-repeat="l in languages" ng-click="changeLanguage(l)" class="btn btn-default navbar-btn" >{{l}}</button>
                    </div>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </div>
        <div ng-view></div>
        
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.24/angular.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.24/angular-route.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.24/angular-sanitize.js"></script>
        <script src="https://code.jquery.com/jquery-1.11.0.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
        <script src="http://cdn.ckeditor.com/4.4.6/basic/ckeditor.js"></script>
        
        <script src="js/app.js"></script>
    </body>
</html>
