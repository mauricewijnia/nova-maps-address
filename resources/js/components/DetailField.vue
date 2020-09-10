<template>
    <panel-item :field="field">
        <template slot="value">
            {{ getValue() }}
        </template>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
    methods: {
        getValue() {
            if (this.field.value && this.field.value.formatted_address) {
                return this.field.value.formatted_address
            }

            return '-'
        }
    }
}
</script>
