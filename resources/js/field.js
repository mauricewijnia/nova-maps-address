Nova.booting((Vue, router, store) => {
  Vue.component('index-nova-maps-address', require('./components/IndexField'))
  Vue.component('detail-nova-maps-address', require('./components/DetailField'))
  Vue.component('form-nova-maps-address', require('./components/FormField'))
})
