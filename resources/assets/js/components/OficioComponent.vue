<template>
    <div class="contenedor">
        <table class="editable" v-on:click="editar" v-on:focusout="fijo" :contenteditable="editable">
            <tr class="font16 padding">
                <td>
                    <img :src="logo" alt="" style="height:150px">
                </td>
                <td style="padding-top:85px; width:50%; text-align:right;">
                    Unidad de Atención Temprana, Distrito XI Xalapa, Veracruz <br>
                    <span class="format1">“Si lo platicamos, lo solucionamos”</span>
                </td>
            </tr>
            <tr class="font14">
                <td colspan="2" style="padding-bottom:10px; text-align:center;">
                    ACTA DE HECHOS NÚMERO <span class="noeditable">UAT-XI/AH- <span class="folio">XXXXXXFOLIO</span> /2018</span>
                </td>
            </tr>
            <tr class="font14">
                <td colspan="2">
                    <div class="justificado" v-html="template">
                    </div>
                </td>
            </tr>
            <tr style="text-align: center;" class="font14">
                <td colspan="2">Firma del Compareciente:</td>  
            </tr>
            <tr style="text-align: center;" class="font14">
                <td colspan="2">
                    ______________________________ <br>
                    <span class="negritas">C.</span> <span class="noeditable nombre">XXXXXXNOMBRE</span>
                </td>
            </tr>
            <tr style="text-align: center;" class="font14">
                <td colspan="2">
                    __________________________________________ <br>
                    <span class="negritas">LIC.</span> <span class="noeditable fiscal">XXXXXXFISCAL</span><br>
                    Fiscal Sexta Orientadora de la Unidad de Atención Temprana <br>
                    Del XI Distrito Judicial en Xalapa, Veracruz
                </td>
            </tr>
            <tr class="font10">
                <td colspan="2">
                    <div class="justificado">
                        <span class="format2"> AVISO DE PRIVACIDAD SIMPLIFICADO</span> <br>
                        <span class="format2"> DEL EXPEDIENTE DE ATENCIÓN TEMPRANA</span> <br>
                        <span class="negritas">La Fiscalía General del Estado de Veracruz</span>, es la responsable del tratamiento de los datos personales que nos proporcione. <br>
                        Los datos personales que recabamos a Usted, los utilizaremos para las siguientes finalidades: <br>
                        •	Identificar al usuario y conocer su problemática a fin de poder orientar en su caso, respecto a la procedencia del asunto expuesto, iniciando de ser procedente la Carpeta de Investigación correspondiente o por el contrario la canalización del ciudadano a la Unidad Integral de Procuración de Justicia, o alguna otra instancia competente; <br>
                        •	Para iniciar el expediente de atención temprana, <br>
                        •	Para la recepción de las denuncias y querellas, <br>
                        •	Para la emisión de informes. <br>
                        De manera adicional, utilizaremos su información personal para la siguiente finalidad que nos permite y facilita brindarle una mejor atención: <br>
                        •	Generación de informes estadísticos. <br>
                        En caso de que no desee que sus datos personales sean tratados para las finalidades  adicionales, Usted puede manifestarlo al correo electrónico <a href="direcciondetransparencia@fiscaliaveracruz.gob.mx">direcciondetransparencia@fiscaliaveracruz.gob.mx</a>  <br>
                        Le informamos que sus datos personales <span class="negritas">NO</span> son compartidos con personas, empresas, organizaciones y autoridades distintas al sujeto obligado, salvo que sean necesarias para atender requerimientos de información de una autoridad competente, debidamente fundados y motivados. <br>
                        Para mayor información acerca del tratamiento y de los derechos que puede hacer valer, usted puede acceder al aviso de privacidad integral a través de la dirección electrónica: <a href="http://fiscaliaveracruz.gob.mx"> http://fiscaliaveracruz.gob.mx </a>  
                    </div>
                </td>
            </tr>
            <tr class="font13">
                <td>
                    <div class="justificado">
                        Circuito Rafael Guízar y <br>
                        Valencia No. 147, <br>
                        Colonia Reserva Territorial, <br>
                        C.P. 91096 <br>
                        Teléfono: 01 (228) 8149428, <br>
                        Xalapa-Enríquez, Veracruz
                    </div>
                </td>
                <td>
                    <div id="qr" style="text-align:center"></div>
                    <div class="num"></div>
                </td>
            </tr>
        </table>
        <div id="imprimir">
            <input type="button" value="Imprimir" id="imprimir" class="impre btn btn-basic btn-outline-dark"> 
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                template:``,
                info:[],
                logo:"http://localhost/oficios/public/img/logo.png",
                editable:false
            }
        },
         props:{
            url: {
                default:false
            },
            tipo: {
                default:false
            }
        },
        mounted: function () {
            this.getTemplate()
        },
        methods:{
            getTemplate: function(){
                var urlTemplate = 'actas';
                var urlPeticion = this.url;
                axios.post(urlTemplate,{
                    tipo:this.tipo
                }).then(response => {
                    this.template = response.data[0]['html'];
                    axios.get(urlPeticion).then(response => {
                        this.info = response.data;
                        this.setData();
                    });
                });   
            },
            setData: function(){
                var res = this.template.split("{{$");
                //console.log(res);
                let list=[];
                let template = this.template;
                let info = this.info;
                res.map(function(value, key) {
                    if(key!=0){
                        var res2 = value.split("}}");
                        list.push(res2[0]);
                    }
                });
                list.map(function(value,key){
                    template = template.replace("{{$"+value+"}}",info[value]);
                });
                this.template = template;
            },
            editar: function(){
                this.editable = true;
            },
            fijo: function(){
                this.editable = false;
            }
       }
    }
</script>
<style>
    *{
        font-family: "NeoSans";
    }
    body{
        background-color: #F0F0F0;
    }
    .editable{
        background-color: #ffffff;
        width: 80%;
        margin-left: auto;
        margin-right: auto;
        border: 2px solid #E3E3E3;
    }
    .editable td{
        padding: 10px;
    }
    .font16{
        font-size: 16px;
    }
    .font14{
        font-size: 14px;
    }
    .font13{
        font-size: 13px;
    }
    .font10{
        font-size: 10px;
    }
    .format1{
        font-weight: bold;
        font-style: italic;
    }
    .format2{
        font-weight: bold;
        text-align: center;
        display: block;
        margin-bottom: -15px;
    }
    .noeditable{
        font-weight: bold;
    }
    .justificado{
        margin-left: 50px;
        margin-right: 50px;
        text-align : justify;
    }
    .negritas{
        font-weight: bold;
    }
    .padding td{ 
        padding: 20px 60px;
    }
    #imprimir{
        text-align: center;
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .impre{
        padding-left: 30px;
        padding-right: 30px;
    }
    @media print {
        .impre {display:none}
        .editable{
            border: none;
            width: 100%;
        }
    }
    @page 
    {
        size:  auto;   /* auto es el valor inicial */
    }
</style>