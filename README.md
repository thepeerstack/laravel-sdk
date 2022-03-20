# Thepeer Laravel SDK

## Installation

```bash
composer install thepeer/sdk
```

## Usage

### Initiate 

```php
<?php

use Thepeer\Sdk\Thepeer;

$thepeer = new Thepeer("your-secret-key");

$thepeer->chargeLink("lost-in-the-world", 5000, "Benz");
```

### Available methods

* validateSiganture
    - `accepts`: 
        - request (object)
    - `returns`: boolean
    
* indexUser
    - `accepts`:
        - name (string)
        - email (string)
        - identifier (string)
    - `returns`: object
        
* updateUser
    - `accepts`:
        - reference (string)
        - identifier (string)
    - `returns`: object
        
* deleteUser
    - `accepts`:
        - reference (string)
    - `returns`: boolean
    
* getLink
    - `accepts`:
        - link_id (string)
    - `returns`: object

* chargeLink
    - `accepts`:
        - link_id (string)
        - amount (integer)
    - `returns`: object
    
* authorizeCharge
    - `accepts`:
        - reference (string)
        - event (string)
    - `returns`: object

## Extra

Refer to the [documentation](https://docs.theper.co) for more information.