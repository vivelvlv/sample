<?php
use backend\assets\DashboardAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

DashboardAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="content-language" content="zh-CN">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<?php $this->beginBody() ?>
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="<?= Url::to(['site/index']) ?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>S</b>A</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><b>Sam</b>ple</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <li class="user-footer">
                        <?= Html::a(Yii::t('backend', 'Chinese'), ['site/language', 'locale' => 'zh-CN']) ?>
                    </li>
                    <li class="user-footer">
                        <?= Html::a(Yii::t('backend', 'English'), ['site/language', 'locale' => 'en-US']) ?>
                    </li>

                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <?= (isset(Yii::$app->user->identity->image) && !empty(Yii::$app->user->identity->image))
                                ? Html::img(Yii::$app->upload->imageRootUrl . Yii::$app->user->identity->image, ['class' => 'user-image', 'alt' => 'User Image'])
                                : Html::img(Yii::$app->upload->imageRootUrl . 'dist/img/user2-160x160.jpg', ['class' => 'user-image', 'alt' => 'User Image']); ?>
                            <span class="hidden-xs"><?= Yii::$app->user->identity->user_name ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <?= (isset(Yii::$app->user->identity->image) && !empty(Yii::$app->user->identity->image))
                                    ? Html::img(Yii::$app->upload->imageRootUrl . Yii::$app->user->identity->image, ['class' => 'img-circle', 'alt' => 'User Image'])
                                    : Html::img(Yii::$app->upload->imageRootUrl . 'dist/img/user2-160x160.jpg', ['class' => 'img-circle', 'alt' => 'User Image']); ?>
                                <p>
                                    <?= Yii::$app->user->identity->user_name . ' - ' ?>

                                    <small><?= Yii::$app->formatter->asDate(Yii::$app->user->identity->entry_date) ?></small>
                                </p>
                            </li>

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <?= Html::a(Yii::t('backend', 'Profile'), ['site/user'], ['class' => "btn btn-default btn-flat"]) ?>
                                </div>
                                <div class="pull-right">
                                    <?= Html::a(Yii::t('backend', 'Sign out'), ['site/logout'], ['data-method' => 'post', 'class' => "btn btn-default btn-flat"]) ?>
                                </div>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->
            <div class="user-panel">
                <div class="pull-left image">
                    <?= (isset(Yii::$app->user->identity->image) && !empty(Yii::$app->user->identity->image))
                        ? Html::img(Yii::$app->upload->imageRootUrl . Yii::$app->user->identity->image, ['class' => 'img-circle', 'alt' => 'User Image'])
                        : Html::img(Yii::$app->upload->imageRootUrl . 'dist/img/user2-160x160.jpg', ['class' => 'img-circle', 'alt' => 'User Image']); ?>
                </div>
                <div class="pull-left info">
                    <p><?= Yii::$app->user->identity->user_name ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                </div>
            </div>

            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header"><?= Yii::t('backend', 'Navigation') ?></li>

                <?php
                function activeTreeview($items)
                {
                    if (is_array($items)) {
                        $controller = Yii::$app->controller->id;
                        //$action = Yii::$app->controller->action->id;
                        if (in_array($controller, $items)) {
                            return 'active';
                        }
                    }
                    return '';
                }

                function visibleTreeView($items)
                {
                    if (is_array($items)) {

                        if (Yii::$app->user->identity != null) {
                            foreach ($items as $item) {
                                if (Yii::$app->user->identity->isOwn($item)) {
                                    return true;
                                }
                            }
                        }
                    }
                    return false;
                }

                $treeviews = [
                    //admin user management
                    [
                        'iconClass' => "fa fa-user-plus",
                        'iconText' => Yii::t('backend', 'Admin User Management'),
                        'addin' => '<i class=" fa fa-angle-left pull-right" ></i>',
                        'url' => '#',
                        'active' => ['admin-user', 'role'],
                        'visible' => ['admin-user/index', 'role/index'],
                        'menuItems' => [
                            [
                                'childIconClass' => "fa fa-circle-o",
                                'childIconText' => Yii::t('backend', 'Admin User Management'),
                                'childUrl' => ['admin-user/index'],
                            ],
                            [
                                'childIconClass' => "fa fa-circle-o",
                                'childIconText' => Yii::t('backend', 'Role Assignment'),
                                'childUrl' => ['role/index'],
                            ],
                        ],
                    ],
                    //user management
                    [
                        'iconClass' => "fa fa-users",
                        'iconText' => Yii::t('backend', 'User Management'),
                        'addin' => '<i class=" fa fa-angle-left pull-right" ></i>',
                        'url' => '#',
                        'active' => ['user', 'region'],
                        'visible' => ['user/index', 'user/basic-setting'],
                        'menuItems' => [
                            [
                                'childIconClass' => "fa fa-circle-o",
                                'childIconText' => Yii::t('backend', 'User Management'),
                                'childUrl' => ['user/index'],
                            ],
                            [
                                'childIconClass' => "fa fa-circle-o",
                                'childIconText' => Yii::t('backend', 'Basic Setting'),
                                'childUrl' => ['region/index', 'level' => 1],
                                'visible' => ['user/basic-setting'],
                            ],
                        ],
                    ],
                    //Sample
                    [
                        'iconClass' => "fa fa-flask",
                        'iconText' => Yii::t('backend', 'Sample Management'),
                        'addin' => '<i class=" fa fa-angle-left pull-right" ></i>',
                        'url' => '#',
                        'active' => ['service', 'device', 'service-type', 'sample-type', 'sample-unit', 'article', 'sample', 'sample-service'],
                        'visible' => ['sample-service/receive','sample-service/index', 'sample-service/deliver', 'sample-service/manage', 'sample-service/normal-back',
                            'sample-service/exception-list',
                            'sample-service/stuff-fetching',
                            'service-type/index',
                            'service/index', 'device/index', 'sample/index', 'sample-unit/index', 'article/terms', 'sample/basic-setting'],
                        'menuItems' => [
                            [
                                'childIconClass' => "fa fa-circle-o",
                                'childIconText' => Yii::t('backend', 'Receive Sample'),
                                'childUrl' => ['sample-service/receive'],
                            ],
                            [
                                'childIconClass' => "fa fa-circle-o",
                                'childIconText' => Yii::t('backend', 'Deliver Sample'),
                                'childUrl' => ['sample-service/deliver'],
                            ],
                            [
                                'childIconClass' => "fa fa-circle-o",
                                'childIconText' => Yii::t('backend', 'Sample Management'),
                                'childUrl' => ['sample-service/my-fetching'],
                                'visible' => ['sample-service/manage']
                            ],
                            [
                                'childIconClass' => "fa fa-circle-o",
                                'childIconText' => Yii::t('backend', 'Normal Back'),
                                'childUrl' => ['sample-service/normal-back'],
                            ],
                            [
                                'childIconClass' => "fa fa-circle-o",
                                'childIconText' => Yii::t('backend', 'Fetch'),
                                'childUrl' => ['sample-service/stuff-fetching'],
                            ],
                            [
                                'childIconClass' => "fa fa-circle-o",
                                'childIconText' => Yii::t('backend', 'Cancel List'),
                                'childUrl' => ['sample-service/exception-list'],
                            ],
                            [
                                'childIconClass' => "fa fa-circle-o",
                                'childIconText' => Yii::t('backend', 'Sample Service List'),
                                'childUrl' => ['sample-service/index'],
                            ],
                            [
                                'childIconClass' => "fa fa-circle-o",
                                'childIconText' => Yii::t('backend', 'Basic Setting'),
                                'childUrl' => ['service/index'],
                                'visible' => ['sample/basic-setting']
                            ],
                        ],
                    ],
                ];

                ?>

                <?php foreach ($treeviews as $treeview): ?>
                    <?php if (visibleTreeView($treeview['visible'])): ?>
                        <li class=" treeview <?= activeTreeview($treeview['active']) ?>">
                            <a href="<?= $treeview['url'] ?>">
                                <i class="<?= $treeview['iconClass'] ?>"></i> <span><?= $treeview['iconText'] ?> </span>
                                <?php if (isset($treeview['addin'])): ?>
                                    <?= $treeview['addin'] ?>
                                <?php endif; ?>
                            </a>
                            <?php if (isset($treeview['menuItems']) && !empty($treeview['menuItems'])): ?>
                                <ul class="treeview-menu">
                                    <?php foreach ($treeview['menuItems'] as $menuItem): ?>
                                        <?php if ((isset($menuItem['visible']) && visibleTreeView($menuItem['visible'])) || visibleTreeView($menuItem['childUrl'])): ?>
                                            <li>
                                                <?= Html::a('<i class="' . $menuItem['childIconClass'] . '"></i> ' . $menuItem['childIconText'], $menuItem['childUrl']) ?>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?= Html::encode($this->title) ?>
            </h1>
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </section>

        <!-- Main content -->
        <section class="content">
            <br>
            <?= $content ?>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.3.0
        </div>
        <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com">Almsaeed Studio</a>.</strong> All rights
        reserved.
    </footer>
</div> <!-- ./wrapper -->
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
