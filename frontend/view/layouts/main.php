<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
//use yii\bootstrap5\Nav;
//use yii\bootstrap5\NavBar;
use frontend\widgets\MainMenuWidget;
use frontend\widgets\ProfileMenuWidget;
use frontend\widgets\FooterMenuWidget;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <?= MainMenuWidget::widget() ?>
    </header>

    <main role="main" class="flex-shrink-0">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

<?php /*
    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
            <p class="float-end"><?= Yii::powered() ?></p>
        </div>
    </footer> */ ?>
	
	 <style>
        .footer {
            background-color: #f8f9fa;
            padding: 40px 0 30px 0;
            /*border-top: 1px solid #e9ecef;*/
        }
        
        .footer-nav {
            margin-bottom: 25px;
            text-align: center;
        }
        
        .footer-nav a {
            color: #333;
            text-decoration: none;
            font-size: 16px;
            margin-right: 25px;
            margin-bottom: 8px;
            display: inline-block;
        }
        
        .footer-nav a:hover {
            color: #007bff;
            text-decoration: none;
        }
        
        .footer-info {
            color: #666;
            font-size: 13px;
            line-height: 1.5;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .footer-info a {
            color: #666;
            text-decoration: underline;
        }
        
        .footer-info a:hover {
            color: #333;
        }
        
        .social-icons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        
        .social-icon {
            width: 40px;
            height: 40px;
            background-color: #333;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
        
        .social-icon:hover {
            background-color: #555;
            color: white;
        }
        
        .social-icon.vk:hover {
            background-color: #4c75a3;
        }
        
        .social-icon.ok:hover {
            background-color: #ee8208;
        }
        
        .social-icon.youtube:hover {
            background-color: #ff0000;
        }

        .social-icon.telegram:hover {
            background-color: #0088cc;
        }
        
        @media (max-width: 768px) {
            .footer-nav a {
                margin-right: 15px;
                margin-bottom: 10px;
            }
            
            .social-icons {
                justify-content: center;
                margin-top: 20px;
            }
        }
    </style>
	 <?php /*
	<footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <!-- Navigation Links -->
                    <nav class="footer-nav">
                        <a href="#">Помощь</a>
                        <a href="#">Безопасность</a>
                        <a href="#">Реклама на сайте</a>
                        <a href="#">О компании</a>
                        <a href="#">Карьера</a>
                        <a href="#">Журнал</a>
                        <a href="#">Блог</a>
                        <a href="#">#яПомогаю</a>
                        <a href="#">Приложение</a>
                        <a href="#">Карта сайта</a>
                    </nav>
                    
                    <!-- Footer Information -->
                    <div class="footer-info">
                        <p class="mb-2">
                            KotoGo — <a href="#">сайт объявлений России</a>. © ООО «BarsilLab» 2007–2025. <a href="#">Правила KotoGo</a>,<br>
                            <a href="#">Политика конфиденциальности</a>. Оплачивая услуги на KotoGo, вы принимаете <a href="#">оферту</a>.<br>
                            KotoGo использует <a href="#">рекомендательные технологии</a>.
                        </p>
                    </div>
                    
                    <!-- Social Media Icons -->
                    <div class="social-icons">
                        <a href="#" class="social-icon vk" aria-label="VKontakte">
                            <i class="fab fa-vk"></i>
                        </a>
                        <a href="#" class="social-icon ok" aria-label="Odnoklassniki">
                            <i class="fab fa-odnoklassniki"></i>
                        </a>
                        <a href="#" class="social-icon youtube" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer> */ ?>
	
	<footer class="footer">
        <div class="container">
			<?php /*
            <!-- Navigation Links -->
            <div class="footer-nav">
                <a href="#">Помощь</a>
                <a href="#">Безопасность</a>
                <a href="#">Реклама на сайте</a>
                <a href="#">О компании</a>
                <a href="#">Карьера</a>
                <a href="#">Журнал</a>
                <a href="#">Блог</a>
                <a href="#">#яПомогаю</a>
                <a href="#">Приложение</a>
                <a href="#">Карта сайта</a>
            </div> */ ?>
			<?= FooterMenuWidget::widget() ?>
            
            <!-- Footer Information -->
            <div class="footer-info">
                <p class="mb-2">
                    KotoGo — <a href="#">сайт объявлений России</a>. © ООО «BarsikLab» 2007–2025. <a href="#">Правила KotoGo</a>.<br>
                    <a href="#">Политика конфиденциальности</a>. Оплачивая услуги на KotoGo, вы принимаете <a href="#">оферту</a>.<br>
                    KotoGo использует <a href="#">рекомендательные технологии</a>.
                </p>
            </div>
            
            <!-- Social Media Icons -->
            <div class="social-icons">
                <a href="#" class="social-icon vk" aria-label="VKontakte">
                    <i class="fab fa-vk"></i>
                </a>
                <a href="#" class="social-icon youtube" aria-label="YouTube">
                    <i class="fab fa-youtube"></i>
                </a>
                <a href="#" class="social-icon telegram" aria-label="Telegram">
                    <i class="fab fa-telegram-plane"></i>
                </a>
                <a href="#" class="social-icon ok" aria-label="Odnoklassniki">
                    <i class="fab fa-odnoklassniki"></i>
                </a>
            </div>
        </div>
    </footer>
	
	 

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
