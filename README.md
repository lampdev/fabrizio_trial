fabrizio Test Task
========

#### Missing features

- dashboard
- donate panel
- fixtures 
- no tests

#### Prerequisites

- free 80 port
- `docker`, `docker-compose`
- `composer`, `yarn`, both in the `PATH`
- PHP >= 7 with imagick PECL extension (required for `LiipImagineBundle`)
- `make` (optional)

#### Installation

- add `fabrizio.int` to your hosts file (optional, you can just use :80)
- `make build && make run`
- `make stop` to stop containers