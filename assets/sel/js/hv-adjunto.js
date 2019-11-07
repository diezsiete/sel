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
            this.on('success', function(file, response) {
                const $embed = $('embed');
                $embed.attr('src', $embed.attr('src')).closest('.row').removeClass('hidden');
            })
        },
        dictDefaultMessage: "Arrastre aqui archivo para subir"
    })
});
