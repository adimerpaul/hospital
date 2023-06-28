<template>
<q-page>
  <div class="row q-pa-xs">
    <div class="col-12 col-md-4">
      <div class="row">
        <div class="col-12">
          <div class="text-uppercase text-h4 text-bold text-center">{{patient.name}} {{patient.lastname}}</div>
        </div>
        <div class="col-6 q-my-xs col-md-6 text-right text-bold q-pr-xs text-grey">F. de Nacimiento:</div>
        <div class="col-6 q-my-xs col-md-6"><div class="text-left text-bold " v-if="patient.birthday!=undefined">{{formatDate(patient.birthday)}}</div></div>
        <div class="col-6 q-my-xs col-md-6 text-right text-bold q-pr-xs text-grey">Edad:</div>
        <div class="col-6 q-my-xs col-md-6"><div class="text-left text-bold " v-if="patient.birthday!=undefined">{{ $filters.ageMonthDays(patient.birthday) }}</div></div>
        <div class="col-6 q-my-xs col-md-6 text-right text-bold q-pr-xs text-grey">Sexo:</div>
        <div class="col-6 q-my-xs col-md-6"><div class="text-left text-bold">{{(patient.sex)}}</div></div>
        <div class="col-6 q-my-xs col-md-6 text-right text-bold q-pr-xs text-grey">Direccion:</div>
        <div class="col-6 q-my-xs col-md-6"><div class="text-left text-bold">{{(patient.address)}}</div></div>
        <div class="col-6 q-my-xs col-md-6 text-right text-bold q-pr-xs text-grey">Correo:</div>
        <div class="col-6 q-my-xs col-md-6"><div class="text-left text-bold ">{{patient.email}}</div></div>
        <div class="col-6 q-my-xs col-md-6 text-right text-bold q-pr-xs text-grey">Tel:</div>
        <div class="col-6 q-my-xs col-md-6"><div class="text-left text-bold ">{{patient.phone}}</div></div>
      </div>
    </div>
    <div class="col-12 col-md-8">
      <div class="row">
        <div class="col-6">
          <span :class="`${$q.screen.lt.md?'text-subtitle2':'text-h6'} text-bold text-center`">
            <q-icon name="o_monitor_heart" class="q-mr-xs text-subtitle2" size="1.4em" color="grey-8" />
            HISTORIAL CLINICO
          </span>
        </div>
        <div class="col-6 text-right">
          <q-btn class="q-mr-xs" :loading="loading" color="primary" label="Nuevo" icon="add_circle_outline" dense @click="historyAddClick" />
          <q-icon name="refresh" :loading="loading" class="q-mr-xs" size="1.4em" color="grey-8" @click="historyGet" />
        </div>
        <div class="col-12">
          <q-table bordered flat  :rows="histories" :loading="loading" dense :columns="historyColumns" :rows-per-page-options="[0, 10, 15, 20]">
            <template v-slot:body-cell-date="props">
              <q-td :props="props">{{formatDate(props.row.date)}} {{formatTime(props.row.time)}}</q-td>
            </template>
            <template v-slot:body-cell-options="props">
              <q-td :props="props" auto-width>
                <span class="text-bold q-pa-xs">{{histories.length-props.pageIndex}}</span>
                <q-btn-dropdown label="Opciones" dense color="primary" no-caps>
                  <q-list>
                    <q-item clickable v-close-popup @click="editHistory(props.row)">
                      <q-item-section avatar>
                        <q-icon name="o_edit" color="grey-8" />
                      </q-item-section>
                      <q-item-section>Editar o ver</q-item-section>
                    </q-item>
                    <q-item clickable v-close-popup @click="deleteHistory(props.row)">
                      <q-item-section avatar>
                        <q-icon name="o_delete" color="grey-8" />
                      </q-item-section>
                      <q-item-section>Eliminar</q-item-section>
                    </q-item>
                    <q-item clickable v-close-popup :href="`${url}reportHistory/${props.row.id}`" target="_blank">
                      <q-item-section avatar>
                        <q-icon name="o_print" color="grey-8" />
                      </q-item-section>
                      <q-item-section>Ver Historial clinico</q-item-section>
                    </q-item>
                    <q-item clickable v-close-popup :href="`${url}reportReception/${props.row.id}`" target="_blank">
                      <q-item-section avatar>
                        <q-icon name="o_print" color="grey-8" />
                      </q-item-section>
                      <q-item-section>Reseta medica</q-item-section>
                    </q-item>
                    <q-item clickable v-close-popup :href="`https://api.whatsapp.com/send?phone=591${patient.phone}&text=${url}reportHistory/${props.row.id}`" target="_blank">
                      <q-item-section avatar>
                        <q-icon name="ion-logo-whatsapp" color="grey-8" />
                      </q-item-section>
                      <q-item-section>Historial clinico whatsapp</q-item-section>
                    </q-item>
                    <q-item clickable v-close-popup :href="`https://api.whatsapp.com/send?phone=591${patient.phone}&text=${url}reportReception/${props.row.id}`" target="_blank">
                      <q-item-section avatar>
                        <q-icon name="ion-logo-whatsapp" color="grey-8" />
                      </q-item-section>
                      <q-item-section>Receta medica whatsapp</q-item-section>
                    </q-item>
                    <q-item clickable v-close-popup @click="printReceta(props.row)">
                      <q-item-section avatar>
                        <q-icon name="o_print" color="grey-8" />
                      </q-item-section>
                      <q-item-section>Imprimir receta</q-item-section>
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
  <q-dialog v-model="historyShow" full-width>
    <q-card class="q-pa-md" >
      <q-card-section class="row items-center q-py-none bg-primary">
        <div class="text-center text-h5 text-bold text-white">Consulta Medica <span v-if="!historyCreate">{{formatDate(history.date)}} {{formatTime(history.time)}}</span></div>
        <q-space />
        <q-btn icon="close" color="white" flat round dense v-close-popup />
      </q-card-section>
      <q-card-section class="items-center q-pa-none" >
        <div class="row">
<!--          <div class="col-2 col-md-2 ">-->
<!--            <div class="row bg-primary">-->
<!--              <div class="col-12">-->
<!--                <q-btn @click="aniateScroll(0,'weight')" label="Signos vitales" no-caps align="left" class="full-width bg-primary text-white" icon="favorite_outline" flat  />-->
<!--              </div>-->
<!--              <div class="col-12">-->
<!--                <q-btn @click="aniateScroll(110,'fum')" label="Preguntas ginecologias" no-caps align="left" class="full-width bg-primary text-white" icon="help_outline" flat  />-->
<!--              </div>-->
<!--              <div class="col-12">-->
<!--                <q-btn @click="aniateScroll(180,'smokerDescription')" label="Preguntas generales" no-caps align="left" class="full-width bg-primary text-white" icon="question_mark" flat  />-->
<!--              </div>-->
<!--              <div class="col-12">-->
<!--                <q-btn @click="aniateScroll(300,'note')" label="Notas medicas" no-caps align="left" class="full-width bg-primary text-white" icon="o_note" flat  />-->
<!--              </div>-->
<!--              <div class="col-12">-->
<!--                <q-btn @click="aniateScroll(500,'exploration')" label="Exploracion fisica" no-caps align="left" class="full-width bg-primary text-white" icon="speed" flat  />-->
<!--              </div>-->
<!--&lt;!&ndash;              <div class="col-12">&ndash;&gt;-->
<!--&lt;!&ndash;                <q-btn @click="aniateScroll(0,'')" label="Diagnostico importante" no-caps align="left" class="full-width bg-primary text-white" icon="bubble_chart" flat  />&ndash;&gt;-->
<!--&lt;!&ndash;              </div>&ndash;&gt;-->
<!--              <div class="col-12">-->
<!--                <q-btn @click="aniateScroll(550,'prescription1')" label="Receta medica" no-caps align="left" class="full-width bg-primary text-white" icon="o_description" flat  />-->
<!--              </div>-->
<!--              <div class="col-12">-->
<!--                <q-btn @click="aniateScroll(1000,'observations')" label="Observaciones" no-caps align="left" class="full-width bg-primary text-white" icon="o_contact_support" flat  />-->
<!--              </div>-->
<!--              <div class="col-12">-->
<!--                <q-btn @click="aniateScroll(1000,'summary')" label="Infomacion Laboral" no-caps align="left" class="full-width bg-primary text-white" icon="work_outline" flat  />-->
<!--              </div>-->
<!--&lt;!&ndash;              <div class="col-12">&ndash;&gt;-->
<!--&lt;!&ndash;                <q-btn @click="aniateScroll(0,'')" label="Medicamento" no-caps align="left" class="full-width bg-primary text-white" icon="o_vaccines" flat  />&ndash;&gt;-->
<!--&lt;!&ndash;              </div>&ndash;&gt;-->
<!--              <div class="col-12 bg-white q-py-xs">-->
<!--                <q-btn :loading="loading" @click="historySubmit" :label="historyCreate?`Registrar`:`Modificar`" no-caps :class="`full-width ${historyCreate?`bg-green text-white`:`bg-yellow`} `" :icon="historyCreate?`check_circle_outline`:`edit`" flat  />-->
<!--                <q-btn v-if="!historyCreate" :loading="loading" @click="deleteHistory(history)" :label="`Eliminar`" no-caps :class="`full-width bg-red text-white`" :icon="`o_delete`" flat  />-->
<!--              </div>-->
<!--            </div>-->
<!--          </div>-->
          <div class="col-12 col-md-12 q-pa-xs">
<!--            <q-scroll-area class="full-width full-height">-->
<!--              <div v-for="n in 100" :key="n" class="q-py-xs">-->
<!--            <q-scroll-area class="full-width full-height" ref="scrollAreaRef">-->
              <div class="row">
                <div class="col-12 bg-primary q-pa-xs text-white"> <q-icon name="favorite_outline" /> Signos vitales</div>
                <div class="col-12">
                  <div class="row">
                    <div class="col-6 col-md-2">
                      <q-input dense outlined label="Peso Kg" v-model="history.weight" ref="weight" />
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
                      <q-input readonly dense outlined label="IMC" v-model="calculateIMC" />
                    </div>
                    <div class="col-6 col-md-2">
                      <q-input readonly dense outlined label="IMC Clasificacion" v-model="calculateIMCText" />
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
<!--                    <div class="col-6 col-md-2">-->
<!--                      <q-select dense outlined label="Tipo de sangre" v-model="history.blood" :options="[ 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-' ]" />-->
<!--                    </div>-->
                  </div>
                </div>
                <div class="col-12">
                  <div class="row">
                    <div class="col-6">
                      <div class="col-12 bg-primary q-pa-xs text-white"> <q-icon name="help_outline" /> Preguntas ginecologicas</div>
                      <div class="col-12">
                        <div class="row">
                          <div class="col-12">
                            <div> ¿Esta embarazada? </div>
                            <q-radio dense v-model="history.pregnant" checked-icon="task_alt" unchecked-icon="panorama_fish_eye" val="SI" label="SI" />
                            <q-radio dense v-model="history.pregnant" checked-icon="task_alt" unchecked-icon="panorama_fish_eye" val="NO" label="NO" />
                          </div>
                          <div class="col-12" v-if="history.pregnant=='SI'">
                            <q-input dense outlined  v-model="history.fum" ref="fum" label="Fecha de ultima menstruacion" type="date" />
                          </div>
                          <div class="col-12" v-if="history.pregnant=='SI'">
                            <q-input dense outlined readonly  v-model="calculateGestation" label="Semanas de gestacion" />
                          </div>
                          <div class="col-12" v-if="history.pregnant=='SI'">
                            <q-input dense outlined v-model="history.fu" label="Fondo uterino" />
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="col-12 bg-primary q-pa-xs text-white"> <q-icon name="question_mark" /> Preguntas generales</div>
                      <div class="col-12">
                        <div class="row">
                          <div class="col-6 col-md-4">
                            <div> ¿Fuma? </div>
                            <q-radio dense v-model="history.smoker" checked-icon="task_alt" unchecked-icon="panorama_fish_eye" val="SI" label="SI" />
                            <q-radio dense v-model="history.smoker" checked-icon="task_alt" unchecked-icon="panorama_fish_eye" val="NO" label="NO" />
                          </div>
                          <div class="col-6 col-md-8" >
                            <q-input v-if="history.smoker=='SI'" dense outlined label="Especificaciones de fumador" ref="smokerDescription" v-model="history.smokerDescription" />
                          </div>
                          <div class="col-6 col-md-4">
                            <div> ¿Toma alcohol? </div>
                            <q-radio dense v-model="history.alcohol" checked-icon="task_alt" unchecked-icon="panorama_fish_eye" val="SI" label="SI" />
                            <q-radio dense v-model="history.alcohol" checked-icon="task_alt" unchecked-icon="panorama_fish_eye" val="NO" label="NO" />
                          </div>
                          <div class="col-6 col-md-8" >
                            <q-input v-if="history.alcohol=='SI'" dense outlined label="Especificaciones de alcohol" v-model="history.alcoholDescription" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12 bg-primary q-pa-xs text-white"> <q-icon name="o_note" />
                  Notas medicas
<!--                  <q-btn dense flat :loading="loading" :icon="isRecording ? 'mic' : 'mic_off'" @click="ToggleMic('note')" />-->
                </div>
                <div class="col-12">
                  <div class="row">
                    <div class="col-3">
                      <q-input dense outlined label="Diagnostico 1" v-model="history.note1" ref="note" >
                        <template v-slot:append>
                          <q-btn dense flat :loading="loading" :icon="isRecording ? 'mic' : 'mic_off'" @click="ToggleMic('note1')" />
                        </template>
                      </q-input>
                    </div>
                    <div class="col-3">
                      <q-input dense outlined label="Diagnostico 2" v-model="history.note2" >
                        <template v-slot:append>
                          <q-btn dense flat :loading="loading" :icon="isRecording ? 'mic' : 'mic_off'" @click="ToggleMic('note2')" />
                        </template>
                      </q-input>
                    </div>
                    <div class="col-3">
                      <q-input dense outlined label="Diagnostico 3" v-model="history.note3" >
                        <template v-slot:append>
                          <q-btn dense flat :loading="loading" :icon="isRecording ? 'mic' : 'mic_off'" @click="ToggleMic('note3')" />
                        </template>
                      </q-input>
                    </div>
                    <div class="col-3">
                      <q-input dense outlined label="Diagnostico 4" v-model="history.note4" >
                        <template v-slot:append>
                          <q-btn dense flat :loading="loading" :icon="isRecording ? 'mic' : 'mic_off'" @click="ToggleMic('note4')" />
                        </template>
                      </q-input>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="row">
                    <div class="col-6">
                      <div class="col-12 bg-primary q-pa-xs text-white"> <q-icon name="speed" />
                        Exploracion fisica
                        <q-btn dense flat :loading="loading" :icon="isRecording ? 'mic' : 'mic_off'" @click="ToggleMic('exploration')" />
                      </div>
                      <div class="col-12">
                        <q-input dense outlined label="Exploracion fisica" v-model="history.exploration" type="textarea" ref="exploration" />
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="col-12 bg-primary q-pa-xs text-white">
                        <q-icon name="o_description" />
                        Receta Medica <q-btn dense flat :loading="loading" icon="add_circle_outline" @click="addPrescription" />
                      </div>
                      <div class="col-12 q-pa-xs">
                        <template v-for="(m,i) in queryMedicines" :key="i">
                          <div class="row">
                            <div class="col-12 text-bold">
                              <q-btn flat dense rounded icon="o_edit" @click="editPrescription(m)" />
                              <q-btn flat dense rounded icon="o_delete" @click="deletePrescription(i)" />
                              {{i+1}}.- {{m.name}} {{m.number}} {{m.unit}} {{m.times}} {{m.via}} {{m.diagnosis}}
                            </div>
                          </div>
                        </template>
                        <!--                  <pre>{{queryMedicines}}</pre>-->
                        <!--                  <q-input dense outlined label="Receta medica 1" ref="prescription1" v-model="history.prescription1">-->
                        <!--                    <template v-slot:append>-->
                        <!--                      <q-btn dense flat :loading="loading" :icon="isRecording ? 'mic' : 'mic_off'" @click="ToggleMic('prescription1')" />-->
                        <!--                    </template>-->
                        <!--                  </q-input>-->
                        <!--                  <q-input dense outlined label="Receta medica 2" v-model="history.prescription2">-->
                        <!--                    <template v-slot:append>-->
                        <!--                      <q-btn dense flat :loading="loading" :icon="isRecording ? 'mic' : 'mic_off'" @click="ToggleMic('prescription2')" />-->
                        <!--                    </template>-->
                        <!--                  </q-input>-->
                        <!--                  <q-input dense outlined label="Receta medica 3" v-model="history.prescription3">-->
                        <!--                    <template v-slot:append>-->
                        <!--                      <q-btn dense flat :loading="loading" :icon="isRecording ? 'mic' : 'mic_off'" @click="ToggleMic('prescription3')" />-->
                        <!--                    </template>-->
                        <!--                  </q-input>-->
                        <!--                  <q-input dense outlined label="Receta medica 4" v-model="history.prescription4">-->
                        <!--                    <template v-slot:append>-->
                        <!--                      <q-btn dense flat :loading="loading" :icon="isRecording ? 'mic' : 'mic_off'" @click="ToggleMic('prescription4')" />-->
                        <!--                    </template>-->
                        <!--                  </q-input>-->
                        <!--                  <q-input dense outlined label="Receta medica 5" v-model="history.prescription5">-->
                        <!--                    <template v-slot:append>-->
                        <!--                      <q-btn dense flat :loading="loading" :icon="isRecording ? 'mic' : 'mic_off'" @click="ToggleMic('prescription5')" />-->
                        <!--                    </template>-->
                        <!--                  </q-input>-->
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div class="row">
                    <div class="col-6">
                      <div class="col-12 bg-primary q-pa-xs text-white"> <q-icon name="o_contact_support" />
                        Observaciones
                        <q-btn dense flat :loading="loading" :icon="isRecording ? 'mic' : 'mic_off'" @click="ToggleMic('observations')" />
                      </div>
                      <div class="col-12">
                        <q-input dense outlined label="Observaciones" ref="observations" v-model="history.observations" type="textarea" />
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="col-12 bg-primary q-pa-xs text-white"> <q-icon name="work_outline" /> Informacion laboral</div>
                      <div class="row">
                        <div class="col-6">
                          <q-select dense outlined label="Resumen medico" ref="summary" v-model="history.summary" :options="summaries" />
                        </div>
                        <div class="col-6">
                          <q-select dense outlined label="Medida de accion" v-model="history.action" :options="actions" />
                        </div>
                        <div class="col-6">
                          <q-select clearable dense outlined label="RAYOS X - TOMOGRAFIA" multiple ref="summary" v-model="history.tomografias" :options="tomografias" />
                        </div>
                        <div class="col-6">
                          <q-select clearable dense outlined label="ECOGRAFIA" multiple ref="summary" v-model="history.ecografias" :options="ecografias" />
                        </div>
                        <div class="col-6">
                          <q-select clearable dense outlined label="laboratiorios" multiple ref="summary" v-model="history.laboratorios" :options="laboratorios" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-12">
                  <div><span class="text-subtitle2">Consulta Medica realizada por:</span> {{ store.user.name}} <span class="text-subtitle2"> en fecha:</span> {{ formatDate(hoy)}}</div>
                </div>
                <div class="col-12">
                                  <q-btn :loading="loading" @click="historySubmit" :label="historyCreate?`Registrar`:`Modificar`" no-caps :class="`full-width ${historyCreate?`bg-green text-white`:`bg-yellow`} `" :icon="historyCreate?`check_circle_outline`:`edit`" flat  />
                                  <q-btn v-if="!historyCreate" :loading="loading" @click="deleteHistory(history)" :label="`Eliminar`" no-caps :class="`full-width bg-red text-white`" :icon="`o_delete`" flat  />
                </div>
              </div>
<!--            </q-scroll-area>-->
<!--              </div>-->
<!--            </q-scroll-area>-->
          </div>
        </div>
      </q-card-section>
    </q-card>
  </q-dialog>
  <q-dialog v-model="medicineDialog">
    <q-card style="width: 750px;max-width: 80vh;height: 750px;max-height: 80vh;">
      <q-form @submit.prevent="addMedicines">
        <q-card-section class="row items-center q-pb-none">
          <div class="text-h6">Medicamentos</div>
          <q-space />
          <q-btn icon="close" flat round dense v-close-popup />
        </q-card-section>
        <q-card-section class="q-pt-none">
          <div class="row">
            <div class="col-12 q-pb-xs">
              <q-table style="height: 400px" :rows-per-page-options="[0]" flat bordered title="Seleccionar medicamento" :columns="medicineColumns" dense :loading="loading" :rows="medicines" :filter="medicineFilter">
                <template v-slot:top-right>
                  <q-btn dense flat round icon="add_circle_outline" @click="addMedicine" />
                  <q-input outlined dense v-model="medicineFilter" label="Buscar">
                    <template v-slot:append>
                      <q-icon name="search" />
                    </template>
                  </q-input>
                </template>
                <template v-slot:body="props">
                  <q-tr :props="props" @click="medicine.name = props.row.name">
                    <q-td key="options" auto-width>
                      <q-btn dense flat round icon="edit" @click="editMedicine(props.row, props.rowIndex)" />
                      <q-btn dense flat round icon="delete" @click="deleteMedicine(props.row, props.rowIndex)" />
                    </q-td>
                    <q-td key="name">
                      <div class="cursor-pointer">{{ props.row.name }}</div>
                    </q-td>
                  </q-tr>
                </template>
                </q-table>
            </div>
            <div class="col-12">
              <div class="text-subtitle2 text-red">Medicamento seleccionado</div>
            </div>
            <div class="col-12 col-sm-10">
              <q-input dense options-dense required outlined label="Medicamento" v-model="medicine.name" />
            </div>
            <div class="col-6 col-sm-2">
              <q-input dense options-dense required outlined label="Cantidad" v-model="medicine.number" :options="numbers" list="numbers" >
                <datalist id="numbers">
                  <option v-for="number in numbers" :key="number" :value="number" />
                </datalist>
              </q-input>
            </div>
            <div class="col-6 col-sm-3">
              <q-select dense options-dense required outlined label="Unidad" v-model="medicine.unit" :options="units" />
            </div>
            <div class="col-6 col-sm-3">
              <q-select dense options-dense required outlined label="Frecuencia" v-model="medicine.times" :options="times" />
            </div>
            <div class="col-6 col-sm-3">
              <q-select dense options-dense required outlined label="Via de administracion" v-model="medicine.via" :options="vias" />
            </div>
            <div class="col-6 col-sm-3">
              <q-select dense options-dense outlined label="Diagnostico" v-model="medicine.diagnosis" :options="['',1,2,3,4]" />
            </div>
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="Cancelar" color="primary" @click="medicineDialog = false" />
          <q-btn label="Guardar" color="primary" type="submit" />
        </q-card-actions>
      </q-form>
    </q-card>
  </q-dialog>
</q-page>
</template>
<script>
import { useCounterStore } from 'stores/example-store'
import { date } from 'quasar'
import moment from 'moment'
import Imprimir from 'src/addons/Imprimir'
moment.locale('es')
const SpeechRecognition = window.speechRecognition || window.webkitSpeechRecognition

export default {
  name: 'HistoryItem',
  data () {
    return {
      medicineDialog: false,
      medicine: {},
      ecografias: [
        'ECOGRAFIA ABDOMINAL',
        'ECOGRAFIA OBSTETRICA',
        'ECOGRAFIA TRANSVAGINAL',
        'ECOGRAFIA ABDOMINAL Y PELVICA',
        'ECOGRAFIA RASTREO ABDOMINAL',
        'ECOGRAFIA FAST',
        'ECOGRAFIA HEPATOBILIOPANCREATICA',
        'ECOGRAFIA RENOVESICAL PROSTATICA',
        'ECOGRAFIA MAMARIA BILATERAL',
        'ECOGRAFIA TIROIDEA',
        'ECOGRAFIA TESTICULAR',
        'ECOGRAFIA PARTES BLANDAS',
        'ECOGRAFIA DOPPLER ARTERIAL POR MIEMBRO',
        'ECOGRAFIA DOPPLER VENOSO POR MIEMBRO',
        'OBSTETRICA AVANZADA VITALIDA FETAL',
        'ECOGRAFIA CADERAS PEDIATRICO',
        'ECOGRAFIA ESTRUCTURAL I,II Y III TRIMESTRE',
        'ECOGRAFIA DOPLER OBSTETRICA',
        'ECOGRAFIA 3D Y 4D',
        'E',
        'ECOGRAFIA OCULAR',
        'ECOGRAFIA MUSCULO ESQUELETICA',
        'PROCEDIMIENTOS GUIADOS POR ECOGRAFIA',
        'ECOGRAFIA POR SEGUIMIENTO FOLICULAR',
        'ECOGRAFIA GINECOLOGICA',
        'ECOGRAFIA RENOVESICAL PRE Y POST MIXONARI',
        'ECOGRAFIA CADERAS PEDIATRICO',
        'ECOGRAFAI ESTRUCTURAL PRIMER TRIMESTRE',
        'ECOGRAFAI ESTRUCTURAL SEGUNDO TRIMESTRE',
        'ECOGRAFAI OBSTETRICA DOPPLER ',
        'ECOGRAFIA 3D Y 4D ',
        'ECOGRAFIA HEMITORAX',
        'ECOGRAFIA PELVICA',
        'ECOGRAFIA ESTRUCTURAL TERCER TRIMESTRE',
        'ECOGRAFIA PULMONAR',
        'ECOGRAFIA OCULAR',
        'ECOGRAFIA MUSCULOESQUELETICA',
        'PROCEDIMIENTOS GUIADOS POR ECOGRAFIA',
        'ECOGRAFIA DE SEGUIMIENTO FOLICULAR (TRES ESTUDIOS)'
      ],
      laboratorios: [
        'GLICEMIA - GLICEMIA BASAL',
        'CURVA TOLERANCIA ORAL DE GLUCOSA',
        'HEMOGLOBINA GLICOSILADA   -  HB A1c',
        'AMILASA',
        'LIPASA',
        'CREATININA',
        'UREA ',
        'BUN - NUS  (NITROGENO UREICO) ',
        'ÁCIDO ÚRICO',
        'PROTEINURIA DE 24 HORAS',
        'EXAMEN GENERAL DE ORINA (EGO)',
        'CREATINURIA DE 24 HORAS',
        'LIPIDOGRAMA - PERFIL LIPÍDICO (COSTO SIN VLDL)',
        'COLESTEROL TOTAL',
        'TRIGLICERIDOS',
        'HDL COLESTEROL ',
        'LDL COLESTEROL',
        'VLDL COLESTEROL',
        'HEPATOGRAMA - PERFIL HEPÁTICO - (COSTO SIN GGT)',
        'FOSFATASA ALCALINA',
        'BILIRRUBINAS (FRACCIONES) (DIRECTA, INDIRECTA, TOTAL)',
        'GOT (TRANSAMINASA)',
        'GPT (TRANSAMINASA)',
        'GGT',
        'BILIRRUBINA TRANSCUTANEA',
        'ELECTROLITOS (Na, K, Cl) - IONOGRAMA ',
        'SODIO',
        'CLORO',
        'POTASIO',
        'CALCIO',
        'FÓSFORO',
        'MAGNESIO',
        'PROTEINOGRAMA (Proteína + Albúmina)',
        'PROTEINAS TOTALES',
        'ALBUMINA',
        'GLOBULINAS',
        'REL. ALBUMINA/GLOBULINA',
        'PERFIL CARDIACO',
        'TROPONINA I',
        'CPK TOTAL',
        'CK - MB ',
        'LDH ',
        'TOMA DE MUESTRA PARA PLASMA RICO EN PLAQUETAS x TUBO',
        'PCR (PROTEÍNA C REACTIVA)',
        'FR (FACTOR RUMATOIDEO) (LATEX)',
        'ASTO (ANTIESTREPTOLISINA)',
        'REACCIÓN DE WIDALL (ANTIGENO FEBRIL)',
        'VDRL - RPR - SIFILIS',
        'HELICOBACTER PILORY (SANGRE)',
        'HELICOBACTER PILORY  (HECES)',
        'B-HCG (ORINA O SANGRE)',
        'VIH (PRUEBA RÁPIDA)',
        'HEPATITIS "A"  (HAV)',
        'HEPATITIS "B" (HBsAg)',
        'HEPATITIS "C" (HVC)',
        'HAI CHAGAS',
        'HAI TOXOPLASMOSIS',
        'HEMOGRAMA',
        'HEMOGRAMA COMPLETO + VES',
        'HEMATOCRITO + HEMOGLOBINA',
        'LEUCOGRAMA',
        'GRUPO SANGUÍNEO Y FACTOR',
        'RETICULOCITOS',
        'TUBO VACUTAINER',
        'COAGULOGRAMA (COSTO SIN PLAQUETAS)',
        'TIEMPO DE PROTROMBINA + INR + ACT%',
        'TIEMPO DE COAGULACIÓN',
        'TIEMPO DE SANGRÍA',
        'RECUENTO DE PLAQUETAS (PQ)',
        'EXAMEN DE SECRECIÓN VAGINAL + TINCIÓN GRAM',
        'EXAMEN DE SECRECIÓN URETRAL + TINCION GRAM',
        'TAMIZAJE NEONATAL (PAQUETE DE 4 PRUEBAS)',
        'TSH NEONATAL - TAMISAJE HIPOTIROIDISMO',
        'PESQUIZA PRENATAL (DUO DE MATERNIDAD)',
        'PESQUIZA PRENATAL (TRIO DE MATERNIDAD)',
        'SANGRE OCULTA EN HECES',
        'TINCIÓN GRAM',
        'REACCIÓN DE BENEDITC Y PH',
        'MOCO FECAL',
        'COPROPARASITOLÓGICO SIMPLE',
        'COPROPARASITOLÓGICO SERIADO',
        'GASOMETRIA ARTERIAL CON LACTATO',
        'GASOMETRIA ARTERIAL CON ELECTROLITOS',
        'PRUEBA RÁPIDA COVID 19 (CUALITATIVA)',
        'PRUEBA COVID 19 (INMUNOFLUORESCENCIA)(CUANTIFICADO)',
        'ACTH ',
        'ADA EN LIQUIDOS',
        'ADENOVIRUS',
        'ALCOHOLEMIA',
        'ALFA FETO PROTEINA ----AFP',
        'AMEBAS ELISAS',
        'ANA',
        'ANCA C',
        'ANCA P ',
        'ANTI CCP',
        'ANTI DNA',
        'ANTI TG',
        'ANTIGENO PARA HELICOBACTER PYLORI EN HECES',
        'BACILOSCOPIA ',
        'BACTERISCOPIA DE SECRECION',
        'CA -125',
        'CA 15 -3 ',
        'CA 19 - 9',
        'CEA - CARCINOEMBRINARIO',
        'CELULA L.E.',
        'CHAGAS ELISA',
        'CISTICERCOSIS',
        'CITOMEGALOVIRUS IGG',
        'CITOMEGALOVIRUS IGM',
        'CITOQUIMICO DE LIQUIDOS',
        'CLAMIDIA IGG',
        'CLAMIDIA IGM',
        'COMPLEMENTO C3',
        'COMPLEMENTO C4',
        'COPROCULTIVO Y ANTIBIOGRAMA',
        'CORTISOL AM-PM ',
        'CULTIVO DE SECRECIONES Y ANTIBIOGRAMA',
        'DHEAS',
        'DIMERO D',
        'EIPSTEN  BARR IGG',
        'EIPSTEN  BARR IGM',
        'ENA PROFILE ',
        'ESTRADIOL',
        'FERRITINA',
        'HORMONA FOLICULO ESTIMULANTE (FSH)',
        'GIARDIA ELISAS',
        'HCG CUANTITATIVO',
        'HELICOBACTER PYLORI IGG',
        'HELICOBACTER PYLORI IGM',
        'HEMOCULTIVO',
        'HEMOCULTIVO X 2 (SERIADO)',
        'HEPATITIS C',
        'HERPES VIRUS IGG',
        'HERPES VIRUS IGM',
        'HIDATIDOSIS',
        'HIERRO POR ELISA',
        'HORMONA DE CRECIMIENTO ',
        'INMUNOGLOBULINA IgA',
        'INMUNOGLOBULINA IgE',
        'INMUNOGLOBULINA IgM',
        'INSULINA',
        'HORMONA LUTEINIZANTE (LH)',
        'PANEL MULTIDROGAS',
        'PARATHORMONA',
        'PCR ULTRASENSIBLE',
        'PROGESTERONA',
        'PROLACTINA',
        'PSA LIBRE',
        'PSA TOTAL',
        'ROTAVIRUS',
        'RUBEOLA IGG',
        'RUBEOLA IGM',
        'SEMINOGRAMA - ESPERMIOGRAMA',
        'T3',
        'T4 LIBRE',
        'TEST DE CRISTALIZACION',
        'TESTOSTERONA',
        'TOXOPLASMOSIS IGG',
        'TOXOPLASMOSIS IGM',
        'TPO',
        'TRANSFERRINA',
        'TSH ADULTO ',
        'UROCULTIVO Y ANTIBIOGRAMA',
        'VIH ELISA',
        'ANTIGENO NASAL COVID',
        'ANTI TGO',
        'ANTI TPO',
        'APTT - TPTA',
        'CALPROTECTINA',
        'ESTROGENO',
        'FIBRINOGENO',
        'MICROALBUMINURIA',
        'PERFIL TIROIDEO COMPLETO',
        'RETROCULTIVO',
        'T4 TOTAL',
        'TGO',
        'TIROGLOBULINA',
        'PROCALCITONINA',
        'ANTI CCP',
        'INTERLEUCINA - 6 (IL-6)',
        'TRIPLE CARDIACO (MIOGLUBINA/TROPONINA I/CK MB)',
        'LAB-PAQUETE GINECOLOGICO',
        'TORCH',
        'ACIDO URICO',
        'PCR - RT COVID',
        'ANTIGENO NASAL SEGUROS',
        'GASOMETRIA ARTERIAL CON ELECTROLITOS SEGUROS',
        'GASOMETRIA ARTERIAL CON LACTATO SEGUROS',
        'TEST GRAHAM(TEST TAPE)',
        'HEPATITIS B ELISA',
        'AMONIO',
        'COOMBS DIRECTO',
        'COOMBD INDIRECTO',
        'CITOQUÍMICO DE LIQUIDO SINOVIAL',
        'CITOQUIMICO DE LIQUIDO ASCITICO O PERITONEAL',
        'CITOQUIMICO DE LIQUIDO PLEURAL',
        'CITOQUIMICO DE LIQUIDO PERICARDICO',
        'CITOQUIMICO DE LCR',
        'TIEMPO DE TROMBINA - TT',
        'MICROALBUMINURIA - MICROALBÚMINA EN ORINA DE 24 HORAS U ORINA OCASIONAL',
        'RELACIÓN CREATINURIA/ALBUMINURIA',
        'FRUCTOSAMINA',
        'LIPIDOS TOTALES',
        'ANTICUERPO ANTICARDIOLIPINA IGG - IGM',
        'MIOGLOBINA',
        'NT-proBNP (NT-PROPÉPTIDO NATRIURÉTICO CEREBRAL)',
        'HIERRO',
        'TICB + SATURACIÓN DE TRANSFERRINA',
        'TSH ULTRASENSIBLE',
        'TSH - TIROTROPINA',
        'T3 LIBRE',
        'ANTI-TIROGLOBULINA / ANTI TG',
        'ANTI-PEROXIDASA / ANTI TPO',
        'HORMONA ANTIMULLERIANA (AHM)',
        'TESTOSTERONA LIBRE',
        '17-HIDROXIPROGESTERONA',
        'VITAMINA D3 - COLECALCIFEROL',
        'ANTI CCP - CLIA',
        'ANA (ANTICUERPOS ANTINUCLEARES)',
        'PERFIL ENA (SS-A, SS-B, Scl-70, Jo-1, rnp, Sm)',
        'PEPTIDO C',
        'UROCULTIVO',
        'COPROCULTIVO',
        'ESPERMOCULTIVO',
        'CULTIVO DE SECRECIÓN VAGINAL',
        'CULTIVO DE SECRECIÓN URETRAL',
        'CULTIVO DE ESPUTO',
        'CULTIVO DE HISOPADO FARINGEO',
        'CULTIVO DE ABCESOS',
        'CULTIVO DE PUNTA DE CATETER',
        'BACILOSCOPIA SIMPLE EN ESPUTO - BK ESPUTO',
        'BACILOSCOPIA SERIADA EN ESPUTO - BK (2MUESTRAS)',
        'COCAINA',
        'MARIHUANA',
        'COVID - 19 PCR + TOMA DE MUESTRA',
        'SIFILIS ELISA',
        'B 2 MICROGLOBULINA'
      ],
      tomografias: [
        'Test de Farrell (2 años) # 1 - 14 x 17',
        'Pelvis entrada y salida (adulto) # 2 - 14 x 17',
        'Histerosalpingografia # 1 - 11 x 14',
        'Colangiografía # 1 - 8 x 10',
        'Cráneo AP- LAT. # 1 - 11 x 14',
        'Huesos propios de la Nariz # 1 - 8 x 10',
        'Senos Paranasales MNP- FNP # 2 - 8 x 10',
        'Watters – Cadwell # 1 - 8 x 10',
        'Maxilar Inferior frente # 1 - 8 x 10',
        'Hirtz - towne o base de cráneo # 1 - 8 x 10',
        'Columna cervical AP- LAT. # 1 - 11 x 14',
        'Columna Cervical dinamicas # 1 - 11 x 14',
        'Columna Dorsal AP- LAT. # 1 - 14 x 17',
        'Columna Lumbar AP- LAT. # 1 - 14 x 17',
        'Columna sacro coccix AP- LAT. # 1 - 11 x 14',
        'Tórax PA # 1 - 14 x 17',
        'Torax LAT # 1 - 14 x 17',
        'Hemitorax o Parrilla costal # 1 - 14 x 17',
        'Abdomen Simple o de pie # 1 - 14 x 17',
        'Pelvis o Cadera (adulto) # 1 - 14 x 17',
        'Pelvis Obturatriz - Alar # 1 - 14 x 17',
        'Femur AP- LAT # 1 - 14 x 17',
        'Pierna AP- LAT # 1 - 14 x 17',
        'Brazo o Humero AP- LAT # 1 - 14 x 17',
        'Antebrazo AP- LAT # 1 - 14 x 17',
        'Pelvis o Cadera (niño) # 1 - 8 x 10',
        'Rodilla AP- LAT # 1 - 11 x 14',
        'Pie AP- OBL o LAT # 1 - 11 x 24',
        'Mano PA- OBL o LAT # 1 - 11 x 14',
        'Axiales de rotulas 60 ", 45 ", 30 # 1 - 8 x 10',
        'Tobillo AP- LAT # 1 - 8 x 10',
        'Calcaneo Axial - LAT # 1 - 8 x 10',
        'Clavicula - Hombro - Escapula # 1 - 8 x 10',
        'Codo AP- LAT # 1 - 8 x 10',
        'Muñeca AP- LAT # 1 - 8 x 30',
        'Cráneo Simple - con ventana ósea',
        'Cráneo con contraste',
        'Silla turca – Hipófisis sin contraste',
        'Silla turca – Hipófisis con contraste',
        'Oídos – peñascos sin contraste',
        'Oídos – peñascos con contraste',
        'Orbitas sin contraste',
        'Orbitas con contraste',
        'Senos paranasales sin contraste',
        'Senos paranasales con contraste',
        'Macizo Facial sin contraste',
        'Macizo Facial con contraste',
        'Cuello sin contraste',
        'Cuello con contraste',
        'Tórax sin contraste',
        'Tórax con contraste',
        'Abdomen y pelvis sin contraste',
        'Abdomen y pelvis con contraste',
        'Pelvis sin contraste',
        'Pelvis con contraste',
        'Columna cervical',
        'Columna Dorsal',
        'Columna Lumbar',
        'Columna Sacro-coxis',
        'Columna total Sin contraste',
        'Columna total con contraste técnica Raquídea',
        'Hombro-codo-muñeca-mano-rodilla-tobillo-pie + 3D',
        'Pierna-fémur-antebrazo-humero + 3D',
        'Patelo femoral 15°- 30°- 45° - 60°- 90° (Rotulas)',
        'PIELOTAC sin contraste (Abdomen simple)',
        'Perfusión Cerebral por tomografía',
        'Angiotomografía de cerebro (polígono de Willis o cuatro vasos)',
        'Angiotomografía de carótidas',
        'Angiotomografía de Arterias pulmonares (ANGIO TEP)',
        'Angio (UROTAC)',
        'Angiotomografía de arteria Aorta',
        'Angiotomografía de miembro inferior Total (ANGIO MMII)',
        'Angiotomografía de miembro superior Total (ANGIO MMss)',
        'PIELOTAC sin contraste ( Abdomen simple)',
        'Perfucion Cerebral por tomografia',
        'RX Histerosalpingografia',
        'rayos x de craneo',
        'UROGRAMA EXCRETOR'
      ],
      medicineFilter: '',
      medicineColumns: [
        { name: 'options', label: 'Opciones', field: 'options', sortable: true },
        // { name: 'id', label: 'ID', field: 'id', align: 'left', sortable: true },
        { name: 'name', label: 'Medicamento', field: 'name', align: 'left', sortable: true }
      ],
      summaries: [
        'ACCIDENTE DE TRABAJO',
        'ACCIDENTE DE TRANSITO',
        'ACCIDENTE DE TRABAJO Y TRANSITO',
        'ACCIDENTE DE TRABAJO Y ENFERMEDAD PROFESIONAL',
        'CONSULTA EXTERNA',
        'CONSULTA GENERAL',
        'OTROS',
        'HISTORIA CLINICA'
      ],
      actions: [
        'REPOSOS',
        'SIN TRATAMIENTO',
        'TRATAMIENTO',
        'OTROS'
      ],
      scrollAreaRef: null,
      loading: false,
      store: useCounterStore(),
      filter: '',
      patient: {},
      id: this.$route.params.id,
      historyCreate: true,
      historyShow: false,
      url: process.env.API,
      hoy: date.formatDate(new Date(), 'YYYY-MM-DD'),
      history: {},
      histories: [],
      historyColumns: [
        { name: 'options', label: 'Opciones', field: 'options' },
        { name: 'date', label: 'Fecha', field: 'date', sortable: true },
        { name: 'doctor', label: 'doctor', field: (row) => row.user.name, sortable: true },
        { name: 'exploration', label: 'Exploracion', field: 'exploration', sortable: true },
        { name: 'observations', label: 'Observaciones', field: 'observations', sortable: true }
      ],
      // transcription: '',
      isRecording: false,
      // Recognition: window.SpeechRecognition || window.webkitSpeechRecognition,
      sr: null,
      type: '',
      queryMedicines: [],
      medicines: [],
      numbers: [],
      units: [],
      times: [],
      vias: [],
      medicineCreate: false
    }
  },
  created () {
    this.medicinesGet()
    this.numbersGet()
    this.unitsGet()
    this.timesGet()
    this.viasGet()
    this.sr = new SpeechRecognition()
    this.sr.continuous = true
    this.sr.interimResults = true
    this.sr.lang = 'es'
    this.sr.onresult = (e) => {
      this.loading = true
      for (let i = e.resultIndex; i < e.results.length; i++) {
        const transcript = e.results[i][0].transcript
        if (e.results[i].isFinal) {
          this.loading = false
          if (this.type === 'note1') {
            this.history.note1 += transcript
          } else if (this.type === 'exploration') {
            this.history.exploration += transcript
          } else if (this.type === 'observations') {
            this.history.observations += transcript
          } else if (this.type === 'prescription1') {
            this.history.prescription1 += transcript
          } else if (this.type === 'prescription2') {
            this.history.prescription2 += transcript
          } else if (this.type === 'prescription3') {
            this.history.prescription3 += transcript
          } else if (this.type === 'prescription4') {
            this.history.prescription4 += transcript
          } else if (this.type === 'prescription5') {
            this.history.prescription5 += transcript
          } else if (this.type === 'note1') {
            this.history.note1 += transcript
          } else if (this.type === 'note2') {
            this.history.note2 += transcript
          } else if (this.type === 'note3') {
            this.history.note3 += transcript
          } else if (this.type === 'note4') {
            this.history.note4 += transcript
          }
        }
      }
    }
    // this.historyGet()
    this.patientGet()
  },
  methods: {
    editPrescription (prescription) {
      this.medicine = prescription
      this.medicineDialog = true
      this.medicineCreate = false
    },
    deletePrescription (index) {
      this.$q.dialog({
        title: 'Eliminar',
        message: '¿Estas seguro de eliminar esta prescripcion?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.queryMedicines.splice(index, 1)
      }).onCancel(() => {
        console.log('Cancel')
      }).onDismiss(() => {
        console.log('Dismiss')
      })
    },
    editMedicine (row, index) {
      this.$q.dialog({
        title: 'Editar medicamento',
        message: '¿Desea editar el medicamento?',
        prompt: {
          model: row.name,
          type: 'text'
        },
        cancel: true
      }).onOk((data) => {
        this.medicines[index].name = data
        this.$api.put('medicines/' + row.id, {
          name: data
        }).then(response => {
          this.$q.notify({
            message: 'Medicamento editado',
            color: 'positive',
            icon: 'check_circle'
          })
        }).catch(error => {
          this.$q.notify({
            message: error.response.data.message,
            color: 'negative',
            icon: 'error'
          })
        })
      }).onCancel(() => {
        console.log('Cancel')
      }).onDismiss(() => {
        console.log('Dismissed')
      })
    },
    deleteMedicine (row, index) {
      this.$q.dialog({
        title: 'Eliminar',
        message: '¿Estas seguro de eliminar este medicamento?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        this.medicines.splice(index, 1)
        this.$api.delete('medicines/' + row.id)
      }).onCancel(() => {
        console.log('Cancel')
      }).onDismiss(() => {
        console.log('Dismissed')
      })
    },
    addMedicine () {
      this.$q.dialog({
        title: 'Agregar medicamento',
        message: 'Ingrese el nombre del medicamento',
        prompt: {
          model: '',
          type: 'text'
        },
        cancel: true,
        persistent: true
      }).onOk(data => {
        this.medicines.push({ id: this.medicines.length + 1, name: data })
        this.$api.post('medicines', { name: data }).then(response => {
          this.$q.notify({
            message: 'Medicamento agregado',
            color: 'positive',
            icon: 'check_circle'
          })
        })
      }).onCancel(() => {
        console.log('Cancel')
      }).onDismiss(() => {
        console.log('Dismissed')
      })
    },
    addMedicines () {
      if (this.medicineCreate) {
        this.medicine.id = this.queryMedicines.length + 1
        this.queryMedicines.push(this.medicine)
        this.medicine = {}
        this.medicineDialog = false
      } else {
        const find = this.queryMedicines.find(medicine => medicine.id === this.medicine.id)
        this.queryMedicines[this.queryMedicines.indexOf(find)] = this.medicine
        this.medicineDialog = false
      }
    },
    addPrescription () {
      this.medicineDialog = true
      this.medicine = {}
      this.medicineCreate = true
    },
    medicinesGet () {
      this.$api.get('medicines').then(res => {
        this.medicines = res.data
        // res.data.forEach(medicine => {
        //   this.medicines.push(medicine.name)
        // })
      })
    },
    numbersGet () {
      this.$api.get('numbers').then(res => {
        this.numbers = res.data.map(number => {
          return number.name
        })
      })
    },
    unitsGet () {
      this.$api.get('units').then(res => {
        this.units = res.data.map(medicine => {
          return medicine.name
        })
      })
    },
    timesGet () {
      this.$api.get('times').then(res => {
        this.times = res.data.map(medicine => {
          return medicine.name
        })
      })
    },
    viasGet () {
      this.$api.get('vias').then(res => {
        this.vias = res.data.map(medicine => {
          return medicine.name
        })
      })
    },
    editHistory (history) {
      this.history = history
      this.historyCreate = false
      this.historyShow = true
      this.queryMedicines = []
      this.history.fum = this.history.FUM
      this.history.sdg = this.history.SDG
      this.history.fu = this.history.FU
      if (history.tomografias) {
        this.history.tomografias = history.tomografias.split(',')
      }
      if (history.radiografias) {
        this.history.radiografias = history.radiografias.split(',')
      }
      if (history.laboratorios) {
        this.history.laboratorios = history.laboratorios.split(',')
      }
      this.history.query_medicines.forEach(item => {
        item.name = item.medicine
        this.queryMedicines.push(item)
      })
    },
    printReceta (query) {
      Imprimir.recetaPdf(query)
    },
    deleteHistory (history) {
      this.$q.dialog({
        title: 'Eliminar',
        message: '¿Estas seguro de eliminar esta historia clinica?',
        ok: 'Si',
        cancel: 'NO'
      }).onOk(() => {
        this.loading = true
        this.$api.delete('queries/' + history.id)
          .then(response => {
            this.historyShow = false
            this.$q.notify({
              message: 'Historia clinica eliminada',
              color: 'positive',
              icon: 'check_circle'
            })
            this.historyGet()
          })
          .catch(error => {
            this.$q.notify({
              message: error.response.data.message,
              color: 'negative',
              icon: 'error'
            })
          }).finally(() => {
            this.loading = false
          })
      })
    },
    aniateScroll (px, type) {
      this.$refs.scrollAreaRef.setScrollPosition('vertical', px, 300)
      if (type === 'weight') {
        this.$refs.weight.focus()
      } else if (type === 'fum') {
        this.$refs.fum.focus()
      } else if (type === 'smokerDescription') {
        this.$refs.smokerDescription.focus()
      } else if (type === 'note') {
        this.$refs.note.focus()
      } else if (type === 'exploration') {
        this.$refs.exploration.focus()
      } else if (type === 'prescription1') {
        this.$refs.prescription1.focus()
      } else if (type === 'observations') {
        this.$refs.observations.focus()
      } else if (type === 'summary') {
        this.$refs.summary.focus()
      }
    },
    ToggleMic (type) {
      this.type = type
      if (this.isRecording) {
        this.sr.stop()
        this.isRecording = false
      } else {
        this.sr.start()
        this.isRecording = true
      }
    },
    formatDateAge (date) {
      return moment(date).fromNow(true)
    },
    formatDate (date) {
      return moment(date).format('DD/MMM/YYYY')
    },
    formatTime (time) {
      return moment(time, 'HH:mm:ss').format('hh:mm a')
    },
    historyAddClick () {
      this.queryMedicines = []
      this.history = {
        date: date.formatDate(new Date(), 'YYYY-MM-DD'),
        fum: date.formatDate(new Date(), 'YYYY-MM-DD'),
        note1: '',
        note2: '',
        note3: '',
        note4: '',
        weight: '',
        height: '',
        exploration: '',
        observations: '',
        smoker: 'NO',
        smokerDescription: '',
        pregnant: 'NO',
        alcohol: 'NO',
        alcoholDescription: '',
        prescription1: '',
        prescription2: '',
        prescription3: '',
        prescription4: '',
        prescription5: ''
      }
      this.historyCreate = true
      this.historyShow = true
    },
    historySubmit () {
      this.loading = true
      this.history.FUM = this.history.fum
      this.history.SDG = this.history.calculateGestation
      this.history.FU = this.history.fu
      if (this.historyCreate) {
        this.history.hospital_id = this.store.user.hospital_id
        this.history.patient_id = this.id
        this.history.user_id = this.store.user.id
        this.history.medicines = this.queryMedicines
        this.$api.post('queries', this.history).then(response => {
          this.historyShow = false
          this.historyGet()
          this.$q.notify({
            message: 'Historia clinica creada',
            color: 'positive',
            icon: 'check_circle'
          })
        }).catch(error => {
          this.$q.notify({
            message: error.response.data.message,
            color: 'negative',
            icon: 'error'
          })
        }).finally(() => {
          this.loading = false
        })
      } else {
        this.history.medicines = this.queryMedicines
        this.$api.put('queries/' + this.history.id, this.history).then(response => {
          this.historyShow = false
          this.historyGet()
          this.$q.notify({
            message: 'Historia clinica actualizada',
            color: 'positive',
            icon: 'check_circle'
          })
        }).catch(error => {
          this.$q.notify({
            message: error.response.data.message,
            color: 'negative',
            icon: 'error'
          })
        }).finally(() => {
          this.loading = false
        })
      }
    },
    historyGet () {
      this.loading = true
      this.$api.get(`queries/${this.id}`).then(response => {
        this.histories = response.data
        this.loading = false
      })
    },
    patientGet () {
      this.loading = true
      this.$api.get(`patients/${this.id}`).then(response => {
        this.patient = response.data
        this.historyGet()
      }).catch(error => {
        this.$q.notify({
          message: error.response.data.message,
          color: 'negative',
          icon: 'error'
        })
        this.loading = false
      }).finally(() => {
        // this.loading = false
      })
    }
  },
  computed: {
    calculateGestation () {
      if (this.history.fum) {
        const fum = moment(this.history.fum)
        const today = moment()
        const gestation = today.diff(fum, 'weeks')
        if (gestation < 50) {
          return gestation
        } else {
          return 0
        }
      }
      return 0
    },
    calculateIMC () {
      const imc = (this.history.weight / (this.history.height * this.history.height)).toFixed(2)
      if (isNaN(imc)) {
        return 0
      } else {
        return imc
      }
    },
    calculateIMCText () {
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
