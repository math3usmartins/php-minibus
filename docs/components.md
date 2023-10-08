# Components

## Middlewares

A `Middleware` should do things not specifically related to the `Message`, like
logging or tracing.

Normally a `Middleware` will do at least one of the following:

- find a `Handler` to handle the message
- forward the `Message` to the next `Middleware`.
- fail the `Message` un/intentionally.

p.s. `MiniBus` provides a `HandlerMiddleware` which uses a `HandlerLocator` to
find one or more `Handler` to handle the `Message`.

## Handlers

A `Handler` should expect a **specific** `Message` to be handled.
e.g. `SendEmail` (command) or an `EmailOpened` (event).

**IMPORTANT:** a `Message` can be handled by multiple `Handler` instances.

See `HandlerLocator::locate(Envelope $envelope)` which returns a
`HandlerCollection`.

## Message brokers

A `Handler` may actually send a `Message` to a `MessageBroker`. e.g. if it's an
event that should be processed by another app.

p.s. different `Handler` instances can send a `Message` to different
`MessageBroker` instances.

## Middleware stack

The `MiddlewareStack` class creates a stack from a given array of `Middleware`
instances, where each `Middleware` can break the stack, by not calling the next
`Middleware`.

Therefore, it's critical to create your stack properly. i.e. first things
first.

**Example**

```php
$stack = new MiddlewareStack([
    $loggerMiddleware = new LoggerMiddleware($logger),
    $validatorMiddleware = new ValidatorMiddleware($validator),
    $amqpMiddleware = new AmqpTransporterMiddleware($amqpConnection),
]);
```

So the code above is same as the one below:

```php
$stack = new StackedMiddleware(
    $loggerMiddleware = new LoggerMiddleware($logger),
    new StackedMiddleware(
        $validatorMiddleware = new ValidatorMiddleware($validator),
        $amqpMiddleware = new AmqpTransporterMiddleware($amqpConnection),
    )
);
```

**Nested stacks**

If you need, you can have a `MiddlewareStack` within another `MiddlewareStack`.
This is good in case you have a common stack used by two different stacks.

This is just an example for your information. You probably DO NOT need nested
stacks. And probably you can just combine multiple arrays.

```php
$syncStack = new MiddlewareStack([
    new LoggerMiddleware($logger),
    new ValidatorMiddleware($validator),
    new DatabaseTransactionMiddleware($dbConnection),
    new CommandHandlerMiddelware($commandHandlerLocator),
    new EventHandlerMiddlware($eventHandlerLocator),
]);

$asyncStack = new MiddlewareStack([
    $syncStack,
    new AmqpTransporterMiddleware($amqpConnection),
]);
```
