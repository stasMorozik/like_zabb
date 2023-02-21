run_db:
  docker run --rm --name db_zabb -v /projects/mysql_like_zab/:/var/lib/mysql_like_zab -e MYSQL_ROOT_PASSWORD=12345 -e MYSQL_PASSWORD=12345 -e MYSQL_USER=db_user -e MYSQL_DATABASE=zabb -p 3306:3306 -d mysql:debian

run_db_test:
  docker run --rm --name db_zabb_test -v /projects/mysql_like_zab_test/:/var/lib/mysql_like_zab_test -e MYSQL_ROOT_PASSWORD=12345 -e MYSQL_PASSWORD=12345 -e MYSQL_USER=db_user -e MYSQL_DATABASE=zabb -e MYSQL_TCP_PORT=3307 -p 3307:3306 -d mysql:debian

build_migration:
  docker build -f Dockerfile.migration ./ -t zab_migration

run_migration:
  docker run --rm --name zab_migration -v /projects/like_zabb/backend:/migration zab_migration

build_test_migration:
  docker build -f Dockerfile.test.migration ./  -t zab_migration_test

run_test_migration:
  docker run --rm --name zab_migration_test -v /projects/like_zabb/backend:/migration_test zab_migration_test

build_tests:
  docker build -f Dockerfile.test ./  -t zabb_back_test

tests:
  docker run --rm --name zabb_back_test -v /projects/like_zabb/backend:/tests zabb_back_test
