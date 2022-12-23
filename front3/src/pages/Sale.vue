<template>
<q-page>
  <div class="row">
    <div class="col-12 col-sm-8">
      <div class="row q-pa-xs">
        <div class="col-12 bg-primary text-bold text-uppercase text-white text-center">Categorias</div>
        <div v-for="c in categories" :key="c.id" class="col-2">
          <q-card bordered @click="productosAdd(c)">
            <q-card-section class="q-pa-none q-ma-none" :style="`background: ${c.color}`">
              <q-img :src="`${url}../imagenes/${c.photo}`" style="border-radius: 50%" height="130px">
              </q-img>
              <div class="absolute-bottom text-subtitle1 text-center " :style="`background: ${c.color};opacity: 0.9`">
                <span class="text-white text-bold">{{c.name}}</span>
              </div>
            </q-card-section>
          </q-card>
        </div>
        <div class="col-12 bg-primary text-bold text-uppercase text-white text-center">Productos ({{category.name}})</div>
        <template v-for="p in products" :key="`p${p.id}`" >
          <div v-for="l in p.lots" :key="`l${l.id}`" class="col-2 q-pa-xs">
            <q-card bordered @click="saleAdd(p,l)">
              <q-card-section class="q-pa-none q-ma-none" :style="`background: ${p.color}`">
                <q-badge v-if="l.cantidad>0" color="orange" floating>{{ l.cantidad }}</q-badge>
                <q-img :src="`${url}../imagenes/${p.photo}`" style="border-radius: 50%" height="130px">
                </q-img>
                <div class="absolute-bottom text-subtitle1 text-center " :style="`background: ${p.color};opacity: 0.9`">
                  <div class="text-white text-caption">
                    {{p.name}} {{l.batch}} <br>
                    {{l.priceSale}} Bs-
                    {{l.quantity}} U
                  </div>
                </div>
              </q-card-section>
            </q-card>
          </div>
        </template>
      </div>
    </div>
    <div class="col-12 col-sm-4 q-pa-xs">
      <q-card>
        <q-card-section class="row items-center q-pb-none">
          <div class="col-2">
            <div class="text-bold text-grey">Total: <span class="text-red">{{total}}Bs.</span> </div>
          </div>
          <div class="col-9">
<!--            <q-select-->
<!--              outlined-->
<!--              dense-->
<!--              v-model="client"-->
<!--              use-input-->
<!--              input-debounce="0"-->
<!--              label="Cliente"-->
<!--              :options="clients"-->
<!--              @filter="filterFn"-->
<!--              behavior="menu"-->
<!--            >-->
<!--              <template v-slot:no-option>-->
<!--                <q-item>-->
<!--                  <q-item-section class="text-grey">-->
<!--                    No results-->
<!--                  </q-item-section>-->
<!--                </q-item>-->
<!--              </template>-->
<!--            </q-select>-->
          </div>
          <div class="col-1">
            <q-btn icon="delete_outline" dense  color="negative" @click="saleClear" />
          </div>
        </q-card-section>
        <q-card-section class="q-pa-none">
          <div class="text-subtitle2 flex-center flex text-center bg-primary text-white">PRODUCTOS</div>
        </q-card-section>
        <q-card-section class="q-pa-none">
          <div class="row items-center bg-primary text-white">
            <div class="col-2 text-center text-bold">CANTIDAD</div>
            <div class="col-6 text-center text-bold">NOMBRE</div>
            <div class="col-2 text-center text-bold">PRECIO</div>
            <div class="col-2 text-center text-bold">SUBTOTAL</div>
          </div>
          <div class="row items-center" v-for="p in productSales" :key="p.id">
            <div class="col-2 text-center text-bold">
              <q-input v-model="p.cantidad" dense outlined mask="#" class="text-right" fill-mask="0" reverse-fill-mask />
            </div>
            <div class="col-6">{{p.name}}</div>
            <div class="col-2 text-right">
              <q-input v-model="p.priceSale" dense outlined mask="#" class="text-right" fill-mask="0" reverse-fill-mask >
              </q-input>
            </div>
            <div class="col-2 text-right q-pr-xs text-bold">{{p.priceSale*p.cantidad}}Bs.</div>
          </div>
        </q-card-section>
      </q-card>
    </div>
  </div>
</q-page>
</template>

<script>
export default {
  name: 'SaleItem',
  data () {
    return {
      url: process.env.API,
      sale: {},
      category: {},
      categories: [],
      products: [],
      product: {},
      productSales: []
    }
  },
  created () {
    this.$api.get('categories').then(response => {
      this.categories = response.data
      this.category = this.categories[0]
      this.products = []
      this.categories[0].products.forEach(p => {
        p.lots.forEach(l => {
          l.cantidad = 0
        })
        this.products.push(p)
      })
    })
  },
  methods: {
    saleClear () {
      this.productSales = []
      this.products.forEach(p => {
        p.lots.forEach(l => {
          l.cantidad = 0
        })
      })
    },
    productosAdd (category) {
      this.category = category
      this.products = category.products
    },
    saleAdd (p, l) {
      // console.log(p)
      l.cantidad++
      const find = this.productSales.find(ps => ps.id === l.id)
      if (find) {
        find.cantidad++
      } else {
        this.productSales.push({
          id: l.id,
          name: p.name,
          priceSale: l.priceSale,
          cantidad: 1
        })
      }
    }
  },
  computed: {
    total () {
      let total = 0
      this.productSales.forEach(p => {
        total += p.priceSale * p.cantidad
      })
      return total
    },
    porCobrar () {
      return this.total - this.monto
    }
  }
}
</script>

<style scoped>

</style>
