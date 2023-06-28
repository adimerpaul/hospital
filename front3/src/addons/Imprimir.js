import { jsPDF } from 'jspdf'
import moment from 'moment'
import { useCounterStore } from 'stores/example-store'
export default class Imprimir {
  static recetaPdf (query) {
    console.log(query)
    const user = useCounterStore().user
    /* eslint-disable */
    const doc = new jsPDF({
      // orientation: 'l',
      format: [139.7, 215,9 ]
    })


    doc.addFont("fonts/DancingScript-VariableFont_wght.ttf", "Dancing", "normal");
    doc.addFont("fonts/Belanosima-Regular.ttf", "Belanosima", "normal");
    doc.setFillColor(43,187,179);
    doc.rect(0, 0, 139.7, 33, 'F')
    const img = new Image()
    img.src = 'logoReceta.png'
    doc.addImage(img, 'png', 5, 0, 45, 44)
    doc.setFontSize(17)
    doc.setFont('Dancing', 'normal')
    doc.setTextColor(255,255,255)
    doc.text(`Dr. ${user.name}`, 95, 8, { align: 'center' }).setFont(undefined, 'bold')
    doc.setFontSize(10)
    doc.setFont('Belanosima', 'normal')
    doc.text(`${user.specialty}`, 95, 13, { align: 'center' })
    doc.setFontSize(8)
    doc.text('Especialidad: Hospital General Oruro', 95, 18, { align: 'center' })
    doc.text('Actualizacion: Lima-Peru, Ciudad de Mexico, Panama, Corea del Sur', 95, 21, { align: 'center' })
    doc.setFillColor(178,235,242)
    doc.rect(50, 25, 90, 8, 'F')
    doc.triangle(40, 33, 50, 33, 50, 25, 'F')
    doc.setTextColor(0,0,0)
    doc.setFontSize(7)
    doc.text('Direccion: Calle Bolivar entre Pagador y Velazco Galvarro Edif. LEOMAR 2do. Piso', 95, 29, { align: 'center' })
    doc.text('Galenus Especialidades Medicas 72483227 - 70413013', 95, 32, { align: 'center' })
    doc.setFontSize(10)
    doc.setFont('Belanosima', 'normal')
    doc.setFillColor(0,0,0)
    doc.text('EDAD: ', 5, 40)
    doc.rect(18, 36, 15, 5)
    doc.text('PESO: ', 35, 40)
    doc.rect(48, 36, 15, 5)
    doc.text('TALLA: ', 65, 40)
    doc.rect(78, 36, 15, 5)
    doc.text('TEMP: ', 95, 40)
    doc.rect(108, 36, 15, 5)
    doc.text('NOMBRE: ....................................................................................................................................................', 5, 50)
    doc.setFont('Dancing', 'normal')
    doc.text(`R. /P ${query.id}`, 5, 55)

    doc.setFillColor(43,187,179);
    doc.rect(0, 205, 139.7, 10, 'F')
    doc.setFillColor(178,235,242)
    doc.rect(50, 205, 90, 10, 'F')
    doc.triangle(40, 205, 50, 205, 50, 215, 'F')
    doc.setFont('Belanosima', 'normal')
    doc.text(`Fecha de Control, ..................... de ................. de 20............`, 120, 198, { align: 'right' })
    doc.text(`Oruro, ${moment().format('DD')} de ${moment().format('MMMM')} de ${moment().format('YYYY')}`, 95, 202, { align: 'left' })
    doc.text('FAVOR NO CAMBIAR RECETA', 95, 211, { align: 'center' })

    doc.text(`${query.name}`, 25, 50)




    doc.setFont('courier', 'bold')
    doc.setFontSize(10)
    doc.setFont(undefined, 'normal')
    // doc.text('Nombre: asasasa', 5, 10)
    // setTimeout(() => {
    //   doc.output('dataurlnewwindow', {filename: 'comprobante.pdf'});
    // }, 1000);
    doc.save(`receta-${query.id}${moment().format('YYYYMMDDHHmmss')}.pdf`)
    // doc.autoPrint();
    // doc.output('dataurlnewwindow', {filename: 'comprobante.pdf'});
  }
}
