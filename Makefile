

# NOTE: 誤操作防止のためtarget指定なしの場合はエラー扱いにする
all:
	@echo Please specify the target
	@exit 1

composer:
	docker-compose run --rm composer ${COMMAND}

package-install:COMMAND +=install --no-suggest
package-install: composer;

package-dumpautoload:COMMAND +=dumpautoload
package-dumpautoload: composer;

runner:
	docker run -t --rm -v ${PWD}:/var/www/html -w /var/www/html php:${IMAGE_TAG} ${COMMAND}

php-cs-fixer: IMAGE_TAG +=8.0-alpine
php-cs-fixer: COMMAND +=php vendor/bin/php-cs-fixer fix -vvv --diff
php-cs-fixer: runner;

generate-api-coverage: IMAGE_TAG +=8.0-alpine
generate-api-coverage: COMMAND +=phpdbg -qrr vendor/bin/phpunit -c phpunit.coverage.xml
generate-api-coverage: runner;

generate-api-document:
	docker-compose run --rm php-documentor

test-verify: IMAGE_TAG +=alpine
test-verify: COMMAND +=php vendor/bin/phpunit --group=verify
test-verify: runner;

test-all:
	make test-php-latest && \
	make test-php-8.1 && \
	make test-php-8.0

test-php-latest: IMAGE_TAG +=alpine
test-php-latest: COMMAND +=php vendor/bin/phpunit
test-php-latest: runner;

test-php-8.1: IMAGE_TAG +=8.1-alpine
test-php-8.1: COMMAND +=php vendor/bin/phpunit
test-php-8.1: runner;

test-php-8.0: IMAGE_TAG +=8.0-alpine
test-php-8.0: COMMAND +=php vendor/bin/phpunit
test-php-8.0: runner;
