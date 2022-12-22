<template>
<q-page>
  <q-card>
    <q-card-section>
      <q-table :rows="products" dense :columns="productsColumns" :filter="productFilter" :rows-per-page-options="[0]">
        <template v-slot:top-right>
          <q-btn @click="productAdd" :loading="loading" color="primary" label="Crear producto" no-caps icon="add_circle_outline" dens/>
          <q-btn flat icon="refresh" @click="productsGet" />
          <q-input v-model="productFilter" label="Buscar" dense outlined>
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
        <template v-slot:body-cell-options="props">
          <q-td :props="props" auto-width>
            <q-btn-dropdown label="Opciones" color="primary" no-caps >
              <q-list>
                <q-item clickable v-close-popup @click="productUpdateClick(props.row)">
                  <q-item-section avatar>
                    <q-avatar icon="o_edit" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Modificar</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item clickable v-close-popup @click="productDelete(props.row)">
                  <q-item-section avatar>
                    <q-avatar icon="o_delete" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Eliminar</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item clickable v-close-popup @click="productUpdateClickPhoto(props.row)">
                  <q-item-section avatar>
                    <q-avatar icon="o_photo" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Cambiar foto</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item clickable v-close-popup @click="productAddLotClick(props.row)">
                  <q-item-section avatar>
                    <q-avatar icon="add_circle_outline" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Agregar lote</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-btn-dropdown>
          </q-td>
        </template>
        <template v-slot:body-cell-stock="props">
          <q-td :props="props">
            <q-circular-progress
              show-value
              :class="`text-${props.row.stock <= props.row.stockMin ? 'red' : props.row.stock >= props.row.stockMax ? 'green' : 'blue'} q-ma-md text-bold`"
              :value="props.row.stock"
              size="50px"
              :max="props.row.stockMax<=props.row.stock ? props.row.stock : props.row.stockMax"
              :min="props.row.stockMin>=props.row.stock ? props.row.stock : props.row.stockMin"
            />
            <span class="text-bold">{{ props.row.stock - props.row.stockMin}} Restantes</span>
          </q-td>
        </template>
        <template v-slot:body-cell-color="props">
          <q-td :props="props">
            <q-chip class="text-bold text-white" :style="`background: ${props.row.color};`">{{props.row.color}}</q-chip>
          </q-td>
        </template>
        <template v-slot:body-cell-lot="props">
          <q-td :props="props">
            <ul style="padding: 0; margin: 0;">
              <li style="padding: 0; margin: 0;" v-for="lot in props.row.lots" :key="lot.id">
                <div><b>U:</b>{{lot.quantity}} <b>L:</b>{{lot.batch}} <b>V:</b>
                  <q-chip text-color="white" dense :color="restanteDays( lot.expiration)<=15?'red':'green'">{{ restanteDays( lot.expiration)}} <b>Dias</b></q-chip>
                  <q-btn round flat icon="o_delete" class="q-pa-none q-ma-none" @click="productDeleteLot( lot)" dense/>
                  <q-btn round flat icon="o_edit" class="q-pa-none q-ma-none" @click="productUpdateLot( lot)" dense/>
                </div>
              </li>
            </ul>
          </q-td>
        </template>
        <template v-slot:body-cell-photo="props">
          <q-td :props="props" class="text-center">
            <a :href="`${url}../imagenes/${props.row.photo}`" target="_blank">
              <div class="text-center" :style="`background: ${props.row.color}`">
                <q-img :src="`${url}../imagenes/${props.row.photo}`" style="width: 40px; height: 40px; border-radius: 50%;"/>
              </div>
            </a>
          </q-td>
        </template>
      </q-table>
    </q-card-section>
    <q-dialog v-model="productDialog" persistent >
      <q-card style="width: 700px; max-width: 90vw;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Crear producto</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <q-form @submit="productCreate" @reset="productDialog = false" class="q-gutter-md">
            <div class="row">
              <div class="col-12 col-md-6 q-px-xs">
                <div class="row">
                  <div class="col-12">
                    <q-select v-model="category" :options="categories" label="Producto" dense outlined required hint="" />
                    <q-input v-model="product.name" name="form" label="Nombre" dense outlined required hint="" />
                    <q-input v-model="product.priceCost" name="form" label="Precio de costo" dense outlined required hint="" type="number" step="0.1" />
                    <q-input v-model="product.priceSale" name="form" label="Precio de venta" dense outlined required hint="" type="number" step="0.1"/>
                    <q-input v-model="product.expiration" name="form" label="Fecha de caducidad" dense outlined required hint="" type="date"/>
                    <q-input v-model="product.batch" name="form" label="Lote" dense outlined required hint="" />
                  </div>
                  <div class="col-12 flex flex-center">
                    <q-uploader
                      accept=".jpg, .png"
                      @added="uploadFile"
                      auto-upload
                      max-files="1"
                      label="Subir foto"
                      flat
                      max-file-size="5000000"
                      @rejected="onRejected"
                      bordered
                    />
                  </div>
                </div>

              </div>
              <div class="col-12 col-md-6 q-px-xs">
                <div class="row">
                  <div class="col-12">
                    <q-input v-model="product.stock" name="form" label="Stock" dense outlined type="number" required hint="" />
                    <q-input v-model="product.stockMin" name="form" label="Stock minimo" dense outlined type="number" required hint="" />
                    <q-input v-model="product.stockMax" name="form" label="Stock maximo" dense outlined type="number" required hint="" />
                  </div>
                  <div class="col-12 flex flex-center">
                    <q-color v-model="product.color"  style="max-width:100px" />
                  </div>
                </div>
              </div>
            </div>
            <q-card-actions align="right">
              <q-btn :loading="loading" icon="o_delete" label="Cancelar" color="red" v-close-popup  />
              <q-btn :loading="loading" label="Crear Producto" color="green" icon="check_circle_outline" type="submit" />
            </q-card-actions>
          </q-form>
        </q-card-section>

      </q-card>
      </q-dialog>
    <q-dialog v-model="productDialogUpdate" persistent >
      <q-card style="width: 700px; max-width: 90vw;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Modificar producto</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <q-form @submit="productUpdate" @reset="productDialog = false" class="q-gutter-md">
            <div class="row">
              <div class="col-12 col-md-6 q-px-xs">
                <div class="row">
                  <div class="col-12">
                    <q-select v-model="category" :options="categories" label="Producto" dense outlined required hint="" />
                    <q-input v-model="product.name" name="form" label="Nombre" dense outlined required hint="" />
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 q-px-xs">
                <div class="row">
                  <div class="col-12">
                    <q-input v-model="product.stock" name="form" label="Stock" dense outlined type="number" required hint="" />
                    <q-input v-model="product.stockMin" name="form" label="Stock minimo" dense outlined type="number" required hint="" />
                    <q-input v-model="product.stockMax" name="form" label="Stock maximo" dense outlined type="number" required hint="" />
                  </div>
                  <div class="col-12 flex flex-center">
                    <q-color v-model="product.color"  style="max-width:100px" />
                  </div>
                </div>
              </div>
            </div>
            <q-card-actions align="right">
              <q-btn :loading="loading" icon="o_delete" label="Cancelar" color="red" v-close-popup  />
              <q-btn :loading="loading" label="Modificar Producto" color="green" icon="check_circle_outline" type="submit" />
            </q-card-actions>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="productDialogUpdateLote" persistent >
      <q-card style="width: 350px; max-width: 90vw;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Modificar lote</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <q-form @submit="lotUpdate" class="q-gutter-md">
            <div class="row">
              <div class="col-12 col-md-12 q-px-xs">
                <div class="row">
                  <div class="col-12">
                    <q-input v-model="lot.quantity" name="form" label="Cantidad" dense outlined type="number" required hint="" />
                    <q-input v-model="lot.priceCost" name="form" label="Precio de costo" dense outlined type="number" step="0.1" required hint="" />
                    <q-input v-model="lot.priceSale" name="form" label="Precio de venta" dense outlined type="number" step="0.1" required hint="" />
                    <q-input v-model="lot.expiration" name="form" label="Fecha de caducidad" dense outlined type="date" required hint="" />
                    <q-input v-model="lot.batch" name="form" label="Lote" dense outlined type="text" required hint="" />
                  </div>
                </div>
              </div>
            </div>
            <q-card-actions align="right">
              <q-btn :loading="loading" icon="o_delete" label="Cancelar" color="red" v-close-popup  />
              <q-btn :loading="loading" label="Modificar lote" color="green" icon="check_circle_outline" type="submit" />
            </q-card-actions>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="productAddLotDialog" persistent >
      <q-card style="width: 350px; max-width: 90vw;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Agregar lote</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <q-form @submit="lotInsert" class="q-gutter-md">
            <div class="row">
              <div class="col-12 col-md-12 q-px-xs">
                <div class="row">
                  <div class="col-12">
                    <q-input v-model="lot.quantity" name="form" label="Cantidad" dense outlined type="number" required hint="" />
                    <q-input v-model="lot.priceCost" name="form" label="Precio de costo" dense outlined type="number" step="0.1" required hint="" />
                    <q-input v-model="lot.priceSale" name="form" label="Precio de venta" dense outlined type="number" step="0.1" required hint="" />
                    <q-input v-model="lot.expiration" name="form" label="Fecha de caducidad" dense outlined type="date" required hint="" />
                    <q-input v-model="lot.batch" name="form" label="Lote" dense outlined type="text" required hint="" />
                  </div>
                </div>
              </div>
            </div>
            <q-card-actions align="right">
              <q-btn :loading="loading" icon="o_delete" label="Cancelar" color="red" v-close-popup  />
              <q-btn :loading="loading" label="Agregar lote" color="green" icon="check_circle_outline" type="submit" />
            </q-card-actions>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="productDialogUpdatePhoto" persistent >
      <q-card style="width: 700px; max-width: 90vw;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Modificar producto</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <q-form @submit="productUpdatePhoto" @reset="productDialog = false" class="q-gutter-md">
            <div class="row">
              <div class="col-12 flex flex-center">
                <q-uploader
                  accept=".jpg, .png"
                  @added="uploadFile"
                  auto-upload
                  max-files="1"
                  label="Subir foto"
                  flat
                  max-file-size="5000000"
                  @rejected="onRejected"
                  bordered
                />
              </div>
            </div>
            <q-card-actions align="right">
              <q-btn :loading="loading" icon="o_delete" label="Cancelar" color="red" v-close-popup  />
              <q-btn :loading="loading" label="Modificar Producto" color="green" icon="check_circle_outline" type="submit" />
            </q-card-actions>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-card>
</q-page>
</template>

<script>
import { useCounterStore } from 'stores/example-store'
import { date } from 'quasar'
import moment from 'moment'
export default {
  name: 'CategoryItem',
  data () {
    return {
      productDialogUpdateLote: false,
      productAddLotDialog: false,
      url: process.env.API,
      productDialog: false,
      productDialogUpdatePhoto: false,
      productDialogUpdate: false,
      store: useCounterStore(),
      products: [],
      product: {},
      lot: {},
      categories: [],
      category: {},
      productPhoto: '',
      productFilter: '',
      loading: false,
      productsColumns: [
        { name: 'options', label: 'OPCIONES', field: 'options', align: 'left', sortable: true },
        { name: 'name', label: 'NOMBRE', field: 'name', align: 'left', sortable: true },
        { name: 'lot', label: 'LOTE', field: 'lot', align: 'left', sortable: true },
        { name: 'stock', label: 'STOCK', field: 'stock', align: 'left', sortable: true },
        { name: 'color', label: 'COLOR', field: 'color', align: 'left', sortable: true },
        { name: 'photo', label: 'FOTO', field: 'photo', align: 'left', sortable: true },
        { name: 'category', label: 'CATEGORIA', field: (row) => row.category.name, align: 'left', sortable: true }
      ]
    }
  },
  created () {
    this.productsGet()
    this.$api.get('categories').then(response => {
      response.data.forEach(item => {
        this.categories.push({ label: item.name, value: item.id })
      })
      this.category = this.categories[0]
    })
  },
  methods: {
    lotInsert () {
      this.loading = true
      this.$api.post('lots', this.lot).then(response => {
        this.loading = false
        this.productAddLotDialog = false
        this.productsGet()
      }).catch(error => {
        this.loading = false
        this.$q.notify({
          message: error.response.data.message,
          color: 'negative',
          icon: 'report_problem',
          position: 'top'
        })
      })
    },
    lotUpdate () {
      this.loading = true
      this.$api.put('lots/' + this.lot.id, this.lot).then(response => {
        this.loading = false
        this.productDialogUpdateLote = false
        this.productsGet()
      }).catch(error => {
        this.loading = false
        this.$q.notify({
          message: error.response.data.message,
          color: 'negative',
          icon: 'report_problem',
          position: 'top'
        })
      })
    },
    productUpdateLot (lot) {
      this.productDialogUpdateLote = true
      this.lot = lot
    },
    productDeleteLot (lot) {
      this.$q.dialog({
        title: 'Eliminar',
        message: '¿Estas seguro de eliminar el lote?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.loading = true
        this.$api.delete('lots/' + lot.id).then(response => {
          this.productsGet()
        })
      }).onCancel(() => {
      }).onDismiss(() => {
      })
    },
    restanteDays (date) {
      const today = moment()
      const expiration = moment(date)
      const days = expiration.diff(today, 'days')
      return days
    },
    uploadFile (file) {
      this.loading = true
      let percentage = 0
      const dialog = this.$q.dialog({
        title: 'Subiendo archivo',
        message: 'Espere por favor... ' + percentage + '%',
        persistent: true
      })
      const formData = new FormData()
      formData.append('file', file[0])
      this.$api.post('upload', formData, {
        onUploadProgress: progressEvent => {
          percentage = parseInt(Math.round((progressEvent.loaded * 100) / progressEvent.total))
          dialog.update({ progress: { value: percentage } })
        }
      }).then(response => {
        this.productPhoto = response.data
        this.loading = false
        dialog.hide()
      }).catch(error => {
        console.log(error)
        this.loading = false
        dialog.hide()
      })
    },
    productCreate () {
      if (this.productPhoto === '') {
        this.$q.notify({
          message: 'Debe subir una foto',
          color: 'red',
          position: 'top',
          icon: 'warning'
        })
        return false
      }
      if (this.product.color === '' || this.product.color === undefined) {
        this.$q.notify({
          message: 'Debe ingresar un color',
          color: 'red',
          position: 'top',
          icon: 'warning'
        })
        return false
      }
      this.product.photo = this.productPhoto
      this.product.category_id = this.category.value
      this.product.hospital_id = this.store.user.hospital_id
      this.$api.post('products', this.product).then(response => {
        this.productsGet()
        this.productDialog = false
      }).catch(error => {
        this.$q.notify({
          color: 'negative',
          message: error.response.data.message,
          icon: 'report_problem',
          position: 'top'
        })
      })
    },
    productUpdate () {
      this.loading = true
      this.$api.put('products/' + this.product.id, this.product).then(response => {
        this.productsGet()
        this.loading = false
        this.productDialogUpdate = false
        this.$q.notify({
          color: 'positive',
          message: 'Producto modificada correctamente',
          icon: 'check_circle_outline',
          position: 'top'
        })
      }).catch(error => {
        this.$q.notify({
          color: 'negative',
          message: error.response.data.message,
          icon: 'report_problem',
          position: 'top'
        })
      }).finally(() => {
        this.loading = false
      })
    },
    productUpdatePhoto () {
      if (this.productPhoto === '') {
        this.$q.notify({
          message: 'Debe subir una foto',
          color: 'red',
          position: 'top',
          icon: 'warning'
        })
        return false
      }
      this.loading = true
      this.product.photo = this.productPhoto
      this.$api.put('products/' + this.product.id, this.product).then(response => {
        this.productsGet()
        this.loading = false
        this.productDialogUpdatePhoto = false
        this.$q.notify({
          color: 'positive',
          message: 'Producto modificada correctamente',
          icon: 'check_circle_outline',
          position: 'top'
        })
      }).catch(error => {
        this.$q.notify({
          color: 'negative',
          message: error.response.data.message,
          icon: 'report_problem',
          position: 'top'
        })
      })
    },
    productDelete (product) {
      this.$q.dialog({
        title: 'Eliminar producto',
        message: '¿Esta seguro que desea eliminar esta producto?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.loading = true
        this.$api.delete('products/' + product.id).then(response => {
          this.productsGet()
          this.loading = false
          this.productDialogUpdate = false
          this.$q.notify({
            color: 'positive',
            message: 'Producto eliminada correctamente',
            icon: 'check_circle_outline',
            position: 'top'
          })
        }).catch(error => {
          this.$q.notify({
            color: 'negative',
            message: error.response.data.message,
            icon: 'report_problem',
            position: 'top'
          })
        }).finally(() => {
          this.loading = false
        })
      }).onCancel(() => {
        console.log('Cancel')
      }).onDismiss(() => {
        console.log('Dismissed')
      })
    },
    productAddLotClick (product) {
      this.product = product
      this.lot = {
        product_id: product.id,
        hospital_id: product.hospital_id,
        quantity: 0,
        priceCost: 0,
        priceSale: 0,
        expiration: moment().format('YYYY-MM-DD'),
        batch: ''
      }
      this.productAddLotDialog = true
    },
    productUpdateClickPhoto (product) {
      this.product = product
      this.productDialogUpdatePhoto = true
    },
    productUpdateClick (product) {
      this.product = product
      this.productDialogUpdate = true
    },
    productAdd () {
      this.product = {
        expiration: date.formatDate(new Date(), 'YYYY-MM-DD')
      }
      this.productDialog = true
    },
    productsGet () {
      this.loading = true
      this.$api.get('products')
        .then(response => {
          this.products = response.data
        })
        .catch(error => {
          this.$q.notify({
            color: 'negative',
            message: error.response.data.message,
            icon: 'report_problem',
            position: 'top'
          })
        }).finally(() => {
          this.loading = false
        })
    },
    onRejected (rejectedEntries) {
      this.$q.notify({
        type: 'negative',
        message: `${rejectedEntries.length} el archivo paso las restricciones de validación`
      })
    }
  }
}
</script>

<style scoped>

</style>
