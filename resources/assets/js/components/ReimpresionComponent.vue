<template>
    <div class="contenedor">
        <table class="editable">
            <thead>
                <tr>
                    <td colspan="2">
                        <div class="font16 centrado" v-html="encabezado">
                        </div>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr class="font14">
                    <td colspan="2">
                        <div class="justificado centrado" v-html="template">
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="font13">
                    <th>
                        <div class="justificado centrado" v-html="pie">
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
const { detect } = require('detect-browser');
const browser = detect();
    export default {
        data(){
            return{
                template:'',
                encabezado:'',
                pie:'',
                tipoOficio:'',
                info:[],
                token:''
            }
        },
         props:{
            tipo: {
                default:false
            },
            id:{
                id:false
            }
        },
        mounted: function () {
            this.getTemplate()
        },
        methods:{
            getTemplate: function(){
                var urlTemplate = '../oficios';
                var urlPeticion = this.url+"/"+this.id;
                axios.post(urlTemplate,{
                    tipo:this.tipo
                }).then(response => {
                    if(response.data[0]==undefined){
                        console.log("sin datos");
                    }
                    else{
                        this.template = response.data[0]['contenido'];
                        this.encabezado = response.data[0]['encabezado'];
                        this.pie = response.data[0]['pie'];
                        this.tipoOficio = response.data[0]['id'];
                        axios.get(urlPeticion).then(response => {
                            this.info = response.data;
                        });
                    }
                });   
            },
            imprimir: function(){
                let info = this.info;
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
                        "id_tabla": this.id
                    }).then(response => { 
                        window.print();
                        this.$refs.canvas.width=this.$refs.canvas.width;
                    });
                });
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
    .centrado{
        margin-left: 50px;
        margin-right: 50px;
    }
    .justificado{
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