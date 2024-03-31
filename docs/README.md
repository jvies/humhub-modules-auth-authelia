# Authelia Sign-In

Using this module, users can directly log in or register with [Authelia](https://www.authelia.com/) credentials at this HumHub installation.

A new button "Authelia" (which can be renamed) will appear on the login page.

## Features

- OpenID Connect

## Requirements

- PHP 8.1 or later
- PHP extensions: `MBString`, `JSON` and `BCMath` or `GMP`
- Depending on the algorithms you're using, other PHP extensions may be required (e.g. OpenSSL, Sodium). Full details: https://web-token.spomky-labs.com/introduction/pre-requisite

## Configuration

Go to module's configuration at: `Administration -> Modules -> Authelia Auth -> Configure`.
And follow the instructions.

## Repository

https://github.com/jvies/humhub-modules-auth-authelia

## Origin

The code is heavily inspired from the module [Keyclock Sign-in](https://github.com/cuzy-app/humhub-modules-auth-keycloak) from [CUZY.APP](https://www.cuzy.app/).

## Licence

[GNU AGPL](https://github.com/jvies/humhub-modules-auth-authelia/blob/master/docs/LICENCE.md)
