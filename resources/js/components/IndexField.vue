<template>
    <span>{{ getValue() }}</span>
</template>

<script>
export default {
    props: ['resourceName', 'field'],
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
