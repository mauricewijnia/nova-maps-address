# Nova Maps Address
Nova Maps Address is a Nova field that allows the user to select an adress using the Google Places API and store it in a JSON column.

## Table of Contents

1. [Installation](#installation)
2. [Usage](#usage)

## Installation

To install the field simply run:
```
composer require mauricewijnia/nova-maps-address
```

You will need a Google Maps API key with access to the Places API. You can place the key in your `.env` file like this:
```
NOVA_MAPS_ADDRESS_KEY="you_key_here"
```

## Usage
This fields stores it's data as JSON in your column, so we will have to cast our column to an array.

To add the field to your resource you can do:

```php
use Mauricewijnia\NovaMapsAddress\MapsAddress;
...
public function fields(Request $request) {
    return [
        ...
        MapsAddress::make(__('Address'), 'address'),
        ...
    ];
}
```

And in our model:
```php

protected $casts = [
    'address' => 'array'
]

```

The resulting data will have this format:
```
{
    street_name: string,
    street_number: string,
    postal_code: string,
    city: string,
    country: string,
    formatted_address: string,
    latitude: float,
    longitude: float
}
```

## Options

You can change the default zoom and center options for the map.
```php
MapsAddress::make(__('Address'), 'address')
    ->zoom(5)
    ->center(['lat' => 55.5, 'lng' => 5.5]);
```



