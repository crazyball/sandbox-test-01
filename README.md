# KARTABLE TEST APP (Dev in Progress)
---

IMPORTANT !!!! 

This application is not intended to be deployed in production but is a technical inventory 
of several coding methods (TDD, CQRS etc.) and features. This is not optimized (see Toto at bottom of this readme
to see a list of suggestions / improvements to be made).

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
 ------------------ -------- -------- ------ --------------------------
  Name               Method   Scheme   Host   Path                     
 ------------------ -------- -------- ------ --------------------------
  _preview_error     ANY      ANY      ANY    /_error/{code}.{_format}
  add_classroom      POST     ANY      ANY    /api/classrooms
  delete_classroom   DELETE   ANY      ANY    /api/classrooms/{id}
  list_classrooms    GET      ANY      ANY    /api/classrooms
  get_classroom      GET      ANY      ANY    /api/classrooms/{id}
  update_classroom   PUT      ANY      ANY    /api/classrooms/{id}
  create_exam        POST     ANY      ANY    /api/exam
  show_exam          GET      ANY      ANY    /api/exam/{id}
  add_student        POST     ANY      ANY    /api/students
  delete_student     DELETE   ANY      ANY    /api/students/{id}
  list_students      GET      ANY      ANY    /api/students
  get_student        GET      ANY      ANY    /api/students/{id}
  update_student     PUT      ANY      ANY    /api/students/{id}

 ------------------ -------- -------- ------ --------------------------

```


## Todo (improvements)

- Docker implementation
- Use of ParamFetcher on controller to fill DTOs
- Use of AMQP / Redis or equivalent for asynchronous actions (stats calculation etc.)
- Better DDD directories split (Application, Infrastructure, Core)
- Add Swagger to auto-discover / doc API
- HATEOS implementation to cover full RESTfull levels schema
- Authentication

