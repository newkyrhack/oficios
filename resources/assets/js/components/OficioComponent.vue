<template>
    <div class="contenedor">
        <table class="editable" v-on:click="editar" v-on:focusout="fijo" >
            <thead>
                <tr class="font16 padding">
                    <th>
                        <img :src="logo" alt="" style="height:150px">
                    </th>
                    <th style="padding-top:85px; width:50%; text-align:right;">
                        Unidad de Atención Temprana, Distrito XI Xalapa, Veracruz <br>
                        <span class="format1">“Si lo platicamos, lo solucionamos”</span>
                    </th>
                </tr>
            </thead>
            <tbody>
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
            </tbody>
            <tfoot>
                <tr class="font13">
                    <th>
                        <div class="justificado">
                            Circuito Rafael Guízar y <br>
                            Valencia No. 147, <br>
                            Colonia Reserva Territorial, <br>
                            C.P. 91096 <br>
                            Teléfono: 01 (228) 8149428, <br>
                            Xalapa-Enríquez, Veracruz
                        </div>
                    </th>
                    <th>
                        <canvas ref="canvas" id="qr"></canvas>
                    </th>
                </tr>
            </tfoot>
        </table>
        <div id="imprimir">
            <input type="button" value="Imprimir" id="imprimir" class="impre btn btn-basic btn-outline-dark" v-on:click="imprimir"> 
        </div>
    </div>
</template>

<script>
import QRCode from 'qrcode'
    export default {
        data(){
            return{
                template:'',
                tipoOficio:'',
                info:[],
                variables:[],
                bloqueados:[],
                logo:"http://localhost/oficios2/public/img/logo.png",
                token:''
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
                var urlTemplate = '../oficios';
                var urlPeticion = this.url;
                axios.post(urlTemplate,{
                    tipo:this.tipo
                }).then(response => {
                    if(response.data[0]==undefined){
                        console.log("sin datos");
                    }
                    else{
                        this.template = response.data[0]['html'];
                        this.tipoOficio = response.data[0]['id'];
                        axios.get(urlPeticion).then(response => {
                            this.info = response.data;
                            this.setData();
                        });
                    }
                    
                });   
            },
            setData: function(){
                var res = this.template.split("{{$");
                var img = this.template.split("{{@");
                let list=[];
                let listimg=[];
                let template = this.template;
                let info = this.info;
                res.map(function(value, key) {
                    if(key!=0){
                        var res2 = value.split("}}");
                        list.push(res2[0]);
                    }
                });
                img.map(function(value, key) {
                    if(key!=0){
                        var img2 = value.split("}}");
                        listimg.push(img2[0]);
                    }
                });
                let protegidos=[];
                let tag = '';
                list.map(function(value,key){
                    if(protegidos.indexOf(value)!= -1){
                        tag = value+"1";
                        protegidos.push(tag);
                    }
                    else{
                        tag = value;
                        protegidos.push(tag);
                    }
                    template = template.replace("{{$"+value+"}}","<span class='noeditable "+tag+"' id='"+tag+"'>"+info[value]+"</span>");
                });
                listimg.map(function(value,key){
                    template = template.replace("{{@"+value+"}}","<img src='"+info[value]+"'>");
                });
                this.variables = list;
                this.template = template;
                this.bloqueados = protegidos;
            },
            editar: function(){
                $(".editable").attr("contenteditable", true);
                $(".noeditable").attr('contenteditable', 'false');
            },
            fijo: function(){
                $(".editable").removeAttr("contenteditable");
            },
            imprimir: function(){
                let info = this.info;
                var correcto = true;
                this.bloqueados.map(function(value,key){
                    if ( $("#"+value).length <= 0 ){
                        correcto = false;
                    }  
                });
                if(correcto){
                    this.variables.map(function(value,key){
                        $("."+value).text(info[value]);
                    });
                    axios.post("../getToken").then(response => {
                        this.token = response.data;
                        QRCode.toCanvas(this.$refs.canvas, this.token)
                        axios.post("../saveOficio",{
                            "html" : $(".editable").html(),
                            "token": this.token,
                            "fiscal" : info['fiscal'],
                            "id_oficio": this.tipoOficio,
                        }).then(response => {
                            window.print();
                            this.$refs.canvas.width=this.$refs.canvas.width;
                        });
                    });
                }
                else{
                    axios.post("../intentos",{
                        "html" : $(".editable").html(),
                        "fiscal" : info['fiscal'],
                        "id_oficio": this.tipoOficio,
                    }).then(response => {
                    });
                }
                
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
    .editable td, .editable th{
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
    .padding th{ 
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