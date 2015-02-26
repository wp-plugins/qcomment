QComment.controller('Project', ['$rootScope', '$scope', 'projectDefaults', 'API'
        , function($rootScope, $scope, projectDefaults, API){
    angular.extend($scope, projectDefaults);

    $scope.view = 'waiting';
    $scope.autoCheckComments = false;

    if ($scope.app_key.length > 0) {
        $scope.noAPIKey = false;

        API.getStatus();

        $rootScope.$on('qcomment:project:status', function(event, data) {
            $scope.view = 'project';
            angular.extend($scope, data);
        });

        $scope.buyCommentsFormVisible = false;

        $scope.showBuyComments = function() {
            $scope.buyCommentsFormVisible = !$scope.buyCommentsFormVisible;
        }

        $scope.buyComments = function() {
            $scope.buyingComments = true;
            $scope.showBuyCommentsError = false;
            API.buyComments({
                comments_count: $scope.buyCommentsCount,
                project_id: $scope.project_id,
                auto_check_comments: $scope.autoCheckComments
            });
        }

        $rootScope.$on('qcomment:project:buyComments', function(event, data) {
            $scope.buyingComments = false;

            if (data.status == 'ok') {
                $scope.error_code = 0;
                $scope.status = 1;
            }
            else {
                $scope.showBuyCommentsError = true;
                $scope.buyCommentsError = data.reason;
            }
        });

        $scope.activateProject = function() {
            $scope.activatingProject = true;
            $scope.showActivateProjectError = false;

            API.activateProject({
                project_id: $scope.project_id
            });
        }

        $rootScope.$on('qcomment:project:activate', function(event, data) {
            $scope.activatingProject = false;
            $scope.deactivatingProject = false;

            if (data.error_code) {
                $scope.showActivateProjectError = true;
                $scope.activateProjectError = data.reason;
            }
            else {
                $scope.status = data.status;
            }
        });

        $scope.deactivateProject = function() {
            $scope.deactivatingProject = true;
            $scope.showDeactivateProjectError = false;

            API.deactivateProject({
                project_id: $scope.project_id
            });
        }

        $rootScope.$on('qcomment:project:deactivate', function(event, data) {
            $scope.deactivatingProject = false;
            $scope.activatingProject = false;

            if (data.error_code) {
                $scope.showDeactivateProjectError = true;
                $scope.deactivateProjectError = data.reason;
            }
            else {
                $scope.status = data.status;
            }
        });

        $scope.createProjectFormVisible = false;

        $scope.showCreateProject = function() {
            $scope.createProjectFormVisible = !$scope.createProjectFormVisible;
        }

        $scope.toggleCommentConfigs = function(id) {
            var index = _.indexOf($scope.comment_configs, String(id));
            if (index > -1) {
                $scope.comment_configs.splice(index, 1);
            }
            else {
                $scope.comment_configs.push(String(id));
            }
        }

        $scope.creatingProject = false;

        $scope.createProject = function() {

            $scope.creatingProject = true;
            $scope.showCreateProjectError = false;
            var data = {
                post_id: $scope.post_id,
                title: $scope.title,
                url: $scope.url,
                subjects: $scope.subject,
                tarif_id: $scope.tarif_id,
                bonus: $scope.bonus,
                task: $scope.task,
                group_id: $scope.group_id,
                language: $scope.language,
                min_rating: $scope.min_rating,
                team_id: $scope.team_id,
                comment_configs: $scope.comment_configs,
                start_time: $scope.start_time,
                end_time: $scope.end_time,
                limit: $scope.limit,
                min_day_limit: $scope.min_day_limit,
                max_day_limit: $scope.max_day_limit,
                limit_hour: $scope.limit_hour,
                limit_url: $scope.limit_url,
                limit_url_day: $scope.limit_url_day,
                limit_author: $scope.limit_author,
                limit_author_day: $scope.limit_author_day,
                max_turn: $scope.max_turn,
                stop_words: $scope.stop_words
            };
            API.createProject(data);
        }

        $rootScope.$on('qcomment:project:create', function(event, data) {
            $scope.creatingProject = false;

            if (data.status == 'no') {
                $scope.showCreateProjectError = true;
                $scope.createProjectError = data.reason;
            }
            else {
                $scope.status = data.status;
                $scope.reason = 'По проекту нет оплаченных комментариев.';
                $scope.error_code = 403;
                $scope.project_id = data.project_id;
            }
        });
    }
    else {
        $scope.noAPIKey = true;
    }
}]);