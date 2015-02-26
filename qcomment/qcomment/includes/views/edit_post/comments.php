<hr>
<div ng-controller="Comments" ng-cloak>
    <div ng-show="loadingComments">
        <span class="spinner" style="display: inline;"></span>
        <p>Загрузка комментариев ...</p>
    </div>
    <div ng-show="status.error_code > 500">{{ status.reason }}</div>

    <table class="qc_comments_table">
        <tr ng-repeat="comment in comments">
            <td class="qc_comment_content">
                <p>ID <a href="{{ comment.url }}" target="_blank">{{ comment.id }}</a></p>
                <p>Автор: <span class="qc_data">{{ comment.name }}</span>;
                    дата: <span class="qc_data">{{ comment.date }}</span></p>
                <p>{{ comment.message }}</p>
                <p>Уникальность: <span class="qc_data">{{ comment.uniq }}%</span></p>
                <p ng-show="comment.robot_find == 0" class="qc_alert qc_hint">Комментарий не найден нашим роботом</p>
                <p ng-show="comment.robot_find == 1" class="qc_data qc_hint">Комментарий найден на сайте</p>
            </td>
            <td class="qc_comment_buttons" qc-comment-buttons data-comment-id="{{ comment.id }}">
                <p ng-show="comment.status == 0">
                    <span class="spinner" style="display: inline;"></span>
                    <input type="button" class="button button-primary" ng-click="accept(comment.id)" value="Принять">
                </p>
                <p ng-show="comment.status == 0">
                    <input type="button" class="button button" ng-click="showDeclineDialog(comment.id)" value="Отклонить">
                </p>
                <p ng-show="comment.status == 1" class="qc_data">оплачен</p>
                <p ng-show="comment.status == 2" class="qc_data">отклонен</p>
                <p ng-show="comment.status == 3" class="qc_data">отправлен на доработку</p>
                <p ng-show="comment.status == 4" class="qc_data">заказчик одобрил, ожидается публикация</p>
                <p class="qcomment_error"></p>
            </td>
        </tr>
    </table>

    <div qc-dialog title="Отклонить комментарий" ng-cloak>
        <p>Причина отказа</p>
        <p><textarea ng-model="refuseReason" cols="30" rows="10"></textarea></p>
        <p><label><input type="checkbox" ng-model="rewriteComment"> отправить на доработку</label></p>
        <p>
            <span class="spinner" style="display: inline;" ng-show="decliningComment"></span>
            <input type="button" ng-click="decline()" class="button" value="Отклонить">
        </p>
        <p class="qcomment_error" ng-show="showDeclineError">{{ reason }}</p>
    </div>
</div>