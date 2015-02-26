QComment.controller('Comments', ['$rootScope', '$scope', 'API'
    , function($rootScope, $scope, API){
        $scope.loadingComments = false;
        $scope.decliningComment = false;
        $scope.showDeclineError = false;
        $scope.showAcceptError = false;
        $scope.status = {};
        $scope.comments = [];

        $rootScope.$on('qcomment:project:status', function(event, data) {
            if (data.project_id) {
                $scope.loadingComments = true;
                API.getComments();
            }
        });

        $rootScope.$on('qcomment:comments:get', function(event, data) {
            $scope.loadingComments = false;

            if (data.error_code) {
                $scope.status = data;
            }
            $scope.comments = data;
        });

        $scope.accept = function (id) {
            $scope.selectedComment = id;
            $rootScope.$broadcast('qcomment:comments:accepting', id);
            var currentComment = _.find($scope.comments, function(comment) {
                return $scope.selectedComment == comment.id;
            });
            API.acceptComment(id, currentComment);
        };

        $scope.$on('qcomment:comments:accept', function(event, data) {
            _.each($scope.comments, function(element, index, list) {
                if (data.status != 'no' && element.id == data.comment_id) {
                    element.status = data.status;
                }
            });
        });

        $scope.decline = function() {
            $scope.decliningComment = true;
            $scope.showDeclineError = false;
            API.declineComment($scope.selectedComment, $scope.refuseReason, $scope.rewriteComment);
        };

        $scope.$on('qcomment:comments:decline', function(event, data) {
            $scope.decliningComment = false;
            if (data.error_code) {
                $scope.showDeclineError = true;
                $scope.reason = data.reason;
            }
            else {
                _.each($scope.comments, function(element, index, list) {
                    if (element.id == data.comment_id) {
                        element.status = data.status;
                    }
                });
                $rootScope.$broadcast('qcomment:dialog:close');
            }
        });

        $scope.showDeclineDialog = function(id) {
            $scope.selectedComment = id;
            $scope.showDeclineError = false;
            $rootScope.$broadcast('qcomment:dialog:open');
        }
    }
]);