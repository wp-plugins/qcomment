<?php
global $qcomment_data;
?>

<div ng-controller="Project" ng-cloak>

    <div ng-show="noAPIKey">
        <?php
        _e( 'Set QComment API key, please', 'qcomment' );
        echo ' <a href="' . admin_url( 'admin.php?page=qcomment' ) . '">' . __( 'Options page', 'qcomment' ) . '</a>';
        ?>
    </div>

    <div ng-show="view == 'waiting'">
        <span class="spinner" style="display: inline;"></span>
        <p>Загрузка данных ...</p>
    </div>

    <div ng-show="view == 'project'">
        <p ng-show="project_id > 0">Проект ID {{ project_id }}</p>
        <div class="qcomment_error" ng-show="error_code > 1 && status != 'no'">{{ reason }}</div>
        <div ng-show="error_code == 403">
            <p><input type="button" class="button" value="Оплатить" ng-click="showBuyComments()"></p>
            <div ng-show="buyCommentsFormVisible">
                <label>Количество комментариев: <input type="text" ng-model="buyCommentsCount"></label><br>
                <label>Автоматическая проверка новых комментариев <input type="checkbox" ng-model="autoCheckComments"></label>
                <hr>
                <div>
                    <input type="button" class="button button-primary" value="Оплатить комментарии" ng-click="buyComments()">
                    <span class="spinner" ng-show="buyingComments" style="display: inline;"></span>
                </div>
                <div class="clear"></div>
                <div class="qcomment_error" ng-show="showBuyCommentsError">{{ buyCommentsError }}</div>
            </div>
        </div>
        <div ng-show="status == 2">
            <span class="spinner" ng-show="activatingProject" style="display: inline;"></span>
            <input type="button" ng-click="activateProject()" class="button" value="Запустить">
            <div class="qcomment_error" ng-show="showActivateProjectError">{{ activateProjectError }}</div>
        </div>
        <div ng-show="status == 1">
            <span class="spinner" ng-show="deactivatingProject" style="display: inline;"></span>
            <input type="button" ng-click="deactivateProject()" class="button" value="Приостановить">
            <div class="qcomment_error" ng-show="showDeactivateProjectError">{{ deactivateProjectError }}</div>
        </div>
    </div>

    <div ng-show="error_code == 401 || status == 'no'">
        <a class="button button-primary" ng-click="showCreateProject()"><?php _e( 'Buy comments', 'qcomment' ); ?></a>

        <div ng-show="createProjectFormVisible" id="qcomment_form">
            <h2 class="nav-tab-wrapper">
                <a href="#" qc-tab-head class="nav-tab nav-tab-active" rel="qcomment_main_settings"><?php _e( 'Main', 'qcomment' ); ?></a>
                <a href="#" qc-tab-head class="nav-tab" rel="qcomment_additional_settings"><?php _e( 'Additional', 'qcomment' ); ?></a>
                <a href="#" qc-tab-head class="nav-tab" rel="qcomment_restrictions_settings"><?php _e( 'Restrictions', 'qcomment' ); ?></a>
            </h2>
            <div id="qcomment_main_settings" class="settings_tab" qc-tab>
                <table>
                    <tr>
                        <th>Заголовок</th>
                        <td><input type="text" ng-model="title" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>URL</th>
                        <td><input type="text" ng-model="url" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>Тематика</th>
                        <td>
                            <select id="qcomment_subjects" ng-model="subject">
                                <?php foreach ($qcomment_data['subjects'] as $id => $value) { ?>
                                    <option value="<?php echo $id; ?>" <?php echo selected( $subject, $id, false ); ?>><?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Тариф</th>
                        <td>
                            <select id="qcomment_tarif_id" ng-model="tarif_id">
                                <?php foreach ($tariff as $value) { ?>
                                    <option value="<?php echo $value['id']; ?>"><?php echo $value['name']
                                            . ' (' . $value['symbols'] . ') ' . $value['price']; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Бонус</th>
                        <td><input type="text" ng-model="bonus"" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>Задание авторам</th>
                        <td><textarea ng-model="task"><?php echo $task; ?></textarea></td>
                    </tr>
                </table>
            </div>
            <div id="qcomment_additional_settings" class="settings_tab hidden" qc-tab>
                <table>
                    <tr>
                        <th>ID группы проектов</th>
                        <td><input type="text" ng-model="group_id"" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>Язык</th>
                        <td>
                            <select id="qcomment_language" ng-model="language">
                                <?php foreach ($qcomment_data['languages'] as $id => $value) { ?>
                                    <option value="<?php echo $id; ?>" <?php echo selected( $language, $id ); ?>><?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Минимальный ранг автора</th>
                        <td>
                            <select id="qcomment_min_rating" ng-model="min_rating">
                                <?php foreach ($qcomment_data['author_rating'] as $id => $value) { ?>
                                    <option value="<?php echo $id; ?>" <?php echo selected( $min_rating, $id ); ?>><?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>ID команды авторов</th>
                        <td>
                            <input type="text" ng-model="team_id" class="regular-text">
                            <p class="description"><?php _e( 'View in', 'qcomment' ) ?>
                                <a href="http://qcomment.ru/user/teams" target="_blank">
                                    <?php _e( 'your teams list', 'qcomment' ); ?>
                                </a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th>Типы комментариев</th>
                        <td>
                            <?php foreach ($qcomment_data['comment_configs'] as $id => $value) { ?>
                                <?php
                                $checked = '';
                                if ( in_array( $id, $comment_configs ) ) {
                                    $checked = 'checked="checked"';
                                }
                                ?>
                                <label>
                                    <input name="comment_configs[]" type="checkbox" <?php echo $checked; ?>
                                           ng-click="toggleCommentConfigs(<?php echo $id; ?>)">
                                    <?php echo $value; ?>
                                </label><br>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Отложенный запуск проекта</th>
                        <td><input type="text" ng-model="start_time" class="regular-text" placeholder="2014-03-21 10:00"></td>
                    </tr>
                    <tr>
                        <th>Время, в которое проект будет приостановлен</th>
                        <td><input type="text" ng-model="end_time" class="regular-text" placeholder="2014-03-22 19:30"></td>
                    </tr>
                </table>
            </div>
            <div id="qcomment_restrictions_settings" class="settings_tab hidden" qc-tab>
                <table>
                    <tr>
                        <th>Лимит комментариев в сутки</th>
                        <td><input type="text" ng-model="limit" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>Случайное число в сутки</th>
                        <td>
                            <input type="text" ng-model="min_day_limit "> -
                            <input type="text" ng-model="max_day_limit">
                        </td>
                    </tr>
                    <tr>
                        <th>Лимит комментариев в час</th>
                        <td><input type="text" ng-model="limit_hour" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>Лимит от автора</th>
                        <td><input type="text" ng-model="limit_author" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>Лимит от автора в сутки</th>
                        <td><input type="text" ng-model="limit_author_day" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>Лимит комментариев на страницу.</th>
                        <td><input type="text" ng-model="limit_url" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>Лимит комментариев на страницу в сутки.</th>
                        <td><input type="text" ng-model="limit_url_day" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>Максимум приступивших авторов</th>
                        <td><input type="text" ng-model="max_turn" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th>Стоп слова</th>
                        <td><input type="text" ng-model="stop_words" class="regular-text"></td>
                    </tr>
                </table>
            </div>

            <hr>
            <div>
                <input type="button" ng-click="createProject()" class="button button-primary" value="Создать проект">
                <span class="spinner" ng-show="creatingProject" style="display: inline;"></span>
            </div>
            <div class="clear"></div>
            <div class="qcomment_error" ng-show="showCreateProjectError">{{ createProjectError }}</div>
        </div>
    </div>
</div>
