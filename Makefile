.PHONY: ssh
ssh:
	docker compose exec main sh


.PHONY: lint
lint:
	composer exec phpcs


.PHONY: test
test:
	XDEBUG_MODE=coverage composer exec phpunit


.PHONY: coverage-text
coverage-text:
	cat .coverage/text


.PHONY: coverage-html
coverage-html:
	open .coverage/html/dashboard.html
