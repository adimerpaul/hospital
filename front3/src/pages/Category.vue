<template>
<q-page>
  <q-card>
    <q-card-section>
      <q-table :rows="categories" dense :columns="categoriesColumns" :filter="categoryFilter" :rows-per-page-options="[0]">
        <template v-slot:top-right>
          <q-btn @click="categoryAdd" :loading="loading" color="primary" label="Crear categoria" no-caps icon="add_circle_outline" dens/>
          <q-input v-model="categoryFilter" label="Filtrar" dense outlined>
            <template v-slot:append>
              <q-icon name="search" />
            </template>
          </q-input>
        </template>
        <template v-slot:body-cell-options="props">
          <q-td :props="props" auto-width>
            <q-btn-dropdown label="Opciones" color="primary" no-caps >
              <q-list>
                <q-item clickable v-close-popup @click="categoryUpdateClick(props.row)">
                  <q-item-section avatar>
                    <q-avatar icon="o_edit" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Modificar</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item clickable v-close-popup @click="categoryDelete(props.row)">
                  <q-item-section avatar>
                    <q-avatar icon="o_delete" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Eliminar</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item clickable v-close-popup @click="categoryUpdateClickPhoto(props.row)">
                  <q-item-section avatar>
                    <q-avatar icon="o_photo" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Cambiar foto</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-btn-dropdown>
          </q-td>
        </template>
        <template v-slot:body-cell-color="props">
          <q-td :props="props">
            <q-chip class="text-bold text-white" :style="`background: ${props.row.color};`">{{props.row.color}}</q-chip>
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
    <q-dialog v-model="categoryDialog" persistent >
      <q-card style="width: 700px; max-width: 90vw;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Crear categoria</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <q-form @submit="categoryCreate" @reset="categoryDialog = false" class="q-gutter-md">
            <div class="row">
              <div class="col-12 col-md-6">
                <div class="row">
                  <div class="col-12">
                    <q-input v-model="category.name" name="form" label="Nombre" dense outlined required />
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
              <div class="col-12 col-md-6 flex flex-center">
                <q-color v-model="category.color"  style="max-width:100px" />
              </div>
            </div>
            <q-card-actions align="right">
              <q-btn :loading="loading" icon="o_delete" label="Cancelar" color="red" v-close-popup  />
              <q-btn :loading="loading" label="Crear Categoria" color="green" icon="check_circle_outline" type="submit" />
            </q-card-actions>
          </q-form>
        </q-card-section>

      </q-card>
      </q-dialog>
    <q-dialog v-model="categoryDialogUpdate" persistent >
      <q-card style="width: 700px; max-width: 90vw;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Modificar categoria</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <q-form @submit="categoryUpdate" @reset="categoryDialog = false" class="q-gutter-md">
            <div class="row">
              <div class="col-12 col-md-6">
                <q-input v-model="category.name" name="form" label="Nombre" dense outlined required />
              </div>
              <div class="col-12 col-md-6 flex flex-center">
                <q-color v-model="category.color"  style="max-width:100px" />
              </div>
            </div>
            <q-card-actions align="right">
              <q-btn :loading="loading" icon="o_delete" label="Cancelar" color="red" v-close-popup  />
              <q-btn :loading="loading" label="Modificar Categoria" color="green" icon="check_circle_outline" type="submit" />
            </q-card-actions>
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
    <q-dialog v-model="categoryDialogUpdatePhoto" persistent >
      <q-card style="width: 700px; max-width: 90vw;">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Modificar categoria</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section>
          <q-form @submit="categoryUpdatePhoto" @reset="categoryDialog = false" class="q-gutter-md">
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
              <q-btn :loading="loading" label="Modificar Categoria" color="green" icon="check_circle_outline" type="submit" />
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

export default {
  name: 'CategoryItem',
  data () {
    return {
      url: process.env.API,
      categoryDialog: false,
      categoryDialogUpdatePhoto: false,
      categoryDialogUpdate: false,
      store: useCounterStore(),
      categories: [],
      category: {},
      categoryPhoto: '',
      categoryFilter: '',
      loading: false,
      categoriesColumns: [
        { name: 'options', label: 'Opciones', field: 'options', align: 'left', sortable: true },
        { name: 'name', label: 'Nombre', field: 'name', align: 'left', sortable: true },
        { name: 'color', label: 'Color', field: 'color', align: 'left', sortable: true },
        { name: 'photo', label: 'Foto', field: 'photo', align: 'left', sortable: true }
      ]
    }
  },
  created () {
    this.categoriesGet()
  },
  methods: {
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
        this.categoryPhoto = response.data
        this.loading = false
        dialog.hide()
      }).catch(error => {
        console.log(error)
        this.loading = false
        dialog.hide()
      })
    },
    categoryCreate () {
      if (this.categoryPhoto === '') {
        this.$q.notify({
          message: 'Debe subir una foto',
          color: 'red',
          position: 'top',
          icon: 'warning'
        })
        return false
      }
      if (this.category.color === '' || this.category.color === undefined) {
        this.$q.notify({
          message: 'Debe ingresar un color',
          color: 'red',
          position: 'top',
          icon: 'warning'
        })
        return false
      }
      this.category.photo = this.categoryPhoto
      this.category.hospital_id = this.store.user.hospital_id
      this.$api.post('categories', this.category).then(response => {
        this.categories.push(response.data)
        this.categoryDialog = false
      }).catch(error => {
        this.$q.notify({
          color: 'negative',
          message: error.response.data.message,
          icon: 'report_problem',
          position: 'top'
        })
      })
    },
    categoryUpdate () {
      this.loading = true
      this.$api.put('categories/' + this.category.id, this.category).then(response => {
        this.categoriesGet()
        this.loading = false
        this.categoryDialogUpdate = false
        this.$q.notify({
          color: 'positive',
          message: 'Categoria modificada correctamente',
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
    categoryUpdatePhoto () {
      if (this.categoryPhoto === '') {
        this.$q.notify({
          message: 'Debe subir una foto',
          color: 'red',
          position: 'top',
          icon: 'warning'
        })
        return false
      }
      this.loading = true
      this.category.photo = this.categoryPhoto
      this.$api.put('categories/' + this.category.id, this.category).then(response => {
        this.categoriesGet()
        this.loading = false
        this.categoryDialogUpdatePhoto = false
        this.$q.notify({
          color: 'positive',
          message: 'Categoria modificada correctamente',
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
    categoryDelete (category) {
      this.$q.dialog({
        title: 'Eliminar categoria',
        message: '¿Esta seguro que desea eliminar esta categoria?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.loading = true
        this.$api.delete('categories/' + category.id).then(response => {
          this.categoriesGet()
          this.loading = false
          this.categoryDialogUpdate = false
          this.$q.notify({
            color: 'positive',
            message: 'Categoria eliminada correctamente',
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
      }).onCancel(() => {
        console.log('Cancel')
      }).onDismiss(() => {
        console.log('Dismissed')
      })
    },
    categoryUpdateClickPhoto (category) {
      this.category = category
      this.categoryDialogUpdatePhoto = true
    },
    categoryUpdateClick (category) {
      this.category = category
      this.categoryDialogUpdate = true
    },
    categoryAdd () {
      this.category = {}
      this.categoryDialog = true
    },
    categoriesGet () {
      this.$api.get('categories')
        .then(response => {
          this.categories = response.data
        })
        .catch(error => {
          console.log(error)
          this.$q.notify({
            color: 'negative',
            message: error.response.data.message,
            icon: 'report_problem',
            position: 'top'
          })
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
