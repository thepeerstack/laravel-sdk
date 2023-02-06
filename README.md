# Thepeer Laravel SDK

<img width="1334" alt="Screen Shot 2021-08-12 at 12 32 27 PM" src="https://user-images.githubusercontent.com/5338836/129189931-2094054a-f3c7-4f10-a23b-dbc652fde9c2.png">

## Installation

```bash
composer require thepeer/sdk
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
* getBusinesses
    - `accepts`:
        - channel (string)
    - `returns`: object

## Extra

Refer to the [documentation](https://docs.thepeer.co/) for more information.
