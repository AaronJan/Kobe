# Kobe

`Kobe` is a [Swagger](http://swagger.io/) definition writing tool for `PHP`, it can be used with framework like [Laravel](https://laravel.com) or just native `PHP`.

Kobe can only generate [definitions part](http://swagger.io/specification/#definitionsObject) of the [Swagger Specification](http://swagger.io/specification/), so you must use this with a full-featured library like [zircote/swagger-php](https://github.com/zircote/swagger-php) (or just write those in PHP using array).

Why named "Kobe"? Well, I did a little research, according to the [internet](http://www.bleacherreport.com/articles/924693-10-nba-players-with-most-swagger), **Kobe Bryant** has the most swagger, so I think Kobe knows the difinition of `Swagger`.


## Introduction

You must knew [zircote/swagger-php](https://github.com/zircote/swagger-php). If you used it before, you may complain these things like me:

1. How can I namespacing my definitions ?
2. Why writing nested stuff is so painful ?! Why I have to write so many definitions just for nesting ?!!
3. writing example value is killing me ..

Don't give up on `Swagger`, `Kobe` is here just for the `defintions`.

But first we have to know how `Swagger` works.

`Swagger` is a specification, it can be expressed in many ways, like `YAML` or `JSON`, commonly we use `JSON` in `PHP`. So whether you are using `zircote/swagger-php` or not, the final result is just a `JSON` string. 

Therefore, **you can write other Swagger stuff using `zircote/swagger-php` (because it's good for all of that but `definitions`), and using [Kobe](https://github.com/aaronjan/kobe) to organize your `definitions`**, 


## Caution

**Kobe currently doesn't have any test, although I haven't found any bug, but be careful. I'll writing some tests when I have time, or you can help this !**


## Installation

Install via [Composer](https://getcomposer.org/):

```shell
$ composer require aaronjan/kobe
```


## Example

Let's demostrate `Kobe` with a simple `Laravel` project.

First we create a folder `app/Swagger/Definitions/` to store all our `Definitions`, and create a `BaseDefinition` in `app/Swagger/BaseDefinition.php` like below:

```php
<?php

namespace App\Swagger;

use Kobe\Schemas\Definition;

/**
 * Class BaseDefinition
 *
 * @package App\Swagger
 */
abstract class BaseDefinition extends Definition
{
    /**
     * @return string
     */
    public function getBaseNamespace()
    {
        return 'App\\Swagger\\Definitions\\';
    }
}

```

`Kobe` generate name for `Definition` using the class name by default (you can change this behavior), for example the name of your `app\Swagger\Definitions\User\StoreResponse` will be `app.Swagger.Definitions.User.StoreResponse`. Obviously writing `ref="#/definitions/app.Swagger.Definitions.User.StoreResponse"` is too wordy, so you can trim your class by returning a prefix in the `getBaseNamespace()` method.

All of our `Definition` will be extending from this `BaseDefinition`.

OK, here is our first real `Definition`:

*app/Swagger/Definitions/Responses/ApiResponse.php*

```php
<?php

namespace App\Swagger\Definitions\Responses;

use App\Swagger\BaseDefinition;
use Kobe\Kobe;

/**
 * Class ApiResponse
 *
 * @package App\Swagger\Definitions\Responses
 */
class ApiResponse extends BaseDefinition
{
    /**
     * DemoResponse constructor.
     */
    public function __construct()
    {
        $this->setProperties([
            'code'    => Kobe::makeInteger(),
            'message' => Kobe::makeString(),
            'type'    => Kobe::makeString(),
        ]);
    }
}

```

This will eventually be converted to `JSON` like this (only the `definitions part` remember ?):

```json
{
    "definitions": {
        "Responses.ApiResponse": {
            "type": "object",
            "properties": {
                "code": {
                    "type": "integer",
                    "format": "int32"
                },
                "message": {
                    "type": "string"
                },
                "type": {
                    "type": "string"
                },
            }
        }
    }
}
```

You can write as many `Definitions` as you want, you can use `Kobe` whatever you want too, because it's just `PHP` !

For instance, a response that extends the `ApiResponse`:

*app/Swagger/Definitions/Responses/FailureResponse.php*

```php
<?php

namespace App\Swagger\Definitions\Responses;

use Kobe\Kobe;

/**
 * Class FailureResponse
 *
 * @package App\Swagger\Definitions\Responses
 */
class FailureResponse extends ApiResponse
{
    /**
     * FailureResponse constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setProperties([
            'code'    => Kobe::makeInteger()->setExample(400),
            'message' => Kobe::makeString()->setExample('an error message'),
            'type'    => Kobe::makeString()->setExample('error'),
        ]);
    }
}

```

You can see, writing example for property is that easy.

Finally, we have to return the `Swagger JSON`:

```php

    // Here is your controller method that returns the JSON

    public function getJSON()
    {
        // zircote/swagger-php
        $swagger = \Swagger\scan([
            app_path('Http/Controllers/'),
        ]);

        // Scan the whole directory for any PHP file and parse the class instances to Swagger definitions
        $definitions = \Kobe\Kobe::scanPSR4(
            app_path('Swagger/Definitions/'),
            'App\\Swagger\\Definitions\\'
        );

        // Merge them together!
        $swaggerDefinition = array_merge(
            ((array) $swagger->jsonSerialize()), 
            ['definitions' => $definitions]
        );

        return response()->json($swaggerDefinition, 200);
    }


```

But in the real world things are often more complex, let's write some more examples.

*app/Swagger/Definitions/User.php*

```php
<?php

namespace App\Swagger\Definitions;

use App\Swagger\BaseDefinition;
use Kobe\Kobe;

/**
 * Class User
 *
 * @package App\Swagger\Definitions
 */
class User extends BaseDefinition
{
    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->setProperties([
            'id'   => Kobe::makeInteger(),
            'name' => Kobe::makeString(),
        ]);
    }
}

```

*app/Swagger/Definitions/Article.php*

```php
<?php

namespace App\Swagger\Definitions;

use App\Swagger\BaseDefinition;
use Kobe\Kobe;

/**
 * Class Article
 *
 * @package App\Swagger\Definitions
 */
class Article extends BaseDefinition
{
    /**
     * Article constructor.
     */
    public function __construct()
    {
        $this->setProperties([
            'id'             => Kobe::makeInteger(),
            // $ref
            'author_user_id' => Kobe::referenceByClass(User::class),
            'title'          => Kobe::makeString(),
            'content'        => Kobe::makeString(),
        ]);
    }
}

```

*app/Swagger/Definitions/ComplexStuff.php*

```php
<?php

namespace App\Swagger\Definitions;

use App\Swagger\BaseDefinition;
use Kobe\Kobe;

/**
 * Class ComplexStuff
 *
 * @package App\Swagger\Definitions
 */
class ComplexStuff extends BaseDefinition
{
    /**
     * ComplexStuff constructor.
     */
    public function __construct()
    {
        $configDefinition = Kobe::makeTempDefinition()
            ->setProperties([
                'boot'  => Kobe::makeString()
                    ->setDescription('boot mode: immediate, delay')
                    ->setExample('delay'),
                'retry' => Kobe::makeInteger(),
            ]);

        $extendedUserDefinition = Kobe::makeTempDefinitionFrom(User::class)
            ->setName('temp.ExtendedUser')
            ->setProperties([
                'permissions' => Kobe::makeString()->setExample('CREATE,UPDATE'),
            ]);

        $this->setProperties([
            'config'       => $configDefinition->getReference(),
            'extendedUser' => $extendedUserDefinition->getReference(),
            'article'      => Kobe::referenceByClass(Article::class),
        ]);
    }
}

```

This is the result:

```json
{

    // ...

    "definitions": {
        "ApiResponse": {
            "type": "object",
            "properties": {
                "code": {
                    "type": "integer",
                    "format": "int32"
                },
                "message": {
                    "type": "string"
                },
                "type": {
                    "type": "string"
                }
            }
        },
        "Article": {
            "type": "object",
            "properties": {
                "id": {
                    "type": "integer",
                    "format": "int32"
                },
                "author_user_id": {
                    "$ref": "#\/definitions\/User"
                },
                "title": {
                    "type": "string"
                },
                "content": {
                    "type": "string"
                }
            }
        },
        "ComplexStuff": {
            "type": "object",
            "properties": {
                "config": {
                    "$ref": "#\/definitions\/temp.0c67cd34540a99c2"
                },
                "extendedUser": {
                    "$ref": "#\/definitions\/temp.ExtendedUser"
                },
                "article": {
                    "$ref": "#\/definitions\/Article"
                }
            }
        },
        "User": {
            "type": "object",
            "properties": {
                "id": {
                    "type": "integer",
                    "format": "int32"
                },
                "name": {
                    "type": "string"
                }
            }
        },
        "temp.0c67cd34540a99c2": {
            "type": "object",
            "properties": {
                "boot": {
                    "description": "boot mode: immediate, delay",
                    "type": "string",
                    "example": "delay"
                },
                "retry": {
                    "type": "integer",
                    "format": "int32"
                }
            }
        },
        "temp.ExtendedUser": {
            "type": "object",
            "properties": {
                "id": {
                    "type": "integer",
                    "format": "int32"
                },
                "name": {
                    "type": "string"
                },
                "permissions": {
                    "type": "string",
                    "example": "CREATE,UPDATE"
                }
            }
        }
    },

    // ...

}
```

`Temp Definition` automatically gets a random name (or you can set it manually), and will be parsed with all your other `Definitions`.


## API

---

### Kobe\Kobe

#### scanPSR4($directory, $namespace, $depth = 3)

#### parseDefinitions(array $definitionClasses, $mergeWithTemp = true)

#### makeInteger()

#### makeSchema()

#### makeItems()

#### makeLong()

#### makeFloat()

#### makeDouble()

#### makeString()

#### makeByte()

#### makeBinary()

#### makeBoolean()

#### makeDate()

#### makeDateTime()

#### makePassword()

#### makeObject()

#### makeArray()

#### makeTempDefinition($save = true)

#### makeTempDefinitionFrom($class, $save = true)

#### referenceByClass($class)

#### referenceByName($name)


---

### Kobe\Schemas\Definition

#### getName()

#### getReference()

#### setAllOf($parents)

#### getAllOf()

#### toArray()

#### setExample($example)

#### getExample()

#### setTitle($title)

#### getTitle()

#### setDefault($default)

#### getDefault()

#### setDescription($description)

#### getDescription()

#### setProperty($name, Understandable $value)

#### setProperties($properties)

#### getProperties()

#### addRequired($required)

#### setRequired(array $required)

#### getRequired()

#### setItems(\Kobe\Schemas\Items $items)

#### setReferenceAsItems(\Kobe\Schemas\Reference $reference)

#### getItems()

#### asInteger()

#### asSchema()

#### asItems()

#### asLong()

#### asFloat()

#### asDouble()

#### asString()

#### asByte()

#### asBinary()

#### asBoolean()

#### asDate()

#### asDateTime()

#### asPassword()

#### asObject()

#### asArray()

#### getType()

#### setType($type)

#### getFormat()

#### setFormat($format)

---


### Kobe\Schemas\TempDefinition

Extended from the `Kobe\Schemas\Definition`.

#### setName($name)

#### originToArray()


## Lisence

Licensed under the [APACHE LISENCE 2.0](http://www.apache.org/licenses/LICENSE-2.0).


## Credits

[Swagger](http://swagger.io/)

[zircote/swagger-php](https://github.com/zircote/swagger-php)