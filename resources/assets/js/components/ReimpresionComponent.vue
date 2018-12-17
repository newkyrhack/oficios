<template>
    <div class="contenedor">
        <table v-if="validacion" class="editable" v-html="contenido">
        </table>
        <!-- <h1 v-if="!validacion">Prueba</h1> -->
        <table v-if="!validacion" class="back" v-html="prueba">
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
                contenido:'',
                respaldo:'',
                token:'',
                validacion:true,
                prueba:'',
                myurl:''
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
            },
            nivel:{
                default:0
            }
        },
        mounted: function () {
            this.getTemplate();
            
        },
        updated: function(){
            this.clearCanvas();
            this.fijo();
        },
        methods:{
            generarUrl: function(){
                if(this.nivel!=0){
                    var contador = 0;
                    while(contador<this.nivel){
                        this.myurl += "../"
                        contador++
                    }
                }
            },
            clearCanvas: function(){
                var html = '<canvas ref="canvas" id="qr" width="50" height="50" style="display:none" ></canvas><img src="" alt="" width="80px;" style="float:right;" id="myqr">';
                $(".editable>tfoot>tr>#thqr").html(html);
            },
            fijo: function(){
                $(".edt").removeAttr("contenteditable");
                if(browser.name=='ie'||browser.name=='edge'){
                    $("#body").addClass("justificado");
                }  
            },
            getTemplate: function(){
                if(this.token2!=''){
                    var urlPeticion = this.myurl+"recuperar_token";
                    axios.post(urlPeticion,{
                        token:this.token2
                    }).then(response => {
                        if(response.data==undefined){
                            console.log("sin datos");
                        }
                        else{
                            this.contenido = response.data.html;
                            this.respaldo = response.data.html;
                            this.token = response.data.token;
                        }
                    });
                }
                else{
                    var urlPeticion = this.myurl+"recuperar";
                    axios.post(urlPeticion,{
                        tipo:this.tipo,
                        id:this.id
                    }).then(response => {
                        if(response.data==undefined){
                            console.log("sin datos");
                        }
                        else{
                            this.contenido = response.data.html;
                            this.respaldo = response.data.html;
                            this.token = response.data.token;
                        }
                    });
                }   
            },
            imprimir: function(){
                this.validacion = false;
                var self = this
                setTimeout(function(){
                    self.prueba = self.respaldo
                },100)

                setTimeout(function(){
                    window.print()
                },200)

                setTimeout(function(){
                self.validacion = true;
                self.prueba=''
                },200)

                // window.print();
                // axios.post("getToken").then(response => {
                //     this.token = response.data;
                //     QRCode.toCanvas($("#qr2"), this.token);
                //     var image = new Image();
                //     image.src = $("#qr2").toDataURL("image/png");
                //     $("#myqr").attr("src", image.src);
                //     axios.post("saveOficio",{
                //         "html" : $(".editable").html(),
                //         "token": this.token,
                //         "id_oficio": this.tipo,
                //         "id_tabla": this.id
                //     }).then(response => { 
                //         window.print();
                //         $("#myqr").attr("src", "");
                //     })
                //     .catch(error => {
                //         console.log(error)
                //     });
                // });
                // // window.print();
                // axios.post("saveOficio",{
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
    .editable,.back{
        background-color: #ffffff;
        width: 612pt;
        margin-left: auto;
        margin-right: auto;
        border: 2px solid #E3E3E3;
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
        .editable, .back{
            border: none;
        }
    }
    @page 
    {
        size:  A4 !important;
    }
</style>