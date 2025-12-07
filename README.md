Установка
```
composer create-project SergeyTimakov86/test-apple apple-test
cd apple-test
docker compose up -d
docker exec app /bin/sh rollup.sh
```
Запуск

http://localhost в браузере

![screenshot](test.png)