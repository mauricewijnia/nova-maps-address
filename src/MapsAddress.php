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

        $this->googleKey();
    }

    /**
     * @return MapsAddress
     */
    public function googleKey() {
        return $this->withMeta([
            'googleKey' => env('NOVA_MAPS_ADDRESS_KEY')
        ]);
    }

    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        $model->setAttribute($attribute, json_decode($request->$attribute, true));
    }
}
