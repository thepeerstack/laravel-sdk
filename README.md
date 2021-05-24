# Thepeer Laravel SDK

## Installation

```bash
composer install thepeer/sdk
```

## Usage

### Initiate 

```php
<?php

$thepeer = new \Thepeer\Sdk\Thepeer("your-secret-key");
```

### Available methods

* validateSiganture
    - `accepts`: 
        - request object
    - `returns`: boolean
    
* getReceipt
    - `accepts`: 
        - reference
    - `returns`: object
    
* processReceipt
    - `accepts`: 
        - reference (string)
    - `returns`: object
    
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

## Extra

Refer to the [documentation](https://documenter.getpostman.com/view/2370026/TzJu8wdy) for more information.