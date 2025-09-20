<?php

use app\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Breadcrumbs;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);
    
    $menuItems = [];
    
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Yii::t('app', 'nav.signup'), 'url' => ['/signup']];
        $menuItems[] = ['label' => Yii::t('app', 'nav.login'), 'url' => ['/login']];
    } else {
        $menuItems[] = ['label' => Yii::t('app', 'nav.home'), 'url' => ['/site/index']];
        $menuItems[] = ['label' => Yii::t('app', 'nav.products'), 'url' => ['/products/index']];
        $menuItems[] = ['label' => Yii::t('app', 'nav.categories'), 'url' => ['/categories/index']];
        
        if (Yii::$app->user->can('user.index')) {
            $menuItems[] = ['label' => Yii::t('app', 'nav.users'), 'url' => ['/users/index']];
        }
        
        $menuItems[] = '<li class="nav-item">'
            . Html::beginForm(['/logout'])
            . Html::submitButton(
                Yii::t('auth', 'button.logout') . ' (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout nav-link', 'style' => 'color: rgba(255,255,255,.55); border: none; padding: 0.5rem 1rem;']
            )
            . Html::endForm()
            . '</li>';
    }
    
    $languageItems = [
        ['label' => Yii::t('app', 'app.english'), 'url' => ['/site/language', 'lang' => 'en-US']],
        ['label' => Yii::t('app', 'app.russian'), 'url' => ['/site/language', 'lang' => 'ru-RU']],
    ];
    
    $menuItems[] = [
        'label' => Yii::t('app', 'app.language'),
        'items' => $languageItems
    ];
    
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (isset($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        
        <?php foreach (Yii::$app->session->getAllFlashes() as $key => $message): ?>
            <div class="alert alert-<?= $key === 'error' ? 'danger' : $key ?> alert-dismissible fade show" role="alert">
                <?= $message ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endforeach; ?>
        
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-start">&copy; Kub24 <?= date('Y') ?></div>
            <div class="col-md-6 text-end"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
