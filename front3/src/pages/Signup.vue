<template>
  <q-layout view="lHh Lpr lFf" class="bg-grey-2">
        <q-page-container>
    <q-page>
      <div class="row">
        <div class="col-1 col-sm-2"></div>
        <div class="col-10 col-sm-8">
          <div class="text-subtitle1 text-center q-pt-xs text-grey">REGISTRATE</div>
          <div class="text-h3 text-center q-pa-xs text-grey-7 text-bold">Crea tu cuenta</div>
          <q-card flat bordered>
            <q-card-section >
              <q-form class="" @submit.prevent="signUp">
                <div class="row">
                  <div class="col-12 col-sm-6 q-px-xs">
                    <div class="row">
                      <div class="col-12">
                        <label class="text-grey">Nombre Completo<span class="text-red">(*)</span></label>
                        <q-input dense outlined v-model="user.name" hide-hint type="name" placeholder="Nombre completo" :rules="[val => val.length > 0 || 'Nombre requerido']" required>
                          <template v-slot:prepend>
                            <q-icon name="o_person" class="cursor-pointer">
                              <q-tooltip>Nombre</q-tooltip>
                            </q-icon>
                          </template>
                        </q-input>
                      </div>
                      <div class="col-12 ">
                        <label class="text-grey">Correo <span class="text-red">(*)</span></label>
                        <q-input dense outlined v-model="user.email" type="email" placeholder="Email" :rules="[val => val.length > 0 || 'Correo requerido']" required>
                          <template v-slot:prepend>
                            <q-icon name="o_email" class="cursor-pointer">
                              <q-tooltip>Correo</q-tooltip>
                            </q-icon>
                          </template>
                        </q-input>
                      </div>
                      <div class="col-12">
                        <label class="text-grey">Sexo <span class="text-red">(*)</span></label>
                        <q-radio v-model="user.sex" val="MASCULINO" class="text-grey" label="MASCULINO"/>
                        <q-radio v-model="user.sex" val="FEMENINO" class="text-grey" label="FEMENINO"/>
                      </div>
                      <div class="col-12">
                        <label class="text-grey">Especialidad <span class="text-red">(*)</span></label>
                        <q-select dense outlined v-model="user.specialty" :options="specialities" hint="" />
                      </div>
                      <div class="col-12 ">
                        <label class="text-grey">Contraseña <span class="text-red">(*)</span></label>
                        <q-input dense outlined v-model="user.password" placeholder="Contraseña" :type="typePassword?'password':'text'" required :rules="[val => val.length > 0 || 'Contraseña requerida']" >
                          <template v-slot:append>
                            <q-icon @click="typePassword=!typePassword" :name="typePassword?'visibility':'visibility_off'" />
                          </template>
                        </q-input>
                      </div>
                      <div class="col-12 ">
                        <label class="text-grey">Confirmar Contraseña <span class="text-red">(*)</span></label>
                        <q-input dense outlined v-model="user.password_confirmation" placeholder="Confirmar Contraseña" :type="typePassword?'password':'text'" required :rules="[val => val.length > 0 || 'Confirmar Contraseña requerida']" >
                          <template v-slot:append>
                            <q-icon @click="typePassword=!typePassword" :name="typePassword?'visibility':'visibility_off'" />
                          </template>
                        </q-input>
                      </div>
                    </div>
                  </div>
                  <div class="col-12 col-sm-6 q-px-xs">
                    <div class="row">
                      <div class="col-12 ">
                        <label class="text-grey">Numero  profesional / Carnet identidad <span class="text-red">(*)</span></label>
                        <q-input dense outlined v-model="user.ci" placeholder="Numero de colegiatura" :rules="[val => val.length > 0 || 'Numero profesional requerido']" required >
                          <template v-slot:prepend>
                            <q-icon name="credit_card" class="cursor-pointer">
                              <q-tooltip>Numero profesional</q-tooltip>
                            </q-icon>
                          </template>
                        </q-input>
                      </div>
                      <div class="col-12">
                        <label class="text-grey">Nombre Hospital<span class="text-red">(*)</span></label>
                        <q-input dense outlined v-model="hospital.name" hide-hint type="name" placeholder="Nombre hospital" :rules="[val => val.length > 0 || 'Nombre requerido']" required>
                          <template v-slot:prepend>
                            <q-icon name="account_circle" class="cursor-pointer" />
                            <q-tooltip>Nombre Hospital</q-tooltip>
                          </template>
                        </q-input>
                      </div>
                      <div class="col-12">
                        <label class="text-grey">Dirección<span class="text-red">(*)</span></label>
                        <q-input dense outlined v-model="hospital.address" hide-hint type="address" placeholder="Dirección" :rules="[val => val.length > 0 || 'Dirección requerida']" required>
                          <template v-slot:prepend>
                            <q-icon name="location_on" class="cursor-pointer" />
                            <q-tooltip>Dirección</q-tooltip>
                          </template>
                        </q-input>
                      </div>
                      <div class="col-12">
                        <label class="text-grey">Pagina web / Correo<span class="text-red">(*)</span></label>
                        <q-input dense outlined v-model="hospital.web" hide-hint type="web" placeholder="Pagina web" :rules="[val => val.length > 0 || 'Pagina web requerida']" required>
                          <template v-slot:prepend>
                            <q-icon name="language" class="cursor-pointer" />
                            <q-tooltip>Pagina web</q-tooltip>
                          </template>
                        </q-input>
                      </div>
                      <div class="col-12">
                        <label class="text-grey">Telefono / Celular<span class="text-red">(*)</span></label>
                        <q-input dense outlined v-model="hospital.phone" hide-hint type="phone" placeholder="Telefono" :rules="[val => val.length > 0 || 'Telefono requerido']" required>
                          <template v-slot:prepend>
                            <q-icon name="phone" class="cursor-pointer" />
                            <q-tooltip>Telefono</q-tooltip>
                          </template>
                        </q-input>
                      </div>
                      <div class="col-12 ">
                        <q-btn size="18px" dense outline class="full-width bold" color="primary" label="Registrate" type="submit" no-caps />
                      </div>
                      <div class="col-12 q-pt-xs">
                        <q-btn size="18px" dense to="login" class="full-width bold" color="primary" label="Iniciar Sesión" type="submit" no-caps />
                      </div>
                      <div class="col-12 text-center q-py-md">
                        <a href="" class="text-blue-8 ">¿Olvidaste tu contraseña?</a>
                      </div>
                    </div>
                  </div>
                </div>
              </q-form>
              <div class="row">
                <div class="col-12 text-center q-py-xs text-caption">
                  <div class="linea"><span>O INICIAR SESIÓN CON</span></div>
                </div>
                <div class="col-6 q-pt-md q-pr-xs">
                  <q-btn outline icon="fa-brands fa-facebook" color="primary" class="full-width "/>
                </div>
                <div class="col-6 q-pt-md q-pl-xs">
                  <q-btn outline icon="fa-brands fa-google" color="primary" class="full-width "/>
                </div>
              </div>
            </q-card-section>
          </q-card>
          <div class="text-subtitle1 text-center text-caption q-pt-lg text-grey">AL INICIAR SESIÓN, USTED ACEPTA LOS
            <a href="" class="text-blue-8">TÉRMINOS DEL SERVICIO</a> Y <a href="" class="text-blue-8 ">LA POLÍTICA DE PRIVACIDAD</a>
          </div>
        </div>
        <div class="col-1 col-sm-4"></div>
      </div>
    </q-page>
        </q-page-container>
  </q-layout>
</template>

<script>
import { useCounterStore } from 'stores/example-store'
export default {
  name: 'SignupItem',
  data () {
    return {
      hospital: {
        name: '',
        address: '',
        web: '',
        phone: ''
      },
      user: {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        type: 'admin',
        sex: 'MASCULINO',
        specialty: 'MEDICO GENERAL'
      },
      specialities: [
        'ANESTESIOLOGO',
        'CARDIOLOGO',
        'CIRUJANO',
        'DERMATOLOGO',
        'ENDOCRINOLOGO',
        'GASTROENTEROLOGO',
        'GINECOLOGO',
        'HEMATOLOGO',
        'INFECTOLOGO',
        'NEFROLOGO',
        'NEUMOLOGO',
        'NEUROLOGO',
        'MEDICO GENERAL',
        'OTROS'
      ],
      email: '',
      name: '',
      password: '',
      password_confirmation: '',
      store: useCounterStore(),
      typePassword: true
    }
  },
  mounted () {
    if (this.store.isLoggedIn) {
      this.$router.push('/')
    }
  },
  methods: {
    signUp () {
      this.$q.loading.show()
      this.$api.post('register', {
        name: this.user.name,
        email: this.user.email,
        password: this.user.password,
        password_confirmation: this.user.password_confirmation,
        sex: this.user.sex,
        specialty: this.user.specialty,
        ci: this.user.ci,
        nameHospital: this.hospital.name,
        address: this.hospital.address,
        phone: this.hospital.phone,
        web: this.hospital.web,
        type: 'DOCTOR'
      }).then((res) => {
        this.$q.notify({
          message: 'Usuario creado correctamente',
          color: 'positive',
          icon: 'check_circle',
          position: 'top'
        })
        this.$router.push('/')
        this.store.user = res.data.user
        this.store.isLoggedIn = true
        this.$api.defaults.headers.common.Authorization = 'Bearer ' + res.data.token
        localStorage.setItem('tokenHospital', res.data.token)
      })
        .catch((error) => {
          console.log(error)
          this.$q.notify({
            message: error.response.data.message,
            color: 'negative',
            icon: 'error',
            position: 'top'
          })
        })
        .finally(() => {
          this.$q.loading.hide()
        })
    }
  }
}
</script>

<style scoped>
div.linea {
  position: relative;
  z-index: 1;
}
div.linea:before {
  border-top: 1px solid grey;
  content: "";
  margin: 0 auto;
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  bottom: 0;
  width: 95%;
  z-index: -1;
}
div.linea span {
  background: #fff;
  padding: 0 10px;
  color: grey;
}
</style>
