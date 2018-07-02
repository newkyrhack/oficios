<template>
    <div class="contenedor">
        <table class="editable" v-html="contenido">
        </table>
        <!-- <canvas ref="canvas"></canvas> -->
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
                contenido:'',
                token:''
            }
        },
         props:{
            tipo: {
                default:false
            },
            id:{
                id:false
            },
            token2:{
                default:''
            }
        },
        mounted: function () {
            this.getTemplate()
        },
        methods:{
            getTemplate: function(){
                if(this.token2!=''){
                    console.log("entro");
                    var urlPeticion = "./recuperar_token";
                    axios.post(urlPeticion,{
                        token:this.token2
                    }).then(response => {
                        if(response.data==undefined){
                            console.log("sin datos");
                        }
                        else{
                            this.contenido = response.data.html;
                            this.token = response.data.token;
                        }
                    });
                }
                else{
                    console.log("entrooo");
                    var urlPeticion = "./recuperar";
                    axios.post(urlPeticion,{
                        tipo:this.tipo,
                        id:this.id
                    }).then(response => {
                        if(response.data==undefined){
                            console.log("sin datos");
                        }
                        else{
                            this.contenido = response.data.html;
                            this.token = response.data.token;
                        }
                    });
                }   
            },
            imprimir: function(){
                window.print();
                // axios.post("./saveOficio",{
                //     "html" : $(".editable").html(),
                //     "token": this.token,
                //     "fiscal" : info['fiscal'],
                //     "id_oficio": this.tipoOficio,
                //     "id_tabla": this.id
                // }).then(response => { 
                //     window.print();
                // });
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