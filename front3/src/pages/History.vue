<template>
<q-page>
  <div class="row q-pa-xs">
    <div class="col-12 col-md-5">
      <div class="row">
        <div class="col-12">
          <div class="text-uppercase text-h5 text-bold text-grey text-center">{{patient.name}} {{patient.lastname}}</div>
        </div>
        <div class="col-6 q-my-xs col-md-6 text-right text-bold q-pr-xs">F. de Nacimiento:</div>
        <div class="col-6 q-my-xs col-md-6"><div class="text-left text-bold text-grey" v-if="patient.birthday!=undefined">{{formatDate(patient.birthday)}}</div></div>
        <div class="col-6 q-my-xs col-md-6 text-right text-bold q-pr-xs">Edad:</div>
        <div class="col-6 q-my-xs col-md-6"><div class="text-left text-bold text-grey" v-if="patient.birthday!=undefined">{{formatDateAge(patient.birthday)}}</div></div>
        <div class="col-6 q-my-xs col-md-6 text-right text-bold q-pr-xs">Sexo:</div>
        <div class="col-6 q-my-xs col-md-6"><div class="text-left text-bold text-grey text-lowercase">{{(patient.sex)}}</div></div>
        <div class="col-6 q-my-xs col-md-6 text-right text-bold q-pr-xs">Direccion:</div>
        <div class="col-6 q-my-xs col-md-6"><div class="text-left text-bold text-grey text-lowercase">{{(patient.address)}}</div></div>
        <div class="col-6 q-my-xs col-md-6 text-right text-bold q-pr-xs">Correo:</div>
        <div class="col-6 q-my-xs col-md-6"><div class="text-left text-bold text-grey">{{patient.email}}</div></div>
        <div class="col-6 q-my-xs col-md-6 text-right text-bold q-pr-xs">Tel:</div>
        <div class="col-6 q-my-xs col-md-6"><div class="text-left text-bold text-grey">{{patient.phone}}</div></div>
      </div>
    </div>
    <div class="col-12 col-md-7">
      <div class="row">
        <div class="col-6">
          <span :class="`${$q.screen.lt.md?'text-subtitle2':'text-h5'} text-bold text-center`">
            <q-icon name="o_monitor_heart" class="q-mr-xs text-subtitle2" size="1.4em" color="grey-8" />
            HISTORIAL CLINICO
          </span>
        </div>
        <div class="col-6 text-right">
          <q-btn class="q-mr-xs" color="primary" label="Nuevo" icon="add_circle_outline" dense @click="historyAddClick" />
        </div>
        <div class="col-12">
          <q-table bordered flat  :data="histories" dense :columns="historyColumns" row-key="id"  :loading="loading" :rows-per-page-options="[0, 10, 15, 20]">
            <template v-slot:body-cell-date="props">
              <div class="text-left">{{formatDate(props.value)}}</div>
            </template>
            <template v-slot:body-cell-options="props">
              <q-td :props="props" auto-width>
                <q-btn-dropdown flat icon="more_vert" color="grey-8">
                  <q-list>
                    <q-item clickable v-close-popup @click="editHistory(props.row)">
                      <q-item-section avatar>
                        <q-icon name="edit" color="grey-8" />
                      </q-item-section>
                      <q-item-section>Editar</q-item-section>
                    </q-item>
                    <q-item clickable v-close-popup @click="deleteHistory(props.row)">
                      <q-item-section avatar>
                        <q-icon name="delete" color="grey-8" />
                      </q-item-section>
                      <q-item-section>Eliminar</q-item-section>
                    </q-item>
                  </q-list>
                </q-btn-dropdown>
              </q-td>
            </template>
          </q-table>
        </div>
      </div>
    </div>
  </div>
  <q-dialog v-model="historyShow" persistent full-width full-height>
    <q-card class="q-pa-md" >
      <q-card-section class="row items-center q-py-none bg-primary">
        <div class="text-center text-h5 text-bold text-white">Consulta Medica</div>
        <q-space />
        <q-btn icon="close" color="white" flat round dense v-close-popup />
      </q-card-section>
      <q-card-section class="items-center q-pa-none" >
        <div class="row">
          <div class="col-3">
            <div class="row bg-primary">
              <div class="col-12">
                <q-btn label="Signos vitales" no-caps align="left" class="full-width bg-primary text-white" unelevated icon="favorite_outline" flat  />
              </div>
              <div class="col-12">
                <q-btn label="Preguntas ginecologias" no-caps align="left" class="full-width bg-primary text-white" icon="help_outline" flat  />
              </div>
              <div class="col-12">
                <q-btn label="Preguntas generales" no-caps align="left" class="full-width bg-primary text-white" icon="question_mark" flat  />
              </div>
              <div class="col-12">
                <q-btn label="Notas medicas" no-caps align="left" class="full-width bg-primary text-white" icon="o_note" flat  />
              </div>
              <div class="col-12">
                <q-btn label="Exploracion fisica" no-caps align="left" class="full-width bg-primary text-white" icon="speed" flat  />
              </div>
              <div class="col-12">
                <q-btn label="Diagnostico importante" no-caps align="left" class="full-width bg-primary text-white" icon="bubble_chart" flat  />
              </div>
              <div class="col-12">
                <q-btn label="Medicamento" no-caps align="left" class="full-width bg-primary text-white" icon="o_vaccines" flat  />
              </div>
              <div class="col-12">
                <q-btn label="Receta medica" no-caps align="left" class="full-width bg-primary text-white" icon="o_description" flat  />
              </div>
              <div class="col-12">
                <q-btn label="Observaciones" no-caps align="left" class="full-width bg-primary text-white" icon="o_contact_support" flat  />
              </div>
              <div class="col-12">
                <q-btn label="Infomacion Laboral" no-caps align="left" class="full-width bg-primary text-white" icon="work_outline" flat  />
              </div>
            </div>
          </div>
          <div class="col-9 q-pa-xs">
<!--            <q-scroll-area class="full-width full-height">-->
<!--              <div v-for="n in 100" :key="n" class="q-py-xs">-->
                <div class="row">
                  <div class="col-12 bg-primary q-pa-xs text-white"> <q-icon name="favorite_outline" /> Signos vitales</div>
                  <div class="col-12">
                    <div class="row">
                      <div class="col-6 col-md-2">
                        <q-input dense outlined label="Peso Kg" v-model="history.weight" />
                      </div>
                      <div class="col-6 col-md-2">
                        <q-input dense outlined label="Altura Mts" v-model="history.height" />
                      </div>
                      <div class="col-6 col-md-2">
                        <q-input dense outlined label="Sat02%" v-model="history.sat" />
                      </div>
                      <div class="col-6 col-md-2">
                        <q-input dense outlined label="Temperatura" v-model="history.tem" />
                      </div>
                      <div class="col-6 col-md-2">
                        <q-input readonly dense outlined label="IMC" v-model="caculateIMC" />
                      </div>
                      <div class="col-6 col-md-2">
                        <q-input readonly dense outlined label="IMC" v-model="caculateIMCText" />
                      </div>
                      <div class="col-6 col-md-2">
                        <q-input dense outlined label="Ten. Arterial" v-model="history.ta" />
                      </div>
                      <div class="col-6 col-md-2">
                        <q-input dense outlined label="Frec. Cardiaca" v-model="history.fc" />
                      </div>
                      <div class="col-6 col-md-2">
                        <q-input dense outlined label="Frec. Respiratoria" v-model="history.fr" />
                      </div>
                      <div class="col-6 col-md-2">
                        <q-select dense outlined label="Tipo de sangre" v-model="history.blood" :options="[ 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-' ]" />
                      </div>
                    </div>
                  </div>
                  <div class="col-12 bg-primary q-pa-xs text-white"> <q-icon name="help_outline" /> Prenguntas ginecologicas</div>
                  <div class="col-12">
                    <div class="row">
                      <div class="col-6 col-md-2">
                        <div> ¿Esta embarazada? </div>
                        <q-radio dense v-model="history.pregnant" checked-icon="task_alt" unchecked-icon="panorama_fish_eye" val="Si" label="SI" />
                        <q-radio dense v-model="history.pregnant" checked-icon="task_alt" unchecked-icon="panorama_fish_eye" val="No" label="No" />
                      </div>
                      <div class="col-6 col-md-2">
                        <q-input dense outlined label="Altura Mts" v-model="history.height" />
                      </div>
                      <div class="col-6 col-md-2">
                        <q-input dense outlined label="FUM" v-model="history.fum" />
                      </div>
                      <div class="col-6 col-md-2">
                        <q-input dense outlined label="SDG" v-model="history.sdg" />
                      </div>
                      <div class="col-6 col-md-2">
                        <q-input dense outlined label="FU" v-model="history.fu" />
                      </div>
                    </div>
                  </div>
                  <div class="col-12 bg-primary q-pa-xs text-white"> <q-icon name="question_mark" /> Preguntas generales</div>
                  <div class="col-12">
                    <div class="row">
                      <div class="col-6 col-md-4">
                        <div> ¿Fuma? </div>
                        <q-radio dense v-model="history.smoker" checked-icon="task_alt" unchecked-icon="panorama_fish_eye" val="Si" label="SI" />
                        <q-radio dense v-model="history.smoker" checked-icon="task_alt" unchecked-icon="panorama_fish_eye" val="No" label="No" />
                      </div>
                      <div class="col-6 col-md-8">
                        <q-input dense outlined label="Especificaciones" v-model="history.smokerDescription" />
                      </div>
                      <div class="col-6 col-md-4">
                        <div> ¿Toma alcohol? </div>
                        <q-radio dense v-model="history.alcohol" checked-icon="task_alt" unchecked-icon="panorama_fish_eye" val="Si" label="SI" />
                        <q-radio dense v-model="history.alcohol" checked-icon="task_alt" unchecked-icon="panorama_fish_eye" val="No" label="No" />
                      </div>
                      <div class="col-6 col-md-8">
                        <q-input dense outlined label="Especificaciones" v-model="history.alcoholDescription" />
                      </div>
                    </div>
                  </div>
                  <div class="col-12 bg-primary q-pa-xs text-white"> <q-icon name="o_note" /> Notas medicas</div>
                  <div class="col-12">
                    <q-input dense outlined label="Notas medicas" v-model="history.note" type="textarea" />
                    <input type="text" class="speech-input" lang="es" data-ready="Habla ahora">
                  </div>
                </div>
<!--              </div>-->
<!--            </q-scroll-area>-->
          </div>
        </div>
      </q-card-section>
    </q-card>
  </q-dialog>
</q-page>
</template>
<script>
import { useCounterStore } from 'stores/example-store'
import { date } from 'quasar'
import moment from 'moment'
moment.locale('es')
export default {
  name: 'HistoryItem',
  data () {
    return {
      loading: false,
      store: useCounterStore(),
      filter: '',
      patient: {},
      id: this.$route.params.id,
      historyCreate: true,
      historyShow: true,
      url: process.env.API,
      history: {
        date: date.formatDate(new Date(), 'YYYY-MM-DD')
      },
      histories: [],
      historyColumns: [
        { name: 'options', label: 'Opciones', field: 'options', sortable: true },
        { name: 'date', label: 'Fecha', field: 'date', sortable: true },
        { name: 'patient_id', label: 'Paciente', field: 'patient_id', sortable: true },
        { name: 'doctor_id', label: 'Doctor', field: 'doctor_id', sortable: true },
        { name: 'description', label: 'Descripción', field: 'description', sortable: true }
      ]
    }
  },
  mounted () {
    // this.historyGet()
    this.patientGet()
  },
  methods: {
    formatDateAge (date) {
      return moment(date).fromNow(true)
    },
    formatDate (date) {
      return moment(date).format('DD/MMMM/YYYY')
    },
    historyAddClick () {
      this.historyCreate = true
      this.historyShow = true
      this.history = {
        date: date.formatDate(new Date(), 'YYYY-MM-DD')
      }
    },
    historySubmit () {
      if (this.historyCreate) {
        this.history.hospital_id = this.store.user.hospital_id
        this.$api.post('histories', this.history).then(response => {
          this.historyShow = false
          this.historyGet()
        })
      } else {
        this.$api.put('histories/' + this.history.id, this.history).then(response => {
          this.historyShow = false
          this.historyGet()
        })
      }
    },
    historyEditClick (history) {
      this.historyCreate = false
      this.historyShow = true
      this.history = history
    },
    historyDeleteClick (history) {
      this.$api.delete('histories/' + history.id).then(response => {
        this.historyGet()
      })
    },
    historyGet () {
      this.$api.get(`histories/${this.id}`).then(response => {
        this.histories = response.data
      })
    },
    patientGet () {
      this.$api.get(`patients/${this.id}`).then(response => {
        this.patient = response.data
      })
    }
  },
  computed: {
    caculateIMC () {
      const imc = (this.history.weight / (this.history.height * this.history.height)).toFixed(2)
      if (isNaN(imc)) {
        return 0
      } else {
        return imc
      }
    },
    caculateIMCText () {
      const imc = (this.history.weight / (this.history.height * this.history.height)).toFixed(2)
      if (isNaN(imc)) {
        return 'Sin datos'
      } else {
        if (imc < 18.5) {
          return 'Bajo peso'
        } else if (imc >= 18.5 && imc <= 24.9) {
          return 'Normal'
        } else if (imc >= 25 && imc <= 29.9) {
          return 'Sobrepeso'
        } else if (imc >= 30 && imc <= 34.9) {
          return 'Obesidad grado 1'
        } else if (imc >= 35 && imc <= 39.9) {
          return 'Obesidad grado 2'
        } else if (imc >= 40) {
          return 'Obesidad grado 3'
        }
      }
      return 0
    }
  }
}
</script>

<style scoped>

</style>
