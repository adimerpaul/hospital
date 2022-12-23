<template>
<q-page>
<q-table :rows="patients" :columns="patientColumns" :filter="filter" dense :rows-per-page-options="[0]">
<template v-slot:top-right>
  <q-btn color="primary" dense icon="add_circle_outline" label="Crear paciente" no-caps @click="patientAddClick" />
  <q-btn flat dense icon="refresh" no-caps @click="patientGet" />
  <q-input v-model="filter" label="Search" dense outlined >
    <template v-slot:append>
      <q-icon name="search" />
    </template>
  </q-input>
</template>
<template v-slot:body-cell-edad="props">
  <q-td :props="props" class="text-center">
    <div class="text-bold">{{calcularEdad(props.row.birthday)}}</div>
  </q-td>
</template>
  <template v-slot:body-cell-options="props">
    <q-td :props="props">
      <q-btn-dropdown label="Opciones" color="primary" no-caps >
        <q-list>
          <q-item clickable @click="patientEditClick(props.row)">
            <q-item-section avatar>
              <q-icon name="o_edit" />
            </q-item-section>
            <q-item-section>Editar</q-item-section>
          </q-item>
          <q-item clickable @click="patientDeleteClick(props.row)">
            <q-item-section avatar>
              <q-icon name="o_delete" />
            </q-item-section>
            <q-item-section>Eliminar</q-item-section>
          </q-item>
          <q-item clickable :to="`history/${props.row.id}`">
            <q-item-section avatar>
              <q-icon name="o_history" />
            </q-item-section>
            <q-item-section>Historial Clinico</q-item-section>
          </q-item>
        </q-list>
      </q-btn-dropdown>
    </q-td>
  </template>
</q-table>
<q-dialog v-model="patientShow">
<q-card style="width: 700px; max-width: 90vw;">
  <q-card-section class="row items-center">
    <div class="text-h6">Crear paciente</div>
    <q-space/>
    <q-btn flat icon="close" v-close-popup />
  </q-card-section>
  <q-card-section>
    <q-form @submit.prevent="patientSubmit">
      <div class="row">
        <div class="col-12 col-md-6">
          <q-input v-model="patient.name" label="Nombre" outlined hint="" dense required />
          <q-input v-model="patient.lastname" label="Apellido" outlined hint="" dense required />
          <q-select v-model="patient.sex" label="Sexo" outlined hint="" dense required :options="['MASCULINO', 'FEMENINO']" />
          <q-input v-model="patient.phone" label="Teléfono" outlined hint="" dense required />
        </div>
        <div class="col-12 col-md-6">
          <q-input v-model="patient.email" label="Email" outlined hint="" dense required />
          <q-input v-model="patient.address" label="Dirección" outlined hint="" dense required />
          <q-input v-model="patient.birthday" label="Fecha de nacimiento" type="date" outlined hint="" dense required />
          <div class="flex flex-center text-bold">
            {{edad}}
          </div>
        </div>
      </div>
      <q-card-actions align="right">
        <q-btn color="red" label="Cancelar" v-close-popup icon="o_delete" />
        <q-btn color="green" icon="check_circle_outline" label="Guardar" type="submit" />
      </q-card-actions>
    </q-form>
  </q-card-section>

</q-card>
</q-dialog>
</q-page>
</template>

<script>
import { date } from 'quasar'
import { useCounterStore } from 'stores/example-store'
import moment from 'moment'

export default {
  name: 'PatientItem',
  data () {
    return {
      store: useCounterStore(),
      filter: '',
      patientCreate: true,
      patientShow: false,
      url: process.env.API,
      patient: {
        birthday: date.formatDate(new Date(), 'YYYY-MM-DD'),
        sex: 'MASCULINO'
      },
      patients: [],
      patientColumns: [
        { name: 'options', label: 'OPCIONES', field: 'options', sortable: true },
        { name: 'name', label: 'NOMBRE', field: (row) => `${row.name}  ${row.lastname}`, sortable: true },
        { name: 'email', label: 'EMAIL', field: 'email', sortable: true },
        { name: 'birthday', label: 'FECHA DE NACIMIENTO', field: 'birthday', sortable: true },
        { name: 'edad', label: 'EDAD', field: 'edad', sortable: true },
        { name: 'phone', label: 'TELEFONO', field: 'phone', sortable: true },
        { name: 'address', label: 'DIRECCION', field: 'address', sortable: true }
      ]
    }
  },
  created () {
    this.patientGet()
  },
  methods: {
    calcularEdad (fecha) {
      const years = moment().diff(fecha, 'years')
      const months = moment().diff(fecha, 'months')
      const months2 = months - (years * 12)
      const days = moment().diff(fecha, 'days')
      const days2 = days - (months * 30)
      return ` ${years} años ${months2} meses ${days2} días`
    },
    patientAddClick () {
      this.patientCreate = true
      this.patientShow = true
      this.patient = {
        birthday: date.formatDate(new Date(), 'YYYY-MM-DD'),
        sex: 'MASCULINO'
      }
    },
    patientSubmit () {
      if (this.patientCreate) {
        this.patient.hospital_id = this.store.user.hospital_id
        this.$api.post('patients', this.patient).then(response => {
          this.patientShow = false
          this.patientGet()
        }).catch(error => {
          this.$q.notify({
            message: error.response.data.message,
            color: 'negative',
            icon: 'o_report_problem'
          })
        })
      } else {
        this.$api.put('patients/' + this.patient.id, this.patient).then(response => {
          this.patientShow = false
          this.patientGet()
        }).catch(error => {
          this.$q.notify({
            message: error.response.data.message,
            color: 'negative',
            icon: 'o_report_problem'
          })
        })
      }
    },
    patientDeleteClick (patient) {
      this.$q.dialog({
        title: 'Eliminar paciente',
        message: '¿Está seguro de eliminar este paciente?',
        ok: 'Eliminar',
        cancel: 'Cancelar',
        persistent: true
      }).onOk(() => {
        this.$api.delete('patients/' + patient.id).then(response => {
          this.patientGet()
        })
      })
    },
    patientEditClick (patient) {
      this.patientCreate = false
      this.patientShow = true
      this.patient = patient
    },
    patientGet () {
      this.$q.loading.show()
      this.$api.get('patients')
        .then(response => {
          this.patients = response.data
        })
        .catch(error => {
          console.log(error)
          this.$q.notify({
            color: 'negative',
            message: 'Error al obtener los pacientes',
            icon: 'error',
            position: 'top'
          })
        }).finally(() => {
          this.$q.loading.hide()
        })
    }
  },
  computed: {
    edad () {
      const years = moment().diff(this.patient.birthday, 'years')
      const months = moment().diff(this.patient.birthday, 'months')
      const months2 = months - (years * 12)
      const days = moment().diff(this.patient.birthday, 'days')
      const days2 = days - (months * 30)
      return ` ${years} años ${months2} meses ${days2} días`
    }
  }
}
</script>

<style scoped>

</style>
