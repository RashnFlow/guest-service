
# Инструкция по запуску
Запустите контейнер

```bash
  docker-compose up -d
```
Подключитесь к контейнеру app
```bash
   docker-compose exec app bash
```
Запустите миграции и сервер
```bash
   php artisan migrate
   php artisan serve --host=0.0.0.0 --port=8000
```
Теперь сервер доступен по адресу http://localhost:8000
## API

#### Сущность "Гость"
| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `first_name` | `string` |  Имя гостя |
| `last_name` | `string` | Фамилия гостя |
| `email` | `string` | Email |
| `phone` | `string` | Телефон гостя (в формате +7) |
| `country` | `string` | Страна |
| `id` | `int` | id гостя |

#### Создание нового гостя

```http
  POST  /guests
```

| Parameter | Type     | Description                |
| :-------- | :------- | :------------------------- |
| `first_name` | `string` | **Required**. Имя гостя |
| `last_name` | `string` | **Required**. Фамилия гостя |
| `email` | `string` | **Required**. Email |
| `phone` | `string` | **Required**. Телефон гостя (в формате +7) |
| `country` | `string` | Страна |

В ответ возвращается сущность "Гость"


#### Получение данных о госте

```http
  GET /guests/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. id гостя |

В ответ возвращается сущность "Гость", если запись не найдена, то вернется
```http
  "error": "Запись не найдена"
```

#### Обновление данных гостя

```http
  PUT/PATCH /guests/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. id гостя |
| `first_name` | `string` | Имя гостя |
| `last_name` | `string` | Фамилия гостя |
| `email` | `string` | Email |
| `phone` | `string` | Телефон гостя (в формате +7) |
| `country` | `string` | Страна |

В ответ возвращается сущность "Гость", если запись не найдена, то вернется
```http
  "error": "Запись не найдена"
```

#### Удаление гостя

```http
  DELETE /guests/{id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. id гостя |

Ничего не возвращает при успешном удалении, если запись не найдена, то вернется
```http
  "error": "Запись не найдена"
```