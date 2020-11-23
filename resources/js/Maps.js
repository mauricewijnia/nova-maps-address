import AddressFormatter from './AddressFormatter'

class Maps {
    constructor(settings) {
        this.settings = {...settings}

        this.events = {}
        this.map = null
        this.autocomplete = null
        this.geocoder = null
        this.formatter = new AddressFormatter()

        this.onClick = this.onClick.bind(this)
        this.onChange = this.onChange.bind(this)
        this.onInput = this.onInput.bind(this)

        this.settings.input.addEventListener('input', this.onInput)

        window.initMap = () => {
            this.initializeServices()
            this.initializeAddress(this.settings.value)
        }
        this.appendScript()
    }

    initializeAddress(address) {
        if (address && address.latitude && address.longitude) {
            const location = {lat: address.latitude, lng: address.longitude}
            this.setMarker(location)

            this.map.panTo(location)
            this.map.setZoom(12)
        }
    }

    initializeServices() {
        this.map = new google.maps.Map(
            document.getElementById('nova-maps-address-container'),
            {
                zoom: this.settings.zoom,
                center: this.settings.center,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: false,
                ...this.settings.mapOptions,
            }
        )

        this.geocoder = new google.maps.Geocoder
        this.autocomplete = new google.maps.places.Autocomplete(this.settings.input, {
            types: this.settings.types,
            ...this.settings.autoCompleteOptions,
        })

        if(this.settings.allowMapClick) {
            this.map.addListener('click', this.onClick)
        }
        this.autocomplete.addListener('place_changed', this.onChange)
    }

    appendScript() {
        this.script = document.createElement('script')
        const extraParams = this.settings.scriptUrlParams ? `&${this.getUrlParamsFromObj(this.settings.scriptUrlParams)}` : '';
        this.script.src = `https://maps.googleapis.com/maps/api/js?key=${this.settings.key}&libraries=places&callback=initMap${extraParams}`
        this.script.id = 'nova-maps-address-script'
        this.script.defer = true

        document.head.appendChild(this.script);
    }

    getUrlParamsFromObj(params) {
        return Object.keys(params)
            .map(key => `${encodeURIComponent(key)}=${encodeURIComponent(params[key])}`)
            .join('&');
    }

    onChange() {
        const place = this.autocomplete.getPlace()

        this.setMarker(place.geometry.location)

        this.map.panTo(place.geometry.location)
        this.map.setZoom(12)

        this.emit('change', {
            value: JSON.stringify(this.formatter.format(place)),
            formatted: place.formatted_address
        })
    }

    onInput(e) {
        this.reset()
        this.emit('change', { formatted: e.target.value })
    }

    onClick(data) {
        this.setMarker(data.latLng)
        this.geocoder.geocode({
            location: data.latLng,
            ...this.settings.geocodeOptions
        }, (data, status) => {
            const place = data[0]
            if (place && status === google.maps.places.PlacesServiceStatus.OK) {
                this.setMarker(place.geometry.location)

                this.emit('change', {
                    value: JSON.stringify(this.formatter.format(place)),
                    formatted: place.formatted_address
                })
            }
        })
    }

    reset() {
        this.emit('change', { value: null })

        if (this.marker) {
            this.marker.setMap(null)
        }
    }

    setMarker(position) {
        if (this.marker) {
            this.marker.setMap(null)
        }

        this.marker = new google.maps.Marker({
            position: position,
            animation: google.maps.Animation.DROP,
            map: this.map,
        })
    }

    on(event, callback) {
        if (!this.events[event]) {
            this.events[event] = []
        }

        this.events[event].push(callback)
    }

    emit(event, data) {
        if (!this.events[event]) {
            return
        }

        this.events[event].forEach((callback) => callback(data))
    }

    destroy() {
        window.google = undefined
        this.script.remove()
    }
}

export default Maps
