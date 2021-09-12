# Bero

Bero (_Swedish_ depend) is a dependency injection container for PHP.
By inspecting class constructors Bero can instantiate classes and all their class dependencies with any depth.
It could be described as a on-the-fly factory creator.

![](https://github.com/paketphp/bero/workflows/tests/badge.svg)

## Usage

```
$bero = new StrictBero();
$bero->callCallable(function (ArticleRepository $articleRepository, Json $json) {
   $article = $articleRepository->getArticleById(17);
   echo $json->encode($article);
});
```

## Installation

`composer require paket/bero`

## Requirements

Requires PHP 7.2 or higher.

## General

Bero inspect method or function parameters for callables or class constructors to be able to instantiate those parameters. To be able to instantiate a parameter it has to have class type, if a type is an interface Bero has to be configured beforehand for what implementation to pick. Every class must either already be loaded or be able to be loaded by autoloader. 

This means that if a class with constructor parameter that is either missing or of scalar type, Bero can't instantiate it. Bero has a configuration API for those use cases.

When the same class is used multiple times through the dependency chain Bero
reuses the same object instance for that class for the entire lifetime of Bero (ideally the length of a request). This is what you probably want in the vast majority of cases, e.g. only one class instance per request for a
database repository.

This also means that any state stored within an instance will be shared with all usages of that class during the lifetime of Bero, for PHP that works pretty well because of PHP's share nothing between request, i.e. shared state will only be for that request, meaning you can use Bero for a singleton pattern per request basis, e.g. keeping track of the current logged in user for this request.

If it is needed to create a new instance for each usage it is recommended to create a factory class instead, depend on the factory class and then ask the factory class for each usage.

### Implementations

Bero has multiple implementations for usage in different scenarios

* MinimalBero 
    * as small as Bero can be for better performance
    * ideal use for production
* StrictBero
    * better error reporting
    * ideal use for development & testing
* BeroContainer
    * [PSR-11](https://www.php-fig.org/psr/psr-11/) complaint container
    * Wrapper around Bero

### Instantiation

#### MinimalBero
```php
$bero = new \Paket\Bero\MinimalBero();
```
#### StrictBero
```php
$bero = new \Paket\Bero\StrictBero();
```

### Dependency injection
Assume these classes

```php
class A
{
}

class B
{
    public $a;

    public function __construct(A $a) 
    {
        $this->a = $a;
    }
    
    public function doStuff(): int
    {
        return 17;
    }
}
```

#### Construct class instances & retrieve them

Instantiate class `B` that has `A` as a dependency

```php
$b = $bero->getObject(B::class);
assert(is_object($b->a));
$b->doStuff();
```

Or a `callable` can be used to retrieve multiple class instances
and optionally return a result.

```php
$int = $bero->callCallable(function (A $a, B $b) {
    assert($a === $b->a);
    return $b->doStuff();
});
assert($int === 17);
```

#### Use existing class instance together with Bero

Assume our application already has a class instance of `A` that we want
to use together with Bero.

```php
$bero->addObject(A::class, $a);
$b = $bero->getObject(B::class);
assert($a === $b->$a);
```

#### Custom factory for creating class instance

You can also provide your own `callable` to be called when
Bero needs to instantiate a class.

```php
$bero->addCallable(B::class, function (A $a) {
    return new B($a);
});
$b = $bero->getObject(B::class);
```

Note that the supplied callable can also depend on class 
dependencies to be used to instantiate your class.

Callable will only be called when needed & only once & then Bero
will cache the result.

`addCallable()` is good way to handle non-instantiable classes. 

```php
class C 
{
    public function __construct(int $i)
    {
    }
}

$bero->addCallable(C::class, function () {
    return new C(17);
});
```

#### Map interface to implementation

If your class depends on an interface you need to tell Bero
which implementation to be used.
 
```php
interface I 
{
}

class D implements I 
{
}

$bero->addInterface(I::class, D::class);
$i = $bero->getObject(I::class);
assert($i instanceof D);
```

#### Make Bero self aware

Normally you would only call Bero once when bootstrapping the application, e.g. instantiate the controller
& all it's dependencies & then execute it.

However sometimes your application makes a fork in it's execution path that is dependent on some calculated value 
that is unknown at bootstrap & because you only want to instantiate the execution path that is needed & not all of them 
you can depend on Bero further down in your application.

Assume following

```php
interface Service
{
    public function doStuff(): void;
}

class FooService implements Service
{
    private $a;

    public function __construct(A $a)
    {
        $this->a = $a;
    }

    public function doStuff(): void
    {
    }
}

class BarService implements Service
{
    public function doStuff(): void
    {
    }
}

class Controller
{
    private $bero;

    public function __construct(Bero $bero)
    {
        $this->bero = $bero;
    }
    
    public function run(bool $flag)
    {
        if ($flag) {
            $service = $this->bero->getObject(FooService::class);
        } else {
            $service = $this->bero->getObject(BarService::class);
        }
        $service->doStuff();
    }
}

$bero->addObject(Bero::class, $bero); // <-- self aware
$bero->addObject(A::class, $a);
$c = $bero->getObject(Controller::class);
$c->run(true);
```

By adding Bero to itself we can ask for the Bero instance further down in the application
& retain all previously constructed class instances & access them, thus class instance field `$a` for 
`FooService` will be the same as the global class instance `$a` & not a newly created instance. 

## License

Bero is released under the MIT License. See the bundled file LICENSE.txt.