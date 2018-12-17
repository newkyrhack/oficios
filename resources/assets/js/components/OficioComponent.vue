<template>
    <div class="contenedor">
        <table class="editable" v-on:mouseover="editar" v-on:blur="fijo" >
            <thead v-if="titulo">
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
                    <th style="padding-right:50px; height:50px; overflow:hidden;" id="thqr">
                        <canvas ref="canvas" id="qr" width="50" height="50" style="display:none" ></canvas>
                        <img src="" alt="" width="80px;" style="float:right;" id="myqr">
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
                html:'',
                tipoOficio:'',
                info:[],
                variables:[],
                bloqueados:[],
                token:'',
                myurl:''
            }
        },
         props:{
            url: {
                default:false
            },
            tipo: {
                default:false
            },
            id:{
                id:false
            },
            titulo:{
                default:true
            },
            idcarpeta:{
                default: ""
            },
            numoficio:{
                default:true
            },
            nivel:{
                default:0
            }
        },
        mounted: function () {
            this.generarUrl()
            this.getTemplate()
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
            getTemplate: function(){
                var urlTemplate = this.myurl+'oficios';
                var urlPeticion = this.url+"/"+this.id;
                axios.post(urlTemplate,{"tipo":this.tipo}).then(response => {
                    if(response.data[0]==undefined){
                        console.log("sin datos");
                    }
                    else{
                        this.template = response.data[0]['contenido'];
                        this.encabezado = response.data[0]['encabezado'];
                        this.pie = response.data[0]['pie'];
                        this.html = response.data[0]['encabezado']+response.data[0]['contenido']+response.data[0]['pie'];
                        this.tipoOficio = response.data[0]['id'];
                        axios.get(urlPeticion).then(response => {
                            this.info = response.data;
                            this.setDataEncabezado();
                            this.setDataContenido();
                            this.setDataPie();
                        });
                    }    
                });   
            },
            setDataEncabezado: function(){
                var res = this.encabezado.split("{{$");
                var img = this.encabezado.split("{{@");
                let list=this.variables;
                let listSeccion = [];
                let listimg=[];
                let encabezado = this.encabezado;
                let info = this.info;
                res.map(function(value, key) {
                    if(key!=0){
                        var res2 = value.split("}}");
                        list.push(res2[0]);
                        listSeccion.push(res2[0]);
                    }
                });
                img.map(function(value, key) {
                    if(key!=0){
                        var img2 = value.split("}}");
                        listimg.push(img2[0]);
                    }
                });
                let protegidos=this.bloqueados;
                let tag = '';
                listSeccion.map(function(value,key){
                    if(protegidos.indexOf(value)!= -1){
                        var contador = 1;
                        while(protegidos.indexOf(value+contador)!= -1){
                            contador++;
                        }
                        tag = value+contador;
                        protegidos.push(tag);
                    }
                    else{
                        tag = value;
                        protegidos.push(tag);
                    }
                    encabezado = encabezado.replace("{{$"+value+"}}","</span> <span class='noeditable "+value+"' id='"+tag+"'>"+info[value]+"</span> <span class='edt'>");
                });
                listimg.map(function(value,key){
                    encabezado = encabezado.replace("{{@"+value+"}}","<img src='"+info[value]+"'>");
                });
                encabezado = "<span class='edt'>"+encabezado+"</span>";
                this.variables = list;
                this.encabezado = encabezado;
                this.bloqueados = protegidos;
            },
            setDataContenido: function(){
                var res = this.template.split("{{$");
                var img = this.template.split("{{@");
                let list=this.variables;
                let listSeccion = [];
                let listimg=[];
                let template = this.template;
                let info = this.info;
                res.map(function(value, key) {
                    if(key!=0){
                        var res2 = value.split("}}");
                        list.push(res2[0]);
                        listSeccion.push(res2[0]);
                    }
                });
                img.map(function(value, key) {
                    if(key!=0){
                        var img2 = value.split("}}");
                        listimg.push(img2[0]);
                    }
                });
                let protegidos=this.bloqueados;
                let tag = '';
                listSeccion.map(function(value,key){
                    if(protegidos.indexOf(value)!= -1){
                        var contador = 1;
                        while(protegidos.indexOf(value+contador)!= -1){
                            contador++;
                        }
                        tag = value+contador;
                        protegidos.push(tag);
                    }
                    else{
                        tag = value;
                        protegidos.push(tag);
                    }
                    template = template.replace("{{$"+value+"}}","</span> <span class='noeditable "+value+"' id='"+tag+"'>"+info[value]+"</span><span class='edt'>");
                });
                listimg.map(function(value,key){
                    template = template.replace("{{@"+value+"}}","<img src='"+info[value]+"'>");
                });
                template = "<span class='edt'>"+template+"</span>";
                this.variables = list;
                this.template = template;
                this.bloqueados = protegidos;
            },
            setDataPie: function(){
                var res = this.pie.split("{{$");
                var img = this.pie.split("{{@");
                let list=this.variables;
                let listSeccion = [];
                let listimg=[];
                let pie = this.pie;
                let info = this.info;
                res.map(function(value, key) {
                    if(key!=0){
                        var res2 = value.split("}}");
                        list.push(res2[0]);
                        listSeccion.push(res2[0]);
                    }
                });
                img.map(function(value, key) {
                    if(key!=0){
                        var img2 = value.split("}}");
                        listimg.push(img2[0]);
                    }
                });
                let protegidos=this.bloqueados;
                let tag = '';
                listSeccion.map(function(value,key){
                    if(protegidos.indexOf(value)!= -1){
                        var contador = 1;
                        while(protegidos.indexOf(value+contador)!= -1){
                            contador++;
                        }
                        tag = value+contador;
                        protegidos.push(tag);
                    }
                    else{
                        tag = value;
                        protegidos.push(tag);
                    }
                    pie = pie.replace("{{$"+value+"}}","</span> <span class='noeditable "+value+"' id='"+tag+"'>"+info[value]+"</span> <span class='edt'>");
                });
                listimg.map(function(value,key){
                    pie = pie.replace("{{@"+value+"}}","<img src='"+info[value]+"'>");
                });
                pie = "<span class='edt'>"+pie+"</span>";
                this.variables = list;
                this.pie = pie;
                this.bloqueados = protegidos;
            },
            editar: function(){
                $(".edt").attr("contenteditable", true);
                $(".noeditable").attr('contenteditable', 'false');
                if (browser.name=='ie'||browser.name=='edge'){
                    $("#body").removeClass("justificado");
                }    
            },
            fijo: function(){
                $(".edt").removeAttr("contenteditable");
                if(browser.name=='ie'||browser.name=='edge'){
                    $("#body").addClass("justificado");
                }  
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
                    this.bloqueados.map(function(value,key){
                        var iden = value.substring(0,value.length-1);
                        var ultimo = isNaN(value.charAt(value.length-1));
                        if(ultimo){
                            $("#"+value).replaceWith(info[value]);
                        }
                        else{
                            $("#"+value).replaceWith(info[iden]);
                        }
                    });
                    axios.post(this.myurl+"getToken").then(response => {
                        this.token = response.data;
                        QRCode.toCanvas(this.$refs.canvas, this.token)
                        var image = new Image();
                        image.src = this.$refs.canvas.toDataURL("image/png");
                        $("#myqr").attr("src", image.src);
                        axios.post(this.myurl+"saveOficio",{
                            "html" : $(".editable").html(),
                            "token": this.token,
                            "fiscal" : info['fiscal'],
                            "id_oficio": this.tipoOficio,
                            "id_tabla": this.id,
                            "idCarpeta": this.idcarpeta,
                            "numOficio": this.numoficio
                        }).then(response => { 
                            window.print();
                            $("#myqr").attr("src", "");
                        });
                    });
                }
                else{
                    axios.post(this.myurl+"intentos",{
                        "html" : $(".editable").html(),
                        "fiscal" : info['fiscal'],
                        "id_oficio": this.tipoOficio,
                        "id_tabla": this.id,
                        "idCarpeta": this.idcarpeta,
                        "numOficio": this.numoficio
                    }).then(response => {
                    });
                }    
            }
       }
    }
</script>
<style>
    *{
        font-family: 'neosanspro-regular';
    }
    body{
        background-color: #F0F0F0;
    }
    .editable{
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
        }
    }
    @page {
        size:  letter !important;
    }
</style>