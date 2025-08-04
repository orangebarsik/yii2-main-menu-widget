<?php

// Запуск командой
// /usr/bin/php yii migrate/to m250423_194548_create_profile_menu
// /usr/bin/php yii migrate/down

use yii\db\Migration;

class m250506_194114_create_main_menu extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%main_menu}}', [
            'id' => $this->primaryKey(),
            'tree' => $this->integer()->notNull(),
            'lft' => $this->integer()->notNull(),
            'rgt' => $this->integer()->notNull(),
            'depth' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
			'name' => $this->string(32),
            'is_external' => $this->boolean()->defaultValue(false),
            'type' => $this->integer()->notNull(),
            'is_active' => $this->boolean()->defaultValue(true),
            'visibility_classes' => $this->string(32),
            'style_classes' => $this->string(32),
            'icon_classes' => $this->string(32),
            'badge' => $this->string(16),
            'badge_class' => $this->string(16),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Создание индексов
        $this->createIndex('idx-main_menu-lft-rgt', '{{%main_menu}}', 'tree, lft, rgt');
        $this->createIndex('idx-main_menu-is_active', '{{%main_menu}}', 'is_active');
        $this->createIndex('idx-main_menu-title', '{{%main_menu}}', 'title');

        // Insert default menu items
        $this->batchInsert('{{%main_menu}}',
            ['tree', 'lft', 'rgt', 'depth', 'title', 'url', 'name', 'is_external', 'type', 'is_active', 'visibility_classes', 'style_classes', 'icon_classes', 'badge', 'badge_class', 'created_at', 'updated_at'],
            [
                // Меню для неавторизованного пользователя
                // Корневой элемент меню
                [1, 1, 38, 1, 'Главное меню', '', null, false, 1, true, null, null, null, null, null, time(), time()],

                // Левая часть меню
                [1, 2, 27, 2, 'Левая часть меню', '', null, false, 2, true, null, null, null, null, null, time(), time()],

                // Правая часть меню
                [1, 28, 37, 2, 'Правая часть меню', '', null, false, 3, true, null, null, null, null, null, time(), time()],

                // Пункты левой части меню
                [1, 3, 4, 3, 'Для бизнеса', '/business', null, false, 4, true, null, null, null, null, null, time(), time()],
                [1, 5, 6, 3, 'Карьера', '/career', null, false, 4, true, null, null, null, null, null, time(), time()],
                [1, 7, 8, 3, 'Помощь', '/support', null, false, 4, true, 'd-none d-xl-flex', null, null, null, null, time(), time()],
                [1, 9, 14, 3, 'Каталоги', '', null, false, 6, true, 'd-none d-xl-flex', null, null, null, null, time(), time()],
                [1, 15, 16, 3, '#яПомогаю', '/care', null, false, 4, true, 'd-none d-xl-flex', null, null, null, null, time(), time()],
                [1, 17, 26, 3, 'Ещё', '', null, false, 6, true, 'd-xl-none', null, null, null, null, time(), time()],

                // Вложенное пункты выпадающего меню Каталоги
                [1, 10, 11, 4, 'Каталог автомобилей', '/catalog/auto', null, false, 8, true, null, null, null, null, null, time(), time()],
                [1, 12, 13, 4, 'Каталог новостроек', '/catalog/novostroyki', null, false, 8, true, null, null, null, null, null, time(), time()],

                // Вложенное пункты выпадающего меню Ещё
                [1, 18, 19, 4, 'Помощь', '/support', null, false, 8, true, null, null, null, null, null, time(), time()],
                [1, 20, 21, 4, 'Каталог автомобилей', '/catalog/auto', null, false, 8, true, null, null, null, null, null, time(), time()],
                [1, 22, 23, 4, 'Каталог новостроек', '/catalog/novostroyki', null, false, 8, true, null, null, null, null, null, time(), time()],
                [1, 24, 25, 4, '#яПомогаю', '/care', null, false, 8, true, null, null, null, null, null, time(), time()],

                // Пункты правой части меню
                [1, 29, 30, 3, 'Избранное', '/favorites', 'nav-favorites', false, 5, true, null, null, 'far fa-heart', null, null, time(), time()],
                [1, 31, 32, 3, 'Корзина', '/order/cart', 'nav-cart', false, 5, true, null, null, 'fas fa-shopping-cart', null, null, time(), time()],
                // /login
                [1, 33, 34, 3, 'Вход и регистрация', 'site/login', null, false, 4, true, null, null, 'fa fa-lock', null, null, time(), time()],
                [1, 35, 36, 3, 'Разместить объявление', '/additem', null, false, 16, true, null, 'btn-additem', 'fa fa-plus', null, null, time(), time()],


                // Меню для авторизованного пользователя
                // Корневой элемент меню
                [2, 1, 100, 1, 'Главное меню', '', null, false, 1, true, null, null, null, null, null, time(), time()],

                // Левая часть меню
                [2, 2, 27, 2, 'Левая часть меню', '', null, false, 2, true, null, null, null, null, null, time(), time()],

                // Правая часть меню
                [2, 28, 99, 2, 'Правая часть меню', '', null, false, 3, true, null, null, null, null, null, time(), time()],

                // Пункты левой части меню
                [2, 3, 4, 3, 'Для бизнеса', '/business', null, false, 4, true, null, null, null, null, null, time(), time()],
                //[2, 5, 6, 3, 'Карьера', '/career', null, false, 4, true, null, null, null, null, null, time(), time()],
                [2, 7, 8, 3, 'Помощь', '/support', null, false, 4, true, 'd-none d-xl-flex', null, null, null, null, time(), time()],
                [2, 9, 14, 3, 'Каталоги', '', null, false, 6, true, 'd-none d-xl-flex', null, null, null, null, time(), time()],
                //[2, 15, 16, 3, '#яПомогаю', '/care', null, false, 4, true, 'd-none d-xl-flex', null, null, null, null, time(), time()],
                [2, 17, 26, 3, 'Ещё', '', null, false, 6, true, 'd-xl-none', null, null, null, null, time(), time()],

                // Вложенное пункты выпадающего меню Каталоги
                [2, 10, 11, 4, 'Каталог автомобилей', '/catalog/auto', null, false, 8, true, null, null, null, null, null, time(), time()],
                [2, 12, 13, 4, 'Каталог новостроек', '/catalog/novostroyki', null, false, 8, true, null, null, null, null, null, time(), time()],

                // Вложенное пункты выпадающего меню Ещё
                [2, 18, 19, 4, 'Помощь', '/support', null, false, 8, true, null, null, null, null, null, time(), time()],
                [2, 20, 21, 4, 'Каталог автомобилей', '/catalog/auto', null, false, 8, true, null, null, null, null, null, time(), time()],
                [2, 22, 23, 4, 'Каталог новостроек', '/catalog/novostroyki', null, false, 8, true, null, null, null, null, null, time(), time()],
                //[2, 24, 25, 4, '#яПомогаю', '/care', null, false, 8, true, null, null, null, null, null, time(), time()],

                // Пункты правой части меню
                [2, 29, 30, 3, 'Разместить объявление', '/additem', null, false, 16, true, null, 'btn-additem', 'fa fa-plus', null, null, time(), time()],
                [2, 31, 32, 3, 'Мои объявления', '/profile', null, false, 4, true, null, null, 'fa fa-list', null, null, time(), time()],
                [2, 33, 34, 3, 'Избранное', '/favorites', 'nav-favorites', false, 5, true, null, null, 'far fa-heart', null, null, time(), time()],
				[2, 35, 36, 3, 'Сообщения', '/profile/messenger', 'nav-messenger', false, 5, true, null, null, 'far fa-comment-alt', null, null, time(), time()],
                [2, 37, 38, 3, 'Уведомления', '/profile/notifications', 'nav-notifications', false, 5, true, null, null, 'far fa-bell', null, null, time(), time()],    
                [2, 39, 40, 3, 'Корзина', '/order/cart', 'nav-cart', false, 5, true, null, null, 'fas fa-shopping-cart', null, null, time(), time()],
                [2, 41, 98, 3, 'Профиль', '/profile', null, false, 7, true, null, null, null, null, null, time(), time()],


                // Вложенное пункты выпадающего меню Профиль
                // Секции выпадающего меню Профиль
                [2, 42, 47, 4, 'Секция Профиль', '', null, false, 9, true, null, null, null, null, null, time(), time()],
                [2, 48, 61, 4, 'Секция Основное', '', null, false, 10, true, null, null, null, null, null, time(), time()],
                [2, 62, 67, 4, 'Секция Сообщения и уведомления', '', null, false, 10, true, null, null, null, null, null, time(), time()],
                [2, 68, 77, 4, 'Секция Финансы и услуги', '', null, false, 10, true, null, null, null, null, null, time(), time()],
                [2, 78, 89, 4, 'Секция Настройки профиля', '', null, false, 10, true, null, null, null, null, null, time(), time()],
                [2, 90, 93, 4, 'Секция Профили', '', null, false, 10, true, null, null, null, null, null, time(), time()],
                [2, 94, 97, 4, 'Секция Выход', '', null, false, 10, true, null, null, null, null, null, time(), time()],

                // Профиль
                [2, 43, 44, 5, 'Профиль', 'profile/basic/index', null, false, 11, true, null, null, null, null, null, time(), time()],
                [2, 45, 46, 5, 'Рейтинг', 'profile/rating/index', null, false, 12, true, null, null, null, null, null, time(), time()],

                // Основное
                [2, 49, 50, 5, 'Мои объявления', 'profile/items/index', null, false, 13, true, null, null, 'fas fa-list', null, null, time(), time()],
				[2, 51, 52, 5, 'Избранное', 'profile/favorites/index', 'favorites', false, 13, true, null, null, 'fas fa-heart', null, null, time(), time()],
				[2, 53, 54, 5, 'Заказы', 'profile/orders/index', null, false, 13, true, null, null, 'fas fa-shopping-bag', null, null, time(), time()],
				[2, 55, 56, 5, 'Корзина', '/order/cart', 'cart', false, 13, true, null, null, 'fas fa-shopping-cart', null, null, time(), time()],
                [2, 57, 58, 5, 'Мои отзывы', 'profile/contacts/index', null, false, 13, true, null, null, 'fas fa-star', null, null, time(), time()],
                [2, 59, 60, 5, 'Портал призов', 'profile/rewards/index', null, false, 13, true, null, null, 'fas fa-gift', 'Новое', 'badge-new', time(), time()],

                // Сообщения и уведомления
                [2, 63, 64, 5, 'Сообщения', 'profile/messenger/index', 'messenger', false, 13, true, null, null, 'fas fa-comment-alt', null, null, time(), time()],
                [2, 65, 66, 5, 'Уведомления', 'profile/notifications/index', 'notifications', false, 13, true, null, null, 'fas fa-bell', null, null, time(), time()],

                // Финансы и услуги
                [2, 69, 70, 5, 'Кошелёк', 'profile/account/index', 'account', false, 13, true, null, null, 'fas fa-wallet', null, null, time(), time()],
                [2, 71, 72, 5, 'Платные услуги', 'profile/services/index', null, false, 13, true, null, null, 'fas fa-credit-card', null, null, time(), time()],
                [2, 73, 74, 5, 'Спецпредложения', 'profile/campaigns/index', null, false, 13, true, null, null, 'fas fa-percent', null, null, time(), time()],
                [2, 75, 76, 5, 'Уровень сервиса', 'profile/loyalty/index', null, false, 13, true, null, null, 'fas fa-medal', null, null, time(), time()],

                // Настройки профиля
                [2, 79, 80, 5, 'Управление профилем', 'profile/basic/index', null, false, 13, true, null, null, 'fas fa-user-edit', null, null, time(), time()],
                [2, 81, 82, 5, 'Защита профиля', 'profile/safety/index', null, false, 13, true, null, null, 'fas fa-shield-alt', null, null, time(), time()],
                [2, 83, 84, 5, 'Настройки', 'profile/settings/index', null, false, 13, true, null, null, 'fas fa-cog', null, null, time(), time()],
				[2, 85, 86, 5, 'Адреса', 'profile/address/index', null, false, 13, true, null, null, 'fas fa-map-marker-alt', null, null, time(), time()],
                [2, 87, 88, 5, 'Доставка', 'profile/delivery/index', null, false, 13, true, null, null, 'fas fa-truck', null, null, time(), time()],

                // Профили
                [2, 91, 92, 5, 'Мои профили', 'profile/basic/index', null, false, 14, true, null, null, 'fas fa-users', null, null, time(), time()],

                // Выход profile/exit
                [2, 95, 96, 5, 'Выйти', 'site/logout', null, false, 15, true, null, null, 'fas fa-sign-out-alt', null, null, time(), time()],
            ]
        );

        $this->update('{{%main_menu}}', ['is_active' => true]);
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%main_menu}}');
    }
}