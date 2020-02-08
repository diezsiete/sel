<template>
    <div>
        <vue-dropzone
            id="dropzone"
            v-bind:class="{
                dragging: isDragging,
                'min-height-auto': minHeightAuto
            }"
            ref="vueDropzone"
            v-on:vdropzone-success="onDropzoneSuccess"
            v-on:vdropzone-success-multiple="onDropzoneSuccessMultiple"
            :options="dropzoneOptions"
            v-on:vdropzone-drag-over="onDropzoneDragEnter"
            v-on:vdropzone-drag-leave="onDropzoneDragEnd"
            v-on:vdropzone-drop="onDropzoneDragEnd"
            v-on:vdropzone-error="onDropzoneError"
            v-on:vdropzone-sending-multiple="onDropzoneSending"
            @vdropzone-upload-progress="uploadProgress"
            @vdropzone-total-upload-progress="totalUploadProgress"
            :useCustomSlot=true
        >
            <slot></slot>
        </vue-dropzone>
    </div>
</template>

<script>
    import vue2Dropzone from 'vue2-dropzone'
    import 'vue2-dropzone/dist/vue2Dropzone.min.css'
    import { mapState } from 'vuex';

    export default {
        props: {
            url : String,
            clickable: {
                default: true
            },
            enabled : Boolean,
            minHeightAuto: {
                default: true
            }
        },
        components: {
            vueDropzone: vue2Dropzone
        },
        computed: mapState([
            'empleado'
        ]),
        data: function () {
            return {
                dropzoneOptions: {
                    url: this.url,
                    clickable: this.clickable,
                    //para que no muestre el preview de cargando archivo
                    previewTemplate: '<div></div>',
                    uploadMultiple: true,
                },
                isDragging: false
            }
        },
        methods: {
            onDropzoneSuccess(file, response) {
                if(!this.$refs.vueDropzone.options.uploadMultiple) {
                    this.$refs.vueDropzone.removeFile(file);
                    this.$store.dispatch('addArchivo', response);
                    this.$store.dispatch('disableLoading');
                }
            },
            onDropzoneSuccessMultiple(files, response) {
                for(let file in files) {
                    this.$refs.vueDropzone.removeFile(file);
                }
                this.$store.dispatch('addArchivo', response);
                this.$store.dispatch('disableLoading');
            },
            onDropzoneDragEnter(event) {
                this.isDragging = true;
            },
            onDropzoneDragEnd(event) {
                this.isDragging = false;
            },
            onDropzoneError(file, response, xhr) {
                this.$store.dispatch('disableLoading');
                let message = "Error del servidor";
                if(response.detail) {
                    message = response.detail
                } else if(response.error) {
                    message = `[${response.error.code}] ${response.error.message}`;
                    console.log(response.error.exception);
                    if(response.error.exception) {
                        message = `[${response.error.code}] ${response.error.exception[0].message}`;
                    }
                }
                this.$store.dispatch('showMessage', {message: `${file.name}: ${message}`, type: 'error'});
            },
            onDropzoneSending(file, xhr, formData) {
                formData.append('owner', this.empleado.id);
                this.$store.dispatch('enableLoading');
            },
            uploadProgress(file, progress, bytesSent) {
                console.log("progress: " + progress);
                console.log("bytesSent: " + bytesSent);
            },
            totalUploadProgress(totaluploadprogress, totalBytes, totalBytesSent) {
                console.log("totaluploadprogress: " + totaluploadprogress)
                console.log("totalBytes: " + totalBytes)
                console.log("totalBytesSent: " + totalBytesSent)
            }
        },
        mounted() {
            this.$refs.vueDropzone.disable();
        },
        watch: {
            enabled: function (enabled) {
                enabled ? this.$refs.vueDropzone.enable() : this.$refs.vueDropzone.disable();
            },
            url: function(url) {
                this.$refs.vueDropzone.setOption('url', url)
            }
        }
    }
</script>

<style lang="scss"  type="text/scss">
    #dropzone.dragging {
        border-color: blue;
    }
    .vue-dropzone.dropzone.dz-clickable {
        padding: 0;
    }
    .dropzone {
        padding: 0;
        .dz-message{
            margin: 0;
            text-align: left;
        }
    }
    .dropzone.min-height-auto {
        min-height: auto;
    }
</style>