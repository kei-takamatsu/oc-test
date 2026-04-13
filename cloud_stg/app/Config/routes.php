<?php
/**
 * Routes configuration
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 * PHP 5
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
Router::connect('/admin', array(
        'controller' => 'users', 'action' => 'login', 'admin' => true, 'plugin' => 'LogiAuth'
));
Router::connect('/admin/logout', array(
        'controller' => 'users', 'action' => 'logout', 'admin' => true, 'plugin' => 'LogiAuth'
));
Router::connect('/', array(
        'controller' => 'base', 'action' => 'index'
));
Router::connect('/pages/*', array(
        'controller' => 'pages', 'action' => 'display'
));
Router::connect('/about', array(
        'controller' => 'pages', 'action' => 'display', 'about'
));
Router::connect('/help', array(
        'controller' => 'pages', 'action' => 'display', 'help'
));
Router::connect('/tokutei', array(
        'controller' => 'pages', 'action' => 'display', 'tokutei'
));
Router::connect('/policy', array(
        'controller' => 'pages', 'action' => 'display', 'policy'
));
Router::connect('/company', array(
        'controller' => 'pages', 'action' => 'display', 'company'
));
Router::connect('/rule', array(
        'controller' => 'pages', 'action' => 'display', 'rule'
));
Router::connect('/point', array(
        'controller' => 'pages', 'action' => 'display', 'point'
));
Router::connect('/mypage', array(
        'controller' => 'users', 'action' => 'mypage'
));
Router::connect('/profile/*', array(
        'controller' => 'users', 'action' => 'view'
));
Router::connect('/make', array(
        'controller' => 'projects', 'action' => 'add'
));
//LogiAuth
Router::connect('/login', array(
        'controller' => 'users', 'action' => 'login', 'plugin' => 'LogiAuth'
));
Router::connect('/logout', array(
        'controller' => 'users', 'action' => 'logout', 'plugin' => 'LogiAuth'
));
Router::connect('/forgot_pass', array(
        'controller' => 'users', 'action' => 'forgot_pass', 'plugin' => 'LogiAuth'
));
Router::connect('/mail_auth', array(
        'controller' => 'users', 'action' => 'mail_auth', 'plugin' => 'LogiAuth'
));
Router::connect('/register', array(
        'controller' => 'users', 'action' => 'register', 'plugin' => 'LogiAuth'
));
Router::connect('/confirm_mail/*', array(
        'controller' => 'users', 'action' => 'confirm_mail', 'plugin' => 'LogiAuth'
));
Router::connect('/send_confirm_mail/*', array(
        'controller' => 'users', 'action' => 'send_confirm_mail', 'plugin' => 'LogiAuth'
));
Router::connect('/reset_pass/*', array(
        'controller' => 'users', 'action' => 'reset_pass', 'plugin' => 'LogiAuth'
));
Router::connect('/social', array(
        'controller' => 'users', 'action' => 'social', 'plugin' => 'LogiAuth'
));
Router::connect('/deactive/*', array(
        'controller' => 'users', 'action' => 'deactive', 'plugin' => 'LogiAuth'
));
Router::connect('/reset_account_lock/*', array(
        'controller' => 'users', 'action' => 'reset_account_lock', 'plugin' => 'LogiAuth'
));
Router::connect('/reset_lock/*', array(
        'controller' => 'users', 'action' => 'reset_lock', 'plugin' => 'LogiAuth'
));
//LogiAuth Twitter
Router::connect('/tw_login', array(
        'controller' => 'twitter', 'action' => 'login', 'plugin' => 'LogiAuth'
));
Router::connect('/tw_callback/*', array(
        'controller' => 'twitter', 'action' => 'callback', 'plugin' => 'LogiAuth'
));
Router::connect('/tw_password/*', array(
        'controller' => 'twitter', 'action' => 'password', 'plugin' => 'LogiAuth'
));
//LogiAuth Facebook
Router::connect('/fb_callback/*', array(
        'controller' => 'facebook', 'action' => 'callback', 'plugin' => 'LogiAuth'
));
Router::connect('/fb_password/*', array(
        'controller' => 'facebook', 'action' => 'password', 'plugin' => 'LogiAuth'
));
/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
CakePlugin::routes();
/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
require CAKE.'Config'.DS.'routes.php';
