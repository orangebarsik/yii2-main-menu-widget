<?php

namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;
use yii\helpers\Html;
use frontend\models\MainMenu;
use frontend\helpers\UserHelper;

class MainMenuWidget extends Widget
{
    // Константы для конфигурации аватара
    const AVATAR_SIZE = '48x48';
	// Время жизни кеша
    const CACHE_DURATION = 3600; // 1 час в секундах

	
    /**
     * @var string|null Имя пользователя
     */
    public ?string $username = null;
	
	/**
     * @var int|null Индекс цвета аватара
     */
	public ?int $avatarColorIndex  = null;

    /**
     * @var string|null URL аватара
     */
    public ?string $avatarUrl = null;
	
    /**
     * @var float|null Рейтинг пользователя
     */
    public ?float $ratingValue = null;

    /**
     * @var int|null Количество отзывов
     */
    public ?int $reviewsCount = null;
	
	/**
	 * @var array Массив счётчиков для пунктов меню
	 * @example [
	 *     'favorites' => ['counter' => 5],
	 *     'nav-favorites' => ['counter' => 5, 'counter_class' => 'badge-new']
	 * ]
	 */
	public $counters = []; 

    public function init(): void
    {
        parent::init();
				
		// Инициализация имени пользователя
        $this->initUsername();
		// Инициализация индекса цвета аватара
        $this->initAvatarColorIndex();
		// Инициализирует URL аватара
        $this->initAvatarUrl();
		// Инициализирует значение рейтинга
        $this->initRatingValue();
		// Инициализирует количество отзывов
        $this->initReviewsCount();
		// Инициализация счетчиков
        $this->initCounters();
    }
	
    /**
     * Инициализирует имя пользователя, если оно не задано
     */
    protected function initUsername(): void
    {
        if ($this->username === null) {
            $this->username = !Yii::$app->user->isGuest 
				? Yii::$app->user->identity->username
				: 'Гость';
        }
    }

	/**
     * Инициализирует индекс цвета аватара, если оно не задано
     */
    protected function initAvatarColorIndex(): void
    {
        if ($this->avatarColorIndex === null) {
			$this->avatarColorIndex = !Yii::$app->user->isGuest
				? Yii::$app->user->identity->avatarColorIndex
				: 0; 
		}
    }

    /**
     * Инициализирует URL аватара, если он не задан
     */
    protected function initAvatarUrl(): void
    {
        if ($this->avatarUrl === null) {
			$this->avatarUrl = !Yii::$app->user->isGuest
				? Yii::$app->user->identity->AvatarUrl 
					?: UserHelper::generateAvatarUrl($this->username, $this->avatarColorIndex, self::AVATAR_SIZE)
				: '';
        }
    }
	
	/**
     * Инициализирует значение рейтинга, если он не задано
     */
    protected function initRatingValue(): void
    {
        if ($this->ratingValue === null) {
			$this->ratingValue = !Yii::$app->user->isGuest
				? Yii::$app->user->identity->ratingValue
				: 0;
        }
    }
	
	/**
     * Инициализирует количество отзывов, если он не задано
     */
    protected function initReviewsCount(): void
    {
        if ($this->reviewsCount === null) {
			$this->reviewsCount = !Yii::$app->user->isGuest 
				? Yii::$app->user->identity->reviewsCount
				: 0;
        }
    }
	
	/**
     * Устанавливает счетчики для пунктов меню, если не заданы
     */
    protected function initCounters()
    {
		if (!empty($this->counters)) {
			$this->counters = $this->prepareCounters($this->counters);
			return;
		}
		$this->counters = $this->prepareCounters(
			!Yii::$app->user->isGuest
			? Yii::$app->user->identity->counters
			: $this->getGuestCounters()
		);
    }
	
	/**
     * Устанавливает тестовые значения счётчиков для гостя
     */
	protected function getGuestCounters(): array
	{
		return [
			'favorites' => 1,
			'cart' => 2,
		];
	}
	
	/**
	 * Форматирует счетчики согласно требованиям
	 */
	protected function prepareCounters(array $counters): array
	{
		$result = [];
		$navClasses = [
			'favorites' => 'badge-new',
			'messenger' => 'badge-alert',
			'notifications' => 'badge-notification',
			'cart' => 'badge-new',
		];
		
		foreach ($counters as $key => $value) {
			// Базовый счетчик
			$result[$key] = ['counter' => $key === 'account' ? number_format($value, 0, '.') . ' ₽' : $value];
			
			// Навигационный счетчик (nav-*)
			if (array_key_exists($key, $navClasses)) {
				$result['nav-'.$key] = [
					'counter' => $value,
					'counter_class' => $navClasses[$key]
				];
			} elseif ($key === 'cart') {
				$result['nav-'.$key] = ['counter' => $value];
			}
		}
		
		return $result;
	}
	
	/**
	 * Получение меню с установленными счётчиками
	 * 
	 * @param MainMenu[] $items Массив пунктов меню
	 * @return MainMenu[] Массив с применёнными счётчиками
	 */
	protected function getMenuItemsWithCounters(array $items): array
	{
		if (empty($items)) {
			Yii::info('No menu items found', __METHOD__);
			return [];
		}

		try {
			return array_map(
				[$this, 'applyCountersToItem'],
				array_filter($items, fn($item) => $item instanceof MainMenu)
			);
		} catch (\Exception $e) {
			Yii::error("Failed to process menu items: {$e->getMessage()}", __METHOD__);
			return [];
		}
	}

	/**
	 * Применяет настройки счётчиков к пункту меню
	 * 
	 * @param MainMenu $item Пункт меню
	 * @return MainMenu Обработанный пункт меню
	 */
	protected function applyCountersToItem(MainMenu $item): MainMenu
	{
		if (!is_array($this->counters)) {
			return $item;
		}
		
		$key = $item->name ?? 'id_' . $item->id;
		
		if (!isset($this->counters[$key])) {
			return $item;
		}

		$counterSettings = $this->counters[$key];

		if (array_key_exists('counter', $counterSettings)) {
			$item->counter = $counterSettings['counter'];
		}

		if (array_key_exists('counter_class', $counterSettings)) {
			$item->counter_class = $counterSettings['counter_class'];
		}

		return $item;
	}
	
	/**
	 * Добавление JS для подсветки активных пунктов меню
	 */
	protected function registerHighlightJs(): void
	{
		// Получаем активный маршрут для подсветки пунктов меню
		$activeRoute = Url::to([Yii::$app->controller->id.'/index']);
		// Генерируем JS для подсветки активных пунктов меню
		$js = $this->getHighlightJsScript($activeRoute);
		// Добавляем JS для подсветки активных пунктов меню
		$this->view->registerJs($js, \yii\web\View::POS_READY);
	}
	
	/**
	 * Генерация JS для подсветки активных пунктов меню
	 */
	protected function getHighlightJsScript(string $activeRoute): string
	{
		return <<<JS
(function() {
    $('.navbar a[href="{$activeRoute}"]')
        .closest('.menu-item, .nav-item, .rating')
        .addClass('active');
})();
JS;
	}
	
	/**
	 * Генерация ключа кеша
	 */
	protected function getCacheKey(bool $isGuest): string
	{
		// Формируем данные для кеша
		$cacheData = [
			'menuVersion' => Yii::$app->cache->get('main_menu_version') ?: 0,
			'counters' => $this->counters,
			'userData' => [
				'id' => Yii::$app->user->id,
				'isGuest' => $isGuest,
				'username' => $this->username,
				'avatarUrl' => $this->avatarUrl,
				'ratingValue' => $this->ratingValue,
				'reviewsCount' => $this->reviewsCount,
			]
		];
		
		return 'main_menu_'.md5(json_encode($cacheData));
	}

    public function run(): string
    {
		// Добавляем JS для подсветки
		$this->registerHighlightJs();
		
		// Получаем статус пользователя
        $isGuest = Yii::$app->user->isGuest;
		
		// Получаем базовую структуру меню (уже закеширована в модели)
        $baseMenuItems = MainMenu::getItems($isGuest);
				
		// Получаем ключ для кеша
		$cacheKey = $this->getCacheKey($isGuest);
		
		// Выводим меню
		return Yii::$app->cache->getOrSet($cacheKey, function() use ($baseMenuItems) {
			return $this->render('mainMenu', [
				'menuItems' => $this->getMenuItemsWithCounters($baseMenuItems),
				'username' => $this->username,
				'avatarUrl' => $this->avatarUrl,
				'ratingValue' => $this->ratingValue,
				'reviewsCount' => $this->reviewsCount,
			]);
		}, self::CACHE_DURATION);
    }
}