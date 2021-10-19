# Установка Meteor CMS

## Минимальная версия php >= 7.3

- Склонировать репозиторий в нужное место таким образом, чтобы директория __public__  была публичной точкой входа в апаче.

Пример настройки виртуального хоста:

```apacheconf
# виртуальный хост сайта meteorcms.loc
<VirtualHost meteorcms.loc:80>
    ServerAdmin webmaster@localhost
    ServerName meteorcms.loc
    ServerAlias www.meteorcms.loc
    DocumentRoot /var/www/meteorcms.loc/public
    ErrorLog /var/www/logs/error.log
    CustomLog /var/www/logs/access.log combined
    DirectoryIndex index.php index.htm index.html
</VirtualHost>
```

> Можно так же зайти на сайт как [http://localhost/meteorcms/public/](http://localhost/meteorcms/public/), но стабильность работы всех ссылок не гарантируется.

- Запустить установку композер пакетов

```bash
php composer install
```

> Можно также использовать ключ `--no-dev`. В этом случае пакеты, которые нужны только для разработки не будут установлены.

- Если в директории с проектом не появился файл `.env` то необходимо создать его, скопировав пример

```bash
cp .env.example .env
```

И сгенерировать случайный ключ, который запишется в наш `.env` файл

```bash
php artisan key:generate
```

- Открыть `.env` файл, найти строки, которые отвечают за подключение к БД

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=meteorcms
DB_USERNAME=root
DB_PASSWORD=123
```

И изменить их на свои. А так же заменить `APP_URL` на свой адрес

- Теперь можно запустить миграцию

```bash
php artisan migrate --seed
```

Если все прошло успешно, то теперь можно попробовать зайти на сайт.
Доступ в админ панель `info@fokgroup.com` / `admin`