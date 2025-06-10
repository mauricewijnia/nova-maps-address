import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-nova-maps-address', IndexField)
  app.component('detail-nova-maps-address', DetailField)
  app.component('form-nova-maps-address', FormField)
})
