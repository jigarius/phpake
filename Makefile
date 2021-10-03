.PHONY: ssh
ssh:
	docker compose exec main sh

.PHONY: test
test:
	composer exec phpunit
