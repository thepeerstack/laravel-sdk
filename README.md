# Thepeer Laravel SDK

<img width="732" alt="Screen Shot 2021-08-12 at 12 27 51 PM" src="https://user-images.githubusercontent.com/5338836/129189446-8acf40d3-5dd2-441c-a50c-2a9aa84b360c.png">

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
    
* getSendReceipt
    - `accepts`: 
        - receipt_id (string)
    - `returns`: object
    
* processSendReceipt
    - `accepts`: 
        - receipt_id (string)
        - insufficient_funds (bool)
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
    
* getLink
    - `accepts`:
        - lind_id (string)
    - `returns`: object

* chargeLink
    - `accepts`:
        - lind_id (string)
        - amount (integer)
    - `returns`: object
    
* authorizaDirectCharge
    - `accepts`:
        - reference (string)
        - insufficient_funds (bool)
    - `returns`: object

## Extra

Refer to the [documentation](https://docs.theper.co) for more information.
