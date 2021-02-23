# KARTABLE TEST APP
---

## Requirements 

- PHP 7.4+
- MySQL
- Composer
- Symfony

## Install 

```
composer install
bin/console doctrine:database:create
bin/console doctrine:migration:migrate

bin/console doctrine:fixtures:load # to load default fixtures
```

## Launch

```
symfony serve
```

access api with given url (usually http://localhost:8000)

## Routes

```
 ---------------- -------- -------- ------ --------------------------
Name              Method   Scheme   Host   Path
 ---------------- -------- -------- ------ --------------------------
add_student       POST     ANY      ANY    /students
delete_student    DELETE   ANY      ANY    /students
get_student       GET      ANY      ANY    /students
update_student    PUT      ANY      ANY    /students
add_classroom     POST     ANY      ANY    /classrooms
delete_classroom  DELETE   ANY      ANY    /classrooms
get_classroom     GET      ANY      ANY    /classrooms
update_classroom  PUT      ANY      ANY    /classrooms
 ---------------- -------- -------- ------ --------------------------
```


## Todo (improvements)

- Docker implementation
- Use of ParamFetcher on controller to fill DTOs
- Use of AMQP / Redis or equivalent for asynchronous actions (stats calculation etc.)
- Better DDD directories split (Application, Infrastructure, Core)
- Add Swagger to auto-discover / doc API
- HATEOS implementation to cover full RESTfull levels scheme

