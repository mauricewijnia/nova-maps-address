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

You will need a Google Maps API key with access to the Maps, Places and Geocoding API. You can place the key in your `.env` file like this:
```
NOVA_MAPS_ADDRESS_KEY="your_key_here"
```

## Usage
This fields stores its data as JSON in your column, so we will have to cast our column to an array.

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

You can change some of the settings for the map by call the respective option method:

```php
MapsAddress::make(__('Address'), 'address')
    ->zoom(5)
    ->center(['lat' => 55.5, 'lng' => 5.5])
    ->types(['address' ,'establishment']);
```

|Option|Description|Default|
|------|-----------|-------|
|zoom|Set the default zoom level of the map|10|
|center|Set the initial centering point of the map|```['lat' => 52.370216, 'lng' => 4.895168]```|
|types|Set the type of places that should be shown options are: establishment, address, geocode|```['address']```|



