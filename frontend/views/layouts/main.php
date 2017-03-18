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

                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                            <span class="hidden-xs"><?= Yii::$app->user->identity->user_name ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">

                                <p>
                                    <?= Yii::$app->user->identity->user_name ?>
                                    <small><?= Yii::$app->formatter->asDate(Yii::$app->user->identity->created_at) ?></small>
                                </p>
                            </li>

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <?= Html::a(Yii::t('frontend', 'Profile'), ['site/user'], ['class' => "btn btn-default btn-flat"]) ?>
                                </div>
                                <div class="pull-right">
                                    <?= Html::a(Yii::t('frontend', 'Sign out'), ['site/logout'], ['data-method' => 'post', 'class' => "btn btn-default btn-flat"]) ?>
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


            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <li class="header"><?= Yii::t('frontend', 'Navigation') ?></li>

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
                                return true;
                            }
                        }
                    }
                    return false;
                }

                $treeviews = [


                    //Test Sheet List
                    [
                        'iconClass' => "fa  fa-files-o",
                        'iconText' => Yii::t('frontend', 'Test Sheet List'),
                        'addin' => '',
                        'url' => Url::to(['new-test-sheet/index']),
                        'active' => [],
                        'visible' => ['new-test-sheet/index'],
                    ],

//                    //Sample List
//                    [
//                        'iconClass' => "fa  fa-flask",
//                        'iconText' => Yii::t('frontend', 'Sample List'),
//                        'addin' => '',
//                        'url' => Url::to(['sample/index']),
//                        'active' => [],
//                        'visible' => ['sample/index'],
//                    ],
                    //Sample Service List
                    [
                        'iconClass' => "fa  fa-flask",
                        'iconText' => Yii::t('frontend', 'Sample Service List'),
                        'addin' => '',
                        'url' => Url::to(['sample-service/index']),
                        'active' => [],
                        'visible' => ['sample-service/index'],
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
