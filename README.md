# Migrations Shiet Show

## The problem

We generate migrations, and run them. This should put the ORM metadata and the actual DB in sync.

However, if we generate migrations again, it will detect changes and generate some stuff again.

## How to reproduce

It uses docker, so you can just run the following in the CLI:
```bash
make docker-create-network # You only need to run this once
make docker-run-test
```
This last command will run the following:
```bash
	make docker-up-deamon
	make docker-install-deps
	make docker-create-db-dev
	make docker-generate-migration
	make docker-run-migrations
	make docker-generate-migration
	make docker-run-migrations
	make docker-generate-migration
	make docker-down
```
Check the migration files created, in `build/Migration/Version`.

We can try running that migration and creating a new one, surely that would put everything in sync...
```bash
make docker-run-migrations
make docker-generate-migration
```
Check the new migration created.

It's exactly the same as the previous one, except for the class name.

## Other info

I tried applying the changes (currently commented out) in: 

  - `config/doctrine/mappings/components/CM.GlobalTicket.Core.Component.Channel.Domain.Channel.php`
    - Tweaking this, it can actually get to the point of creating up() and down() methods that are exactly the same.
  - `config/packages/doctrine.php`

Unfortunately nothing works.
