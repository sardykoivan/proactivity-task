## Необходимое ПО
1. Docker
2. Composer

## Развертывание

1. git clone https://github.com/sardykoivan/proactivity-task
2. перейти в папку проекта
3. composer update
4. docker-compose up -d --build

## Проверка
5. docker exec -it proactivity-task_fpm_1 bash
6. php artisan migrate
7. php artisan tenders:parse
8. Подключиться к БД localhost:33061, база proactivitytask
