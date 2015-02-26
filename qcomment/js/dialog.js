QComment.directive('qcDialog', ['$rootScope', function($rootScope) {
    return {
        restrict: 'EA',
        link: function(scope, element, attrs) {
            var attrs = attrs;
            var element = element;

            jQuery(element).dialog({
                dialogClass: 'decline-comment-dialog',
                closeOnEscape: true,
                autoOpen: false,
                width: 500
            });

            $rootScope.$on('qcomment:dialog:open', function() {
                jQuery(element).dialog('open');
            });

            $rootScope.$on('qcomment:dialog:close', function() {
                jQuery(element).dialog('close');
            });
        }
    }
}]);