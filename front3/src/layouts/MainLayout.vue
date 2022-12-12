<template>
  <q-layout view="lHh Lpr lFf">
    <q-header >
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />

        <q-toolbar-title>
          <div style="line-height: 15px;" v-if="store.user.hospital!=undefined">
            <span class="text-bold">{{store.user.hospital.nameHospital}}</span>
            <br>
            <span class="text-caption">{{store.user.hospital.address}}</span>
          </div>
        </q-toolbar-title>

        <q-toolbar-title class="text-right">
          <div style="line-height: 15px;" v-if="store.user.hospital!=undefined">
            <span class="text-caption">{{store.user.hospital.web}}</span>
            <br>
            <span class="text-caption">{{store.user.hospital.phone}}</span>
          </div>
        </q-toolbar-title>
        <q-btn flat icon="power_settings_new" @click="logout" />
      </q-toolbar>
    </q-header>
    <q-drawer
      v-model="drawer"
      show-if-above
      :width="200"
      :breakpoint="400"
    >
      <q-scroll-area style="height: calc(100% - 130px); margin-top: 130px; border-right: 1px solid #ddd">
        <q-list padding>
          <q-item clickable v-ripple to="/" active-class="bg-blue-6 text-white" exact>
            <q-item-section avatar>
              <q-icon name="o_home" />
            </q-item-section>
            <q-item-section>
              Principal
            </q-item-section>
          </q-item>
<!--          <q-item clickable v-ripple to="/schedule" active-class="bg-blue-6 text-white" exact>-->
<!--            <q-item-section avatar>-->
<!--              <q-icon name="o_schedule" />-->
<!--            </q-item-section>-->
<!--            <q-item-section>-->
<!--              Horarios-->
<!--            </q-item-section>-->
<!--          </q-item>-->
          <q-item clickable v-ripple to="/category" active-class="bg-blue-6 text-white" exact>
            <q-item-section avatar>
              <q-icon name="o_category" />
            </q-item-section>
            <q-item-section>
              Categorías
            </q-item-section>
          </q-item>
          <q-item clickable v-ripple to="/product" active-class="bg-blue-6 text-white" exact>
            <q-item-section avatar>
              <q-icon name="o_inventory_2" />
            </q-item-section>
            <q-item-section>
              Productos
            </q-item-section>
          </q-item>
          <q-item clickable v-ripple to="/sale" active-class="bg-blue-6 text-white" exact>
            <q-item-section avatar>
              <q-icon name="o_shopping_cart" />
            </q-item-section>
            <q-item-section>
              Ventas
            </q-item-section>
          </q-item>
        </q-list>
      </q-scroll-area>

      <q-img class="absolute-top" src="fondo.jpg" style="height: 130px">
        <div class="absolute-bottom bg-transparent">
          <q-avatar size="56px" class="q-mb-sm">
            <img :src="`${url}../imagenes/${store.user.avatar}`" v-if="store.user.avatar!=undefined">
          </q-avatar>
          <div class="text-weight-bold" v-if="store.user.avatar!=undefined">{{ store.user.name.toLowerCase() }}</div>
          <div>{{ store.user.email }}</div>
        </div>
      </q-img>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { useCounterStore } from 'stores/example-store'

export default {
  data () {
    return {
      url: process.env.API,
      drawer: null,
      store: useCounterStore()
    }
  },
  created () {
    // console.log(this.url)
  },
  methods: {
    logout () {
      this.$q.dialog({
        title: 'Cerrar sesión',
        message: '¿Está seguro de cerrar sesión?',
        ok: {
          push: true,
          color: 'primary',
          icon: 'check',
          label: 'Aceptar'
        },
        cancel: {
          push: true,
          color: 'negative',
          icon: 'close'
        }
      }).onOk(() => {
        this.$q.loading.show()
        this.$api.post('logout').then(() => {
          this.$q.loading.hide()
          this.$q.notify({
            message: 'Sesión cerrada',
            color: 'positive',
            icon: 'check_circle',
            position: 'top'
          })
          this.$router.push('/login')
          this.store.user = {}
          this.store.token = ''
          localStorage.removeItem('tokenHospital')
          this.store.isLoggedIn = false
          this.$api.defaults.headers.common.Authorization = ''
        })
        // this.$store.dispatch('logout')
        // this.$router.push('/login')
      })
    },
    toggleLeftDrawer () {
      this.drawer = !this.drawer
    }
  }
}
</script>
