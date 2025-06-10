<template>
  <span>{{ fieldValue }}</span>
</template>

<script>
export default {
  props: ['resourceName', 'field'],

  computed: {
    fieldValue() {
      if (this.field.value && this.field.value.formatted_address) {
        return this.field.value.formatted_address
      }

      return '-'
    },
  }
}
</script>
