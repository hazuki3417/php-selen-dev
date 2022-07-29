

# NOTE: 誤操作防止のためtarget指定なしの場合はエラー扱いにする
all:
	@echo Please specify the target
	@exit 1

composer:
	docker-compose run --rm composer ${COMMAND}

package-install:COMMAND +=install
package-install: composer;

package-dumpautoload:COMMAND +=dumpautoload
package-dumpautoload: composer;

runner:
	docker run -t --rm -v ${PWD}:/var/www/html -w /var/www/html php:${IMAGE_TAG} ${COMMAND}

generate-api-coverage: IMAGE_TAG +=7.2-alpine
generate-api-coverage: COMMAND +=phpdbg -qrr vendor/bin/phpunit -c phpunit.coverage.xml
generate-api-coverage: runner;

generate-api-document:
	docker-compose run --rm php-documentor

test-all:
	make test-php-latest && \
	make test-php-8.1 && \
	make test-php-8.0 && \
	make test-php-7.4 && \
	make test-php-7.3 && \
	make test-php-7.2

test-php-latest: IMAGE_TAG +=alpine
test-php-latest: COMMAND +=php vendor/bin/phpunit
test-php-latest: runner;

test-php-8.1: IMAGE_TAG +=8.1-alpine
test-php-8.1: COMMAND +=php vendor/bin/phpunit
test-php-8.1: runner;

test-php-8.0: IMAGE_TAG +=8.0-alpine
test-php-8.0: COMMAND +=php vendor/bin/phpunit
test-php-8.0: runner;

test-php-7.4: IMAGE_TAG +=7.4-alpine
test-php-7.4: COMMAND +=php vendor/bin/phpunit
test-php-7.4: runner;

test-php-7.3: IMAGE_TAG +=7.3-alpine
test-php-7.3: COMMAND +=php vendor/bin/phpunit
test-php-7.3: runner;

test-php-7.2: IMAGE_TAG +=7.2-alpine
test-php-7.2: COMMAND +=php vendor/bin/phpunit
test-php-7.2: runner;
