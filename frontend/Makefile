build_tests:
  docker build -f Dockerfile.test ./  -t zab_front_test

run_tests:
  docker run --rm --name zab_front_test -v /projects/like_zabb/frontend:/tests -v /tests/node_modules  zab_front_test

build_app_dev:
  docker build -f Dockerfile.dev ./  -t zab_front_dev

run_amqp_app_dev:
  docker run --rm --name zab_front_dev -v /projects/like_zabb/frontend:/app -v /app/node_modules zab_front_dev

build_app:
  docker build -f Dockerfile.prod ./  -t zab_front

run_amqp_app_dev:
  docker run --rm --name zab_front -v /projects/like_zabb/frontend:/app -v /app/node_modules zab_front
