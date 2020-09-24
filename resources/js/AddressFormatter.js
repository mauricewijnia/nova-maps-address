class AddressFormatter {

    /**
     * Format a Google Maps place
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
    }

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
    }
}

export default AddressFormatter
