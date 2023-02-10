
This Process Json Data project is created with Yii 2 Basic Project Template

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      config/             contains application configurations
      controllers/        contains Web controller classes
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages (availble after composer install)
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.4


INSTALLATION
------------

### Install via Composer


After composer install you should be able to access the application through the following URL, assuming `processJsonData` is the directory
directly under the Web root.

{{base-url}}/processJsonData/web

or at http://localhost:8080  
`php yii serve`
The above command will start yii server under port 8080, given your port 8080 is available.


TESTING
-------

Tests are located in `tests` directory. They are developed with Codeception PHP Testing Framework
There are 2 test suites:

- `unit`
- `functional`

Tests can be executed by running

```
vendor/bin/codecept run
```

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. 
