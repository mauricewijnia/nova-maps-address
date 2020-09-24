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
            formatted: null
        }
    },

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
            types: this.field.types
        });

        this.maps.on('change', (data) => {
            this.value = data.value
            this.formatted = data.formatted
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
