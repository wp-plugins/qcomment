QComment.directive('qcCommentButtons', ['$rootScope', function($rootScope) {
    return {
        restrict: 'EA',
        link: function(scope, element, attrs) {
            var attrs = attrs;
            var element = element;
            var commentId = attrs.commentId;
            var spinner = jQuery(element).find('.spinner');
            var errorMes = jQuery(element).find('.qcomment_error');
            spinner.css('display', 'none');

            $rootScope.$on('qcomment:comments:accepting', function(event, data) {
                errorMes.css('display', 'none');
                if (data == commentId) {
                    spinner.css('display', 'inline');
                }
            });

            $rootScope.$on('qcomment:comments:accept', function(event, data) {
                if (data.comment_id == commentId) {
                    spinner.css('display', 'none');
                    if (data.status == 'no') {
                        errorMes.html(data.reason);
                        errorMes.css('display', 'block');
                    }
                }
            });
        }
    }
}]);