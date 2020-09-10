<template>
    <default-field :field="field" :errors="errors" :full-width-content="true" :show-help-text="showHelpText">
        <template slot="field">
            <input type="hidden" name="" v-model="value">
            <input
                :id="field.name"
                type="text"
                class="w-full form-control form-input form-input-bordered nova-maps-address-input"
                :class="errorClasses"
                :placeholder="field.name"
                :value="this.formatted"
                @input="this.onInput"
            />
            <div id="nova-maps-address-container"></div>
        </template>
    </default-field>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'

let timeout;

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field', 'map', 'autocomplete', 'marker', 'src'],

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            const address = this.field.value
            this.formatted = address ? address.formatted_address : ''
            this.value = JSON.stringify(this.field.value) || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },
        onChange() {
            const place = this.autocomplete.getPlace()
            this.value = JSON.stringify(this.format(place))
            this.formatted = place.formatted_address

            this.setMarker(place.geometry.location)
        },
        onInput(e) {
            this.reset()
            this.formatted = e.target.value
        },
        reset() {
            this.value = null
            if (this.marker) {
                this.marker.setMap(null)
            }
        },
        /**
         * Format a place to the object that we will store in the database
         */
        format(place) {
            return {
                street_name: this.extract(place.address_components, 'route'),
                street_number: this.extract(place.address_components, 'street_number'),
                postal_code: this.extract(place.address_components, 'postal_code'),
                city: this.extract(place.address_components, 'locality'),
                country: this.extract(place.address_components, 'country'),
                formatted_address: place.formatted_address,
                latitude: place.geometry.location.lat(),
                longitude: place.geometry.location.lng()
            }
        },
        /**
         * Extract an address component from the components array
         */
        extract(components, type, format = 'long_name') {
            let result = null;
            components.forEach((component) => {
                if (component.types.includes(type)) {
                    result = component[format]
                }
            })
            return result;
        },
        setMarker(position) {
            if (this.marker) {
                this.marker.setMap(null)
            }

            this.marker = new google.maps.Marker({
                position: position,
                animation: google.maps.Animation.DROP,
                map: this.map,
            })

            this.map.panTo(position)
            this.map.setZoom(12)
        }
    },
    created() {
        this.src = `https://maps.googleapis.com/maps/api/js?key=${this.field.googleKey}&libraries=places&callback=initMap`

        /**
         * Add the Google Places API to the DOM
         */
        const places = document.createElement('script');
        places.src = this.src;
        places.defer = true;
        places.id = 'nova-maps-address-script'

        window.initMap = () => {
            this.map = new google.maps.Map(
                document.getElementById('nova-maps-address-container'),
                {
                    zoom: 9,
                    center: {lat: 52.370216, lng: 4.895168},
                    mapTypeControl: false,
                    streetViewControl: false,
                    fullscreenControl: false,
                }
            )

            const input = document.querySelector('.nova-maps-address-input')

            this.autocomplete = new google.maps.places.Autocomplete(input, {
                types: ['address']
            })

            this.autocomplete.addListener('place_changed', this.onChange)

            const address = this.field.value
            if (address && address.latitude && address.longitude) {
                this.setMarker({lat: address.latitude, lng: address.longitude})
            }
        };

        document.head.appendChild(places);
    },
    destroyed() {
        /**
         * Remove the google maps script from the DOM
         */
        const script = document.querySelector(`script[src="${this.src}"]`)
        if (script) {
            window.google = undefined
            script.remove()
        }
    }
}
</script>
<style>
#nova-maps-address-container {
    height: 400px;
    margin-top: 1rem;
    border-width: 1px;
    border-color: var(--60);
    border-radius: .5rem;
}
</style>
