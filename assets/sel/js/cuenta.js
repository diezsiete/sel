import './../css/vacantes/registro.scss';
import 'dropzone/dist/dropzone.css';
import Dropzone from 'dropzone';
import $ from 'jquery';

Dropzone.autoDiscover = false;
$(function(){
    const dropzone = new Dropzone($('.dropzone')[0], {
        paramName: 'adjunto',
        init: function() {
            this.on('error', function(file, data){
                if(data.detail){
                    this.emit('error', file, data.detail)
                }
            });
        },
        dictDefaultMessage: "Arrastre aqui archivo para subir"
    })
});
