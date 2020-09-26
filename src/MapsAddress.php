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

        $this->googleKey()->zoom(10)->center(['lat' => 52.370216, 'lng' => 4.895168])->types(['address']);
    }

    /**
     * @return MapsAddress
     */
    public function googleKey() {
        return $this->withMeta([
            'googleKey' => env('NOVA_MAPS_ADDRESS_KEY')
        ]);
    }

    /**
     * @param int $zoom
     * @return MapsAddress
     */
    public function zoom(int $zoom)
    {
        return $this->withMeta(['zoom' => $zoom]);
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

    public function types(array $types) {
        return $this->withMeta(['types' => $types]);
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
}
