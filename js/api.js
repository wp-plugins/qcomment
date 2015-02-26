QComment.factory('API', ['$rootScope', '$http', 'projectDefaults', function($rootScope, $http, projectDefaults) {
    var projectData = {};

    var API = {};

    API.getProjectData = function() {
        return projectData;
    }

    API.getStatus = function() {
        data = jQuery.param({
            project_id: projectDefaults.project_id,
            action: 'qcomment_get_project_status'
        });

        $http({
                method: 'POST',
                url: ajaxurl,
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            success(function(data, status, headers, config) {
                projectData = data;
                $rootScope.$broadcast('qcomment:project:status', data);
            }).
            error(function(data, status, headers, config) {
                $rootScope.$broadcast('qcomment:project:error', data);
            });
    }

    API.createProject = function(data) {
        data.action = 'qcomment_save_project';
        var requestParams = jQuery.param(data);
        $http({
                method: 'POST',
                url: ajaxurl,
                data: requestParams,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            success(function(data, status, headers, config) {
                $rootScope.$broadcast('qcomment:project:create', data);
            }).
            error(function(data, status, headers, config) {
                $rootScope.$broadcast('qcomment:project:error', data);
            });
    }

    API.buyComments = function(buyCommentsData) {
        buyCommentsData.action = 'qcomment_buy_comments';
        buyCommentsData.post_id = projectDefaults.post_id;
        data = jQuery.param(buyCommentsData);

        $http({
                method: 'POST',
                url: ajaxurl,
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            success(function(data, status, headers, config) {
                $rootScope.$broadcast('qcomment:project:buyComments', data);
            }).
            error(function(data, status, headers, config) {
                $rootScope.$broadcast('qcomment:project:error', data);
            });
    }

    API.activateProject = function(projectData) {
        projectData.action = 'qcomment_activate_project';
        data = jQuery.param(projectData);

        $http({
                method: 'POST',
                url: ajaxurl,
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            success(function(data, status, headers, config) {
                $rootScope.$broadcast('qcomment:project:activate', data);
            }).
            error(function(data, status, headers, config) {
                $rootScope.$broadcast('qcomment:project:error', data);
            });
    }

    API.deactivateProject = function(projectData) {
        projectData.action = 'qcomment_deactivate_project';
        data = jQuery.param(projectData);

        $http({
                method: 'POST',
                url: ajaxurl,
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            success(function(data, status, headers, config) {
                $rootScope.$broadcast('qcomment:project:activate', data);
            }).
            error(function(data, status, headers, config) {
                $rootScope.$broadcast('qcomment:project:error', data);
            });
    }

    API.getComments = function() {
        projectData.action = 'qcomment_get_comments';
        data = jQuery.param({
            action: projectData.action,
            project_id: projectData.project_id
        });

        $http({
                method: 'POST',
                url: ajaxurl,
                data: data,
                headers: {'Content-Type': 'application/x-www-form-urlencoded'}
            }).
            success(function(data, status, headers, config) {
                $rootScope.$broadcast('qcomment:comments:get', data);
            }).
            error(function(data, status, headers, config) {
                $rootScope.$broadcast('qcomment:comments:error', data);
            });
    }

    API.acceptComment = function(id, currentComment) {
        var commentId = id;
        projectData.action = 'qcomment_accept_comment';
        data = jQuery.param({
            action: projectData.action,
            project_id: projectData.project_id,
            comment_id: id,
            comment_data: currentComment,
            post_id: projectDefaults.post_id
        });

        $http({
            method: 'POST',
            url: ajaxurl,
            data: data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).
            success(function(data, status, headers, config) {
                if (data.status == 'no') {
                    data.comment_id = commentId;
                }
                $rootScope.$broadcast('qcomment:comments:accept', data);
            }).
            error(function(data, status, headers, config) {
                data.comment_id = commentId;
                $rootScope.$broadcast('qcomment:comments:error', data);
            });
    }

    API.declineComment = function(id, reason, rewrite) {
        projectData.action = 'qcomment_decline_comment';
        data = jQuery.param({
            action: projectData.action,
            project_id: projectData.project_id,
            comment_id: id,
            reason: reason,
            rewrite: rewrite
        });

        $http({
            method: 'POST',
            url: ajaxurl,
            data: data,
            headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        }).
            success(function(data, status, headers, config) {
                $rootScope.$broadcast('qcomment:comments:decline', data);
            }).
            error(function(data, status, headers, config) {
                $rootScope.$broadcast('qcomment:comments:error', data);
            });
    }

    return API;
}]);