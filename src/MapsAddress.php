<?php

namespace Mauricewijnia\NovaMapsAddress;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class MapsAddress extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-maps-address';

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $this->googleKey()->zoom(10)->center(['lat' => 52.370216, 'lng' => 4.895168])->types(['address'])->allowMapClick(true);
    }

    /**
     * Allow user to click on map to get the address
     * @param bool $allowMapClick 
     * @return $this 
     */
    public function allowMapClick(bool $allowMapClick) {
        return $this->withMeta(['allowMapClick' => $allowMapClick]);
    } 

    /**
     * All options can be found at https://developers.google.com/maps/documentation/javascript/reference/places-widget#Autocomplete 
     * @param array $autoCompleteOptions 
     * @return MapsAddress
     */
    public function autoCompleteOptions(array $autoCompleteOptions){
        return $this->withMeta(['autoCompleteOptions' => $autoCompleteOptions]);
    } 

    /**
     * @param array $center
     * @return MapsAddress
     */
    public function center(array $center) {
        if (isset($this->meta['center'])) {
            $center = array_merge($this->meta['center'], $center);
        }

        return $this->withMeta(['center' => $center]);
    }
    
    /**
     * @param NovaRequest $request
     * @param string $requestAttribute
     * @param object $model
     * @param string $attribute
     * @return mixed|void
     */
    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $model->setAttribute($attribute, json_decode($request->$attribute, true));
    }

    /**
     * All options can be found at https://developers.google.com/maps/documentation/javascript/reference/geocoder#GeocoderRequest
     * @param array $geocodeOptions 
     * @return MapsAddress 
     */
    public function geocodeOptions(array $geocodeOptions)
    {
        return $this->withMeta(['geocodeOptions' => $geocodeOptions]);
    }

    /**
     * @return MapsAddress
     */
    public function googleKey() {
        return $this->withMeta([
            'googleKey' => config('nova.maps-address-field.key')
        ]);
    }

    /**
     * All options can be found at https://developers.google.com/maps/documentation/javascript/reference/map#MapOptions
     * @param array $mapOptions 
     * @return MapsAddress 
     */
    public function mapOptions(array $mapOptions)
    {
        return $this->withMeta(['mapOptions' => $mapOptions]);
    }

    /**
     * All options can be found at https://developers.google.com/maps/documentation/javascript/url-params
     * @param array $scriptUrlParams 
     * @return $this 
     */
    public function scriptUrlParams(array $scriptUrlParams)
    {
        return $this->withMeta(['scriptUrlParams' => $scriptUrlParams]);
    }

    public function types(array $types) {
        return $this->withMeta(['types' => $types]);
    }

    /**
     * @param int $zoom
     * @return MapsAddress
     */
    public function zoom(int $zoom)
    {
        return $this->withMeta(['zoom' => $zoom]);
    }

    public function showMapOnDetail($value = true)
    {
        return $this->withMeta(['showMapOnDetail' => $value]);
    }
}
