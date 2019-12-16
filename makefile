
.PHONY: start stop copy-logs

start:
	docker-compose up -d
	docker cp ./app laravel-app:/var/www/html/
	docker-compose exec -d laravel bash /var/www/html/app/chmod777.sh
	./watch.sh
	@echo "**** listen localhost:8000 *****"

stop:
	docker-compose down

copy-logs:
	docker cp laravel-app:/var/www/html/app/site/storage/logs app/site/storage/

