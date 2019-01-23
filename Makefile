ifndef APP_ENV
	include .env
endif

.DEFAULT_GOAL := help
.PHONY: help
help:
	@grep -E '^[a-zA-Z-]+:.*?## .*$$' Makefile | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "[32m%-27s[0m %s\n", $$1, $$2}'

###> lexik/jwt-authentication-bundle ###
OPENSSL_BIN := $(shell which openssl)
generate-keys: ## Generates OAuth2 keys
ifndef OPENSSL_BIN
	$(error "Unable to generate keys (needs OpenSSL)")
endif
	@echo "\033[32mGenerating RSA keys for JWT\033[39m"
	@mkdir -p config/oauth2
	@openssl genrsa -passout pass:${OAUTH2_ENCRYPTION_KEY} -out ${OAUTH2_PRIVATE_KEY} 2048
	@openssl rsa -in ${OAUTH2_PRIVATE_KEY} -passin pass:${OAUTH2_ENCRYPTION_KEY} -pubout -out ${OAUTH2_PUBLIC_KEY}
	@chmod 640 ${OAUTH2_PRIVATE_KEY}
	@chmod 640 ${OAUTH2_PUBLIC_KEY}
	@echo "\033[32mRSA key pair successfully generated\033[39m"
###< lexik/jwt-authentication-bundle ###

###> db ###
DB_TEST_LOCATION := ./var/data.db
###< db ###

###> db ###
CONSOLE := $(shell which php ./bin/console)
###< db ###

###> cache ###
cache-clear: ## Clears cache
ifdef CONSOLE
	@php bin/console cache:clear --no-warmup
else
	@printf "\033[31mNo console defined!\033[39m\n"
endif
###< cache ###

###> migrate ###
migrate: ## Migrates all migrations
ifdef CONSOLE
	@php bin/console doctrine:migrations:migrate --no-interaction
	@printf "\033[32mMigration complete\033[39m\n"
else
	@printf "\033[31mNo console defined!\033[39m\n"
endif
###< migrate ###

###> drop-db ###
drop-db: ## Full database dropping
ifdef CONSOLE
	@php bin/console doctrine:schema:drop --full-database --force
else
	@printf "\033[31mNo console defined!\033[39m\n"
endif
###< drop-db ###

###> create-db ###
create-db: ## Creates new Database
ifdef CONSOLE
	@php bin/console doctrine:database:create
	@printf "\033[32mCreated new Database: ${DB_TEST_LOCATION}\033[39m\n"
else
	@printf "\033[31mNo console defined!\033[39m\n"
endif
###< create-db ###

###> schema-update ###
schema-update: ## Update schema (force)
ifdef CONSOLE
	@php bin/console doctrine:schema:update --force
else
	@printf "\033[31mNo console defined!\033[39m\n"
endif
###< schema-update ###

###> fixtures ###
fixtures: ## Loades all fixtures
ifdef CONSOLE
	@printf "\033[32mStart loading fixtures\033[39m\n"
	@php bin/console doctrine:fixtures:load --no-interaction
	@printf "\033[32mFixtures are loaded\033[39m\n"
else
	@printf "\033[31mNo console defined!\033[39m\n"
endif
###< fixtures ###

###> clear ###
clear: ## Clear the dist
	# Clear DB
	@if [ -f ${DB_TEST_LOCATION} ]; then \
    	printf "\033[32mDatabase deleted\033[39m\n"; \
    	rm ${DB_TEST_LOCATION}; \
    fi
	# Clear private key
	@if [ -f ${OAUTH2_PRIVATE_KEY} ]; then \
    	printf "\033[32mPrivate key deleted\033[39m\n"; \
    	rm ${OAUTH2_PRIVATE_KEY}; \
    fi
	# Clear public key
	@if [ -f ${OAUTH2_PUBLIC_KEY} ]; then \
    	printf "\033[32mPublic key deleted\033[39m\n"; \
    	rm ${OAUTH2_PUBLIC_KEY}; \
    fi
###< clear ###

###> total reinstalation ###
init: ## Reinstall all DB data with fixtures
	@export MAKEFLAGS=--no-print-directory
	@${MAKE} drop-db --no-print-directory
	@${MAKE} clear --no-print-directory
	@${MAKE} generate-keys --no-print-directory
	@${MAKE} migrate --no-print-directory
###< total reinstalation ###
