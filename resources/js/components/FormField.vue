<template>
    <default-field :field="field" :errors="errors" :full-width-content="true" :show-help-text="showHelpText">
        <template slot="field">
            <input type="hidden" name="" :value="this.value">
            <input
                :id="field.name"
                type="text"
                class="w-full form-control form-input form-input-bordered nova-maps-address-input"
                :class="errorClasses"
                :placeholder="field.name"
                :value="this.formatted"
                ref="input"
            />
            <div id="nova-maps-address-container" ref="container"></div>

            <div class="flex flex-wrap w-full">
                <div class="flex w-1/2">
                    <div class="w-1/5 py-3 pl-2">
                        <label class="inline-block text-80 pt-2 leading-tight">Lat</label>
                    </div>
                    <div class="py-3 w-4/5">
                    <input type="number" step=any v-model="latitude" @change="refreshMap" class="w-full form-control form-input form-input-bordered nova-maps-address-input">
                    </div>
                </div>

                <div class="flex w-1/2">
                    <div class="w-1/5 py-3 pl-2">
                        <label class="inline-block text-80 pt-2 leading-tight">Long</label>
                    </div>
                    <div class="py-3 w-4/5">
                    <input  type="number" step=any v-model="longitude"  @change="refreshMap" class="w-full form-control form-input form-input-bordered nova-maps-address-input">
                    </div>
                </div>
            </div>
           
        </template>
    </default-field>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import Maps from '../Maps'

let timeout;

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            maps: null,
            value: null,
            formatted: null,
            latitude: null,
            longitude: null,
        }
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            const address = this.field.value
            this.formatted = address ? address.formatted_address : ''
            this.latitude = address ? address.latitude : ''
            this.longitude = address ? address.longitude : ''
            this.value = JSON.stringify(this.field.value) || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },

        refreshMap() {
            this.maps.updateMapGeocode(this.latitude, this.longitude)
        }
    },
    mounted() {
        this.setInitialValue()

        this.maps = new Maps({
            input: this.$refs.input,
            container: this.$refs.container,
            value: this.field.value,
            key: this.field.googleKey,
            zoom: this.field.zoom,
            center: this.field.center,
            types: this.field.types,
            autoCompleteOptions: this.field.autoCompleteOptions,
            scriptUrlParams: this.field.scriptUrlParams,
            mapOptions: this.field.mapOptions,
            allowMapClick: this.field.allowMapClick
        });

        this.maps.on('change', (data) => {
            this.value = data.value
            this.formatted = data.formatted
            this.latitude = data.latitude
            this.longitude = data.longitude
        })
    },
    destroyed() {
        this.maps.destroy()
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
