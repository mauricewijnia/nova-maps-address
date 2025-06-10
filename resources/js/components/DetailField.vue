<template>
  <PanelItem :index="index" :field="field">
    <template #value>
      {{ getValue() }}
    </template>
  </PanelItem>
</template>

<script>
export default {
  props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
  methods: {
    getValue() {
      if (this.field.value && this.field.value.formatted_address) {
        return this.field.value.formatted_address
      }

      return '-'
    },
  },
}
</script>
