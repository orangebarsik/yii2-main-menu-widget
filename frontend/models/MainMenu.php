<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use creocoder\nestedsets\NestedSetsBehavior;
//use frontend\models\MenuQuery;

/**
 * Class MainMenu
 *
 * @property int $id Идентификатор
 * @property int $tree Дерево
 * @property int $lft Левый ключ
 * @property int $rgt Правый ключ
 * @property int $depth Глубина
 * @property string $title Название пункта меню
 * @property string $url Ссылка
 * @property int $is_external Внешний ли url (1 - да, 0 - нет)
 * @property int $type Тип элемента
 * @property int $is_active Активен ли пункт (1 - да, 0 - нет)
 * @property string|null $visibility_classes Классы видимости
 * @property string|null $style_classes Классы стиля
 * @property string|null $icon_classes Классы иконки
 * @property string|null $badge Текст бейджа
 * @property string|null $badge_class Класс бейджа
 * @property int $created_at Время создания
 * @property int $updated_at Время обновления
 */
class MainMenu extends ActiveRecord
{
    // Меню для гостя и авторизованного пользователя
    const TREE_GUEST = 1;
    const TREE_AUTH = 2;

    // Типы элементов меню
    // Первый уровень, навигацилнная панель
    const TYPE_MAIN_MENU = 1;
    // Второй уровень, левая и правая части меню
    const TYPE_LEFT_NAV = 2;
    const TYPE_RIGHT_NAV = 3;
    // Третий уровнь, типы элементов меню
    const TYPE_NAV_ITEM = 4;
    const TYPE_NAV_ICON = 5;
    const TYPE_NAV_BUTTON = 16;
    const TYPE_NAV_DROPDOWN = 6;
    const TYPE_NAV_PROFILE = 7;
    // Четвертый уровень
    // Элемент выпадающего меню
    const TYPE_DROPDOWN_ITEM = 8;
    // Типы секций выпадающего меню профиль
    const TYPE_SECTION_PROFILE = 9;
    const TYPE_SECTION_MENU = 10;
    // Пятый уровень, типы элементов выпадающего меню профиль
    const TYPE_MENU_PROFILE = 11;
    const TYPE_MENU_RATING = 12;
    const TYPE_MENU_ITEM = 13;
    const TYPE_MENU_PROFILES = 14;
    const TYPE_MENU_LOGOUT = 15;
	// Время жизни кеша
	const CACHE_DURATION = 600; // 1 час в секундах
	
	private $_counter; // Текст счётчика
    private $_counterClass; // Класс счётчика
	
	/**
     * @return string|null
     */
    public function getCounter()
    {
        return $this->_counter;
    }

    /**
     * @param string|null $value
     */
    public function setCounter($value)
    {
        $this->_counter = $value;
    }

    /**
     * @return string|null
     */
    public function getCounter_class()
    {
        return $this->_counterClass;
    }

    /**
     * @param string|null $value
     */
    public function setCounter_class($value)
    {
        $this->_counterClass = $value;
    }

    /**
     * @return string название таблицы
     */
    public static function tableName()
    {
        return '{{%main_menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new MenuQuery(get_called_class());
    }

    /**
     * @return array правила валидации
     */
    public function rules()
    {
        return [
            [['tree', 'lft', 'rgt', 'depth', 'title', 'url', 'type'], 'required'],
            [['tree', 'lft', 'rgt', 'depth', 'type', 'created_at', 'updated_at'], 'integer'],
            [['title', 'url'], 'string', 'max' => 255],
            [['name', 'visibility_classes', 'style_classes', 'icon_classes'], 'string', 'max' => 32],
            [['badge', 'badge_class'], 'string', 'max' => 16],
            [['is_external', 'is_active'], 'boolean'],
            ['is_external', 'default', 'value' => 0],
            ['is_active', 'default', 'value' => 1],
            ['type', 'default', 'value' => self::TYPE_ITEM],
            ['type', 'in', 'range' => array_keys(self::getTypeNames)],
        ];
    }


    /**
     * @return array подписи атрибутов
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
			'tree' => 'Дерево',
            'lft' => 'Левый ключ',
            'rgt' => 'Правый ключ',
            'depth' => 'Глубина',
            'title' => 'Название',
            'url' => 'Ссылка',
			'name' => 'Имя',
            'is_external' => 'Внешний',
            'type' => 'Тип',
            'is_active' => 'Активен',
            'visibility_classes' => 'Классы видимости',
            'style_classes' => 'Классы стиля',
            'icon_classes' => 'Классы иконки',
            'badge' => 'Бейдж',
            'badge_class' => 'Класс бейджа',
            'created_at' => 'Время создания',
            'updated_at' => 'Время обновления',
        ];
    }

    /**
     * Получает названия типов
     * @return array
     */
    public static function getTypeNames()
    {
        return [
            self::TYPE_MAIN_MENU => 'Главное меню',
            self::TYPE_LEFT_NAV => 'Левая часть меню',
            self::TYPE_RIGHT_NAV => 'Правая часть меню',
            self::TYPE_NAV_ITEM => 'Элемент',
            self::TYPE_NAV_ICON => 'Иконка',
            self::TYPE_NAV_BUTTON => 'Кнопка',
            self::TYPE_NAV_DROPDOWN => 'Выпадающее меню',
            self::TYPE_NAV_PROFILE => 'Выпадающее меню Профиль',
            self::TYPE_DROPDOWN_ITEM => 'Элемент выпадающего меню',
            self::TYPE_SECTION_PROFILE => 'Секция Профиль меню Профиль',
            self::TYPE_SECTION_MENU => 'Секция меню Профиль',
            self::TYPE_MENU_PROFILE => 'Профиль меню Профиль',
            self::TYPE_MENU_RATING => 'Рейтинг меню Профиль',
            self::TYPE_MENU_ITEM => 'Элемент меню Профиль',
            self::TYPE_MENU_PROFILES => 'Профили меню Профиль',
            self::TYPE_MENU_LOGOUT => 'Выйти',
        ];
    }

    /**
     * Проверка наличия дочерних элементов
     */
    public function hasChildren()
    {
        return $this->rgt - $this->lft > 1;
    }

    /**
     * Получение дочерних элементов
     */
    public function getChildren()
    {
        return $this->children()->andWhere(['is_active' => true])->all();
    }
	
	/**
     * Получает пункты меню в зависимости от авторизации
     * @param bool $isGuest
     * @return array
     */
	public static function getItems(bool $isGuest): array
	{
		$treeId = $isGuest ? self::TREE_GUEST : self::TREE_AUTH;
        $cacheKey = "main_menu_raw_{$treeId}"; // raw_ - "сырые" данные

		return Yii::$app->cache->getOrSet($cacheKey, function() use ($treeId) {
			$items = self::find()
				->where(['tree' => $treeId, 'is_active' => 1])
				->orderBy(['lft' => SORT_ASC])
				->all();

			$indexedItems = [];
			foreach ($items as $item) {
				// Используем name, если он задан, иначе id
				$key = $item->name ?? 'id_' . $item->id;
				$indexedItems[$key] = $item;
			}

			return $indexedItems;
		}, self::CACHE_DURATION);
	}
}