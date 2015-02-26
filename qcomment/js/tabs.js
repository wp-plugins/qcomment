QComment
    .directive('qcTabHead', ['$rootScope', function($rootScope) {
        return {
            restrict: 'EA',
            link: function(scope, element, attrs) {
                var attrs = attrs;
                var element = element;

                element.on('click', function(event) {
                    element.addClass('nav-tab-active');
                    $rootScope.$broadcast('qcomment:tab:change', attrs.rel);
                    event.preventDefault();
                });

                $rootScope.$on('qcomment:tab:change', function(event, data) {
                    if (attrs.rel !== data) {
                        element.removeClass('nav-tab-active');
                    }
                });
            }
        }
    }])
    .directive('qcTab', ['$rootScope', function($rootScope) {
        return {
            restrict: 'EA',
            link: function(scope, element, attrs) {
                var attrs = attrs;
                var element = element;

                $rootScope.$on('qcomment:tab:change', function(event, data) {
                    if (attrs.id !== data) {
                        element.addClass('hidden');
                    }
                    else {
                        element.removeClass('hidden');
                    }
                });
            }
        }
    }]);