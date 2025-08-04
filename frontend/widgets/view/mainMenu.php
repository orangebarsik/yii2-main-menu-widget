<?php
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\models\MainMenu;
use frontend\helpers\UserHelper;

/* @var $this yii\web\View */
/* @var $username string|null Имя пользователя */
/* @var $avatarUrl string|null URL аватара */
/* @var $ratingValue float|null Рейтинг пользователя */
/* @var $reviewsCount int|null Количество отзывов */
/* @var $activeRoute string Активный маршрут */
/* @var $menuItems MainMenu[] Массив пунктов меню */

// Проверка на null и установка значений по умолчанию
$username = $username ?? '';
$avatarUrl = $avatarUrl ?? '';
$ratingValue = $ratingValue ?? 0.0;
$reviewsCount = $reviewsCount ?? 0;
$activeRoute = $activeRoute ?? '';

// Конфигурация шаблонов для открывающих тегов
$openTemplates = [
    1 => [
        MainMenu::TYPE_MAIN_MENU => function() {
            return <<<'HTML'
<nav class="navbar navbar-expand">
    <div class="container">
HTML;
        }
    ],
    2 => [
        MainMenu::TYPE_LEFT_NAV => fn() => '<ul class="navbar-nav">',
        MainMenu::TYPE_RIGHT_NAV => fn() => '<ul class="navbar-nav align-items-center">'
    ],
    3 => [
        MainMenu::TYPE_NAV_ITEM => function($item, $params) {
            $url = Html::encode(Url::to([$item->url]));
            $isActive = $params['activeRoute'] === $url ? ' active' : '';
            $icon = $item->icon_classes ? Html::tag('i', '', [
                'class' => "{$item->icon_classes} fa-sm fa-fw me-1"
            ]) : '';
            $visibilityClasses = $item->visibility_classes ? " {$item->visibility_classes}" : '';
            $target = $item->is_external ? ' target="_blank"' : '';

            return <<<HTML
<li class="nav-item{$visibilityClasses}{$isActive}">
    <a class="nav-link" href="{$url}"{$target}>
        {$icon}
        {$item->title}
    </a>
</li>
HTML;
        },
        MainMenu::TYPE_NAV_ICON => function($item, $params) {
            $url = Html::encode(Url::to([$item->url]));
            $isActive = $params['activeRoute'] === $url ? ' active' : '';
            $icon = $item->icon_classes ? Html::tag('i', '', [
                'class' => "{$item->icon_classes} fa-lg fa-fw"
            ]) : '';
            $counter = $item->counter ? Html::tag('span', Html::encode($item->counter), [
                'class' => 'badge rounded-pill badge-counter' . ($item->counter_class ? " {$item->counter_class}" : '')
            ]) : '';
            $visibilityClasses = $item->visibility_classes ? " {$item->visibility_classes}" : '';
            $target = $item->is_external ? ' target="_blank"' : '';

            return <<<HTML
<li class="nav-item{$visibilityClasses}{$isActive}">
    <a class="nav-link nav-link-icon" href="{$url}"{$target} title="{$item->title}">
        {$icon}
        {$counter}
    </a>
</li>
HTML;
        },
        MainMenu::TYPE_NAV_BUTTON => function($item) {
            $url = Html::encode(Url::to([$item->url]));
            $icon = $item->icon_classes ? Html::tag('i', '', [
                'class' => "{$item->icon_classes} fa-sm fa-fw me-1"
            ]) : '';
            $styleClasses = $item->style_classes ? " {$item->style_classes}" : ' btn-outline-dark';
            $visibilityClasses = $item->visibility_classes ? " {$item->visibility_classes}" : '';
            $target = $item->is_external ? ' target="_blank"' : '';

            return <<<HTML
<li class="nav-item{$visibilityClasses}">
    <a class="btn{$styleClasses}" href="{$url}"{$target}>
        {$icon}
        {$item->title}
    </a>
</li>
HTML;
        },
        MainMenu::TYPE_NAV_DROPDOWN => function($item) {
            $visibilityClasses = $item->visibility_classes ? " {$item->visibility_classes}" : '';
			
            return <<<HTML
<li class="nav-item dropdown{$visibilityClasses}">
    <a class="nav-link dropdown-toggle" id="item_{$item->id}_Dropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        {$item->title}
    </a>
    <ul class="dropdown-menu" aria-labelledby="item_{$item->id}_Dropdown">
HTML;
        },
        MainMenu::TYPE_NAV_PROFILE => function($item, $params) {
            $avatarUrl = Html::encode($params['avatarUrl']);
            $visibilityClasses = $item->visibility_classes ? " {$item->visibility_classes}" : '';

            return <<<HTML
<li class="nav-item dropdown{$visibilityClasses}">
    <a class="nav-link nav-link-avatar" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        <div class="avatar">
            <div class="avatar-placeholder bg-secondary w-100 h-100" style="background-image: url({$avatarUrl});"></div>
        </div>
    </a>
    <div class="dropdown-menu dropdown-menu-end profile-dropdown" aria-labelledby="profileDropdown">
HTML;
        }
    ],
    4 => [
        MainMenu::TYPE_DROPDOWN_ITEM => function($item) {
            $url = Html::encode(Url::to([$item->url]));
            $icon = $item->icon_classes ? Html::tag('i', '', [
                'class' => "{$item->icon_classes} fa-fw me-2"
            ]) : '';
            $target = $item->is_external ? ' target="_blank"' : '';

            return <<<HTML
<li>
    <a class="dropdown-item" href="{$url}"{$target}>
        {$icon}
        {$item->title}
    </a>
</li>
HTML;
        },
        MainMenu::TYPE_SECTION_PROFILE => fn($item) => "<div class=\"profile-section\">",
        MainMenu::TYPE_SECTION_MENU => fn($item) => "<div class=\"menu-section\">"
    ],
    5 => [
        MainMenu::TYPE_MENU_PROFILE => function($item, $params) {
            $avatarUrl = Html::encode($params['avatarUrl']);
            $username = Html::encode($params['username']);

            return <<<HTML
<div class="profile-header">
    <div class="profile-image">
        <div class="profile-avatar bg-secondary w-100 h-100" style="background-image: url({$avatarUrl});"></div>
    </div>
    <div class="username">{$username}</div>
</div>
HTML;
        },
        MainMenu::TYPE_MENU_RATING => function($item, $params) {
            $url = Html::encode(Url::to([$item->url]));
            $isActive = $params['activeRoute'] === $url ? ' active' : '';
            $rating = number_format($params['ratingValue'], 2, ',');
            $stars = UserHelper::renderRatingStars($params['ratingValue']);
            $reviewsText = Html::encode(Yii::t('app',
                UserHelper::getNounPluralForm(
                    $params['reviewsCount'],
                    [
                        Yii::t('app', 'нет отзывов'),
                        Yii::t('app', 'отзыв'),
                        Yii::t('app', 'отзыва'),
                        Yii::t('app', 'отзывов')
                    ]
                )
            ));

            return <<<HTML
<div class="rating">
    <a href="{$url}">
        <span class="rating-value">{$rating}</span>
        <span class="stars">{$stars}</span>
        <span class="reviews-count{$isActive}">{$reviewsText}</span>
    </a>
</div>
HTML;
        },
        MainMenu::TYPE_MENU_ITEM => function($item, $params) {
            $url = Html::encode(Url::to([$item->url]));
            $isActive = $params['activeRoute'] === $url ? ' active' : '';
            $icon = $item->icon_classes ? Html::tag('i', '', [
                'class' => "{$item->icon_classes} fa-fw me-2"
            ]) : '';
            $badge = $item->badge ? Html::tag('span', Html::encode($item->badge), [
                'class' => 'badge rounded-pill ms-1' . ($item->badge_class ? " {$item->badge_class}" : '')
            ]) : '';
            $counter = $item->counter ? Html::tag('span', Html::encode($item->counter), [
                'class' => 'counter' . ($item->counter_class ? " {$item->counter_class}" : '')
            ]) : '';
            $target = $item->is_external ? ' target="_blank"' : '';

            return <<<HTML
<div class="menu-item{$isActive}">
    <a href="{$url}"{$target}>
        {$icon}
        {$item->title}
        {$badge}
    </a>
    {$counter}
</div>
HTML;
        },
        MainMenu::TYPE_MENU_PROFILES => function($item, $params) {
            $url = Html::encode(Url::to([$item->url]));
			$icon = $item->icon_classes ? Html::tag('i', '', [
                'class' => "{$item->icon_classes} fa-fw me-2"
            ]) : '';
            $avatarUrl = Html::encode($params['avatarUrl']);

            return <<<HTML
<div class="menu-item">
    <a href="#prifile/switch">
        {$icon}
        {$item->title}
    </a>
</div>
<div class="profile-avatars">
    <a href="{$url}">
		<div class="avatar">
			<div class="avatar-placeholder bg-secondary w-100 h-100" style="background-image: url({$avatarUrl});"></div>
		</div>
	</a>
	<a href="#profile/add">
		<div class="add-profile">
			<i class="fas fa-plus"></i>
		</div>
	</a>
</div>
HTML;
        },
        MainMenu::TYPE_MENU_LOGOUT => function($item) {
            $url = Html::encode(Url::to([$item->url]));
			$csrfParam = Yii::$app->request->csrfParam;
            $csrfToken = Yii::$app->request->csrfToken;
            $icon = $item->icon_classes ? Html::tag('i', '', [
                'class' => "{$item->icon_classes} fa-fw me-2"
            ]) : '';

            return <<<HTML
<div class="menu-item">
    <form action="{$url}" method="post">
        <input type="hidden" name="{$csrfParam}" value="{$csrfToken}">
        <button type="submit" class="btn btn-link logout">
            {$icon}
            {$item->title}
        </button>
    </form>
</div>
HTML;
        },
    ]
];

// Конфигурация шаблонов для закрывающих тегов
$closeTemplates = [
    1 => [MainMenu::TYPE_MAIN_MENU => '</div></nav>'],
    2 => [
        MainMenu::TYPE_LEFT_NAV => '</ul>',
        MainMenu::TYPE_RIGHT_NAV => '</ul>'
    ],
    3 => [
        MainMenu::TYPE_NAV_DROPDOWN => '</ul></li>',
        MainMenu::TYPE_NAV_PROFILE => '</div></li>'
    ],
    4 => [
        MainMenu::TYPE_SECTION_PROFILE => '</div>',
        MainMenu::TYPE_SECTION_MENU => '</div>'
    ]
];

// Основной код рендеринга меню
$currentDepth = 0;
$stackTypes = [];

foreach ($menuItems as $item) {
    // Проверяем, что $item является экземпляром MainMenu
    if (!$item instanceof MainMenu) {
        continue;
    }

    // Закрываем предыдущие уровни
    for ($depth = $currentDepth; $depth >= $item->depth; $depth--) {
        if (empty($stackTypes)) {
            break;
        }

        $type = array_pop($stackTypes);
        if (isset($closeTemplates[$depth][$type])) {
            echo $closeTemplates[$depth][$type];
        }
    }

    // Открываем новый уровень
    if (isset($openTemplates[$item->depth][$item->type])) {
        echo $openTemplates[$item->depth][$item->type]($item, [
            'username' => $username,
            'avatarUrl' => $avatarUrl,
            'ratingValue' => $ratingValue,
            'reviewsCount' => $reviewsCount,
            'activeRoute' => $activeRoute,
        ]);
    }

    $currentDepth = $item->depth;
    array_push($stackTypes, $item->type);
}

// Закрываем оставшиеся уровни
for ($depth = $currentDepth; $depth >= 0; $depth--) {
    if (empty($stackTypes)) {
        break;
    }

    $type = array_pop($stackTypes);
    if (isset($closeTemplates[$depth][$type])) {
        echo $closeTemplates[$depth][$type];
    }
}
?>