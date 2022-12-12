<template>
<q-page class="q-pa-xs">
  <FullCalendar :options="calendarOptions" />
  <q-dialog v-model="reservationShow">
    <q-card style="width: 700px; max-width: 90vw;">
      <q-card-section class="row items-center">
        <div class="text-h6">Crear reserva</div>
        <q-space/>
        <q-btn flat icon="close" v-close-popup />
      </q-card-section>
      <q-card-section>
        <q-form @submit.prevent="reservationSubmit">
          <div class="row">
            <div class="col-12">
              <q-select v-model="patient" label="Paciente" outlined hint="" dense required :options="patients" />
            </div>
            <div class="col-12 col-md-6">
              <q-input v-model="reservation.start" label="Fecha de inicio" type="date" outlined hint="" dense required />
              <q-input v-model="reservation.startTime" label="Hora de inicio" type="time" outlined hint="" dense required />
            </div>
            <div class="col-12 col-md-6">
              <q-input v-model="reservation.end" label="Fecha de fin" type="date" outlined hint="" dense required />
              <q-input v-model="reservation.endTime" label="Hora de fin" type="time" outlined hint="" dense required />
            </div>
            <div class="col-12 text-bold">
              <q-input v-model="reservation.description" label="Descripción" outlined hint="" dense required />
              {{durationTime}}
            </div>
          </div>
          <q-card-actions align="right">
            <q-btn :loading="loading" color="red" label="Cancelar" v-close-popup icon="o_delete" />
            <q-btn :loading="loading" color="green" icon="check_circle_outline" label="Guardar" type="submit" />
            <q-btn v-if="reservationCreate==false" :loading="loading" color="red-10" icon="o_delete" label="Eliminar" @click="deleteReservation" />
          </q-card-actions>
        </q-form>
      </q-card-section>
    </q-card>
  </q-dialog>
</q-page>
</template>

<script>
import '@fullcalendar/core/vdom' // solves problem with Vite
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import listWeek from '@fullcalendar/list'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import esLocale from '@fullcalendar/core/locales/es'
import { useCounterStore } from 'stores/example-store'
import moment from 'moment'
export default {
  name: 'ReservationsItem',
  data () {
    return {
      reservationShow: false,
      reservationCreate: true,
      reservation: {
        patient: '',
        start: '',
        end: '',
        startTime: '',
        endTime: ''
      },
      events: [],
      calendarOptions: {
        plugins: [dayGridPlugin, interactionPlugin, listWeek, timeGridPlugin],
        locale: esLocale,
        initialView: 'timeGridWeek',
        dateClick: this.handleDateClick,
        headerToolbar: {
          left: 'today prev,next',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventClick: this.handleEventClick,
        events: []
      },
      store: useCounterStore(),
      patients: [],
      patient: {},
      patients2: [],
      dateIni: '',
      dateEnd: '',
      loading: false
      // calendarOptions: {
      //   plugins: [dayGridPlugin, interactionPlugin],
      //   initialView: 'dayGridMonth',
      //   dateClick: this.handleDateClick,
      //   events: [
      //     { title: 'event 1', date: '2022-12-01' },
      //     { title: 'event 2', date: '2022-12-02' }
      //   ]
      // }
    }
  },
  components: {
    FullCalendar // make the <FullCalendar> tag available
  },
  created () {
    this.reservationGet()
    this.$api.get('patients').then(response => {
      response.data.forEach(element => {
        element.label = element.name + ' ' + element.lastname
        element.value = element.id
        this.patients.push(element)
      })
      this.patient = this.patients[0]
    })
  },
  computed: {
    durationTime () {
      const hours = moment(this.reservation.endTime, 'HH:mm').diff(moment(this.reservation.startTime, 'HH:mm'), 'hours')
      const minutes = moment(this.reservation.endTime, 'HH:mm').diff(moment(this.reservation.startTime, 'HH:mm'), 'minutes') % 60
      return hours + ' horas ' + minutes + ' minutos'
    }
  },
  methods: {
    deleteReservation () {
      this.$q.dialog({
        title: 'Eliminar reserva',
        message: '¿Está seguro de eliminar la reserva?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.loading = true
        this.$api.delete('reservations/' + this.reservation.id).then(response => {
          this.loading = false
          this.$q.notify({
            message: 'Reserva eliminada',
            color: 'green-4',
            textColor: 'white',
            icon: 'check_circle_outline'
          })
          this.reservationShow = false
          this.reservationGet()
        }).catch(error => {
          this.loading = false
          this.$q.notify({
            message: error.response.data.message,
            color: 'red-4',
            textColor: 'white',
            icon: 'o_report_problem'
          })
        })
      }).onCancel(() => {
        console.log('Cancel')
      }).onDismiss(() => {
        console.log('Dismiss')
      })
    },
    reservationGet () {
      this.$api.get('reservations').then(res => {
        this.events = []
        res.data.forEach(r => {
          this.events.push({
            id: r.id,
            title: r.patient.name,
            start: r.start,
            end: r.end,
            description: r.description
          })
        })
        this.calendarOptions.events = this.events
      })
    },
    handleEventClick (arg) {
      this.reservationShow = true
      this.reservationCreate = false
      this.$q.loading.show()
      this.$api.get('reservations/' + arg.event.id).then(res => {
        this.reservation = res.data
        this.patient = this.patients.find(p => p.id === res.data.patient.id)
        this.reservation.startTime = this.reservation.start.substring(11, 16)
        this.reservation.endTime = this.reservation.end.substring(11, 16)
        this.reservation.start = moment(this.reservation.start).format('YYYY-MM-DD')
        this.reservation.end = moment(this.reservation.end).format('YYYY-MM-DD')
        this.reservationShow = true
        this.$q.loading.hide()
      }).catch(err => {
        this.$q.notify({
          message: err.response.data.message,
          color: 'negative',
          position: 'top',
          icon: 'o_report_problem'
        })
      }).finally(() => {
        this.$q.loading.hide()
      })
      this.reservationShow = true
    },
    reservationSubmit () {
      if (this.reservationCreate) {
        this.loading = true
        this.$api.post('reservations', {
          user_id: this.store.user.id,
          patient_id: this.patient.id,
          status: 'Pendiente',
          description: this.reservation.description,
          hospital_id: this.store.user.hospital_id,
          start: this.reservation.start + ' ' + this.reservation.startTime,
          end: this.reservation.end + ' ' + this.reservation.endTime
        }).then(response => {
          this.reservationShow = false
          this.reservationGet()
        }).catch(error => {
          this.$q.notify({
            message: error.response.data.message,
            color: 'negative',
            position: 'top',
            timeout: 2000
          })
        }).finally(() => {
          this.loading = false
        })
      } else {
        this.loading = true
        this.$api.put('reservations/' + this.reservation.id, {
          user_id: this.store.user.id,
          patient_id: this.patient.id,
          status: 'Pendiente',
          description: this.reservation.description,
          hospital_id: this.store.user.hospital_id,
          start: this.reservation.start + ' ' + this.reservation.startTime,
          end: this.reservation.end + ' ' + this.reservation.endTime
        }).then(response => {
          this.reservationShow = false
          this.reservationGet()
        }).catch(error => {
          this.$q.notify({
            message: error.response.data.message,
            color: 'negative',
            position: 'top',
            timeout: 2000
          })
        }).finally(() => {
          this.loading = false
        })
      }
    },
    handleDateClick (arg) {
      this.reservationCreate = true
      this.reservation.start = moment(arg.date).format('YYYY-MM-DD')
      this.reservation.end = moment(arg.date).format('YYYY-MM-DD')
      this.reservation.startTime = moment(arg.date).format('HH:mm')
      this.reservation.endTime = moment(this.reservation.startTime, 'HH:mm').add(30, 'minutes').format('HH:mm')
      this.reservationShow = true

      this.dateIni = moment(arg.date).format('YYYY-MM-DD HH:mm:ss')
    }
  }
}
</script>

<style scoped>

</style>
