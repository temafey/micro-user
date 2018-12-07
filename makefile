# use the rest as arguments for "run"
RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
# ...and turn them into do-nothing targets
$(eval $(RUN_ARGS):;@:)

.PHONY: start
start: erase build ci up db ## clean current environment, recreate dependencies and spin up again

.PHONY: stop
stop: ## stop environment
		docker-compose stop

.PHONY: rebuild
rebuild: start ## same as start

.PHONY: erase
erase: ## stop and delete containers, clean volumes.
		docker-compose stop
		docker-compose rm -v -f

.PHONY: build
build: ## build environment and initialize composer and project dependencies
		docker-compose build
		docker-compose run --rm php sh -lc 'composer install'

.PHONY: artifact
artifact: ## build production artifact
		docker-compose -f docker-compose.prod.yml build

.PHONY: ci
ci: ## Install project dependencies
		docker-compose run --rm php sh -lc 'composer install'

.PHONY: cu
cu: ## Update project dependencies
		docker-compose run --rm php sh -lc 'composer update'

.PHONY: ccom
ccom: ## Execute composer command
		docker-compose run --rm php sh -lc "composer $(RUN_ARGS)"

.PHONY: up
up: ## spin up environment
		docker-compose up -d

.PHONY: phpunit
phpunit: db ## execute project unit tests
		docker-compose exec php sh -lc "./bin/phpunit $(conf)"

.PHONY: style
style: ## executes php analizers
		docker-compose run --rm php sh -lc './vendor/bin/phpstan analyse -l 6 -c phpstan.neon src tests'

.PHONY: cs
cs: ## executes php cs fixer
		docker-compose run --rm php sh -lc './vendor/bin/php-cs-fixer --no-interaction --diff -v fix'

.PHONY: cs-check
cs-check: ## executes php cs fixer in dry run mode
		docker-compose run --rm php sh -lc './vendor/bin/php-cs-fixer --no-interaction --dry-run --diff -v fix'

.PHONY: lint
lint: ## checks syntax of PHP files
		docker-compose run --rm php sh -lc './vendor/bin/parallel-lint ./ --exclude vendor --exclude bin/.phpunit'
		docker-compose run --rm php sh -lc './bin/console lint:yaml config'
		#docker-compose run --rm php sh -lc './bin/console lint:twig templates'

.PHONY: layer
layer: ## Check issues with layers (deptrac tool)
		docker-compose run --rm php sh -lc 'php bin/deptrac.phar analyze --formatter-graphviz=0'

.PHONY: db
db: ## recreate database
		docker-compose exec php sh -lc './bin/console d:d:d --force'
		docker-compose exec php sh -lc './bin/console d:d:c'
		docker-compose exec php sh -lc './bin/console d:m:m -n'

.PHONY: schema-validate
schema-validate: ## validate database schema
		docker-compose exec php sh -lc './bin/console d:s:v'

.PHONY: xon
xon: ## activate xdebug simlink
		docker-compose exec php sh -lc 'xon'

.PHONY: xoff
xoff: ## deactivate xdebug
		docker-compose exec php sh -lc 'xoff'

.PHONY: sh
sh: ## gets inside a container, use 's' variable to select a service. make s=php sh
		docker-compose exec $(s) sh -l

.PHONY: logs
logs: ## look for 's' service logs, make s=php logs
		docker-compose logs -f $(s)

.PHONY: help
help: ## Display this help message
	@cat $(MAKEFILE_LIST) | grep -e "^[a-zA-Z_\-]*: *.*## *" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: ns
ns: ## Nginx shell
		docker-compose exec nginx sh -l

.PHONY: nr
nr: ## Nginx reload
		docker-compose exec nginx nginx -s reload

.PHONY: ps
ps: ## PHP shell
		docker-compose exec php sh -l

.PHONY: pr
pr: ## PHP restart
		docker-compose exec php kill -USR2 1

.PHONY: commit
commit: ## PHP Commitizen
		docker-compose run --rm php sh -lc 'php vendor/bin/php-commitizen commit $(RUN_ARGS)'

.PHONY: rmt
rmt: ## RMT release
		docker-compose run --rm php sh -lc 'php ./RMT release'