# Yii2 Advanced + Docker Project

## Требования
- Docker & Docker Compose
- PHP (только внутри контейнера)
- MySQL (через Docker)
- Composer (для установки зависимостей, при необходимости)

---

## Установка

2. **Запуск Docker-контейнеров**
```bash
docker-compose up -d
```

3. **Инициализация Yii2 Advanced (только один раз)**
```bash
docker exec -it yii2-backend-1 php init
```

4. **Настройка базы данных**
```bash
docker exec -it yii2-backend-1 yii migrate
```

Запуск приложения

Backend: http://localhost:21080

Для теста приложение есть Постман коллекция 

YII2.postman_collection.json