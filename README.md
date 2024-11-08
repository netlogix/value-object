# netlogix/value-object

This package provides a collection of common value objects with Doctrine integration.

## Installation

Install the package via Composer:

```bash
composer require netlogix/value-object
```

## Usage

In order to use a value object with Doctrine, it must implement the `PersistableValueObject` interface. For this purpose, the value object can, for example, inherit from the abstract classes for common value objects or implement the interface directly.

## Acknowledgements
The initial implementation of the type registration with Doctrine was inspired by [upscale/doctrine-value-object-bundle](https://github.com/upscalesoftware/doctrine-value-object-bundle).
