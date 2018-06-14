<template>
    <div class="tab-pane fade" id="images-&-video" role="tabpanel" aria-labelledby="images-&-video-tab">
        <div class="d-lg-flex">
            <div>
                <h4 class="mb-0">Add up to 20 images</h4>
                <p class="mb-4 font-weight-light text-secondary">For best results, we recommend your images are landscape</p>
            </div>
            <div class="ml-auto p-2 text-right">
                <div class="upload-btn-wrapper">
                    <label for="vehicle-photos" class="btn btn-primary">Upload Images</label>
                    <input type="file" name="photos[]" id="vehicle-photos" multiple="multiple" @change="uploadPhotos"/>
                </div>
            </div>
        </div>
        <div class="mb-4">
            <vue-dropzone ref="imageDropzone" id="dropzone" :options="dropzoneOptions"
                          v-on:vdropzone-success="vdropzoneSuccess"
            ></vue-dropzone>
        </div>
        <div class="clearfix">
            <draggable v-model="images" @end="onEnd">
                <div  v-for="image in images" :key="image.id" class="vehicle-img" :class="{default: image.default == 1}">
                    <img :src="'/storage/'+image.image" class="img-fluid"/>
                    <div class="set-default d-flex align-items-center justify-content-center">
                        <button type="button" @click="updatePhoto(image.id,'DEFAULT')" class="btn btn-outline-primary btn-sm">Set Default</button>
                    </div>
                    <div class="delete-photo" @click="updatePhoto(image.id,'DELETE')">
                        <i class="far fa-trash-alt"></i>
                    </div>
                    <a class="ext-link" :href="'/storage/'+image.image" target="_blank"><i class="fas fa-external-link-alt"></i></a>
                </div>
            </draggable>
        </div>
        <div class="clearfix mt-4">
            <div class="form-group">
                <label  class="h4" for="youtubelink">YouTube Video</label>
                <input type="text" class="form-control" id="youtubelink" aria-describedby="youtubelink" v-model="youtubeLink" @change="updateYoutube()" placeholder="https://www.youtube.com/watch?v=">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "image-videos",
        props: ['vehicleId','vimages','videoLinks'],
        data: function () {
            return {
                images: this.vimages,
                youtubeLink: this.videoLinks ? this.videoLinks.link : '',
                dropzoneOptions: {
                    url: 'https://httpbin.org/post',
                    thumbnailWidth: 115,
                    thumbnailHeight: 86,
                    maxFilesize: 200,
                    forceChunking: true,
                }
            }
        },
        methods:{
            vdropzoneSuccess: function(file){
                if(this.vehicleId == null){
                    this.$toasted.error('Please save the vehicle before uploading Image');
                    return;
                }
                this.$emit('update:loading', true);
                let formData = new FormData();
                formData.append("photos[]", file);
                formData.append( 'vehicle_id', this.vehicleId);

                axios.post('/admin/vehicle/upload/photos',formData)
                    .then( (response) => {
                        this.images = response.data.images;
                        let defaultImg = this.images.filter(r => r.default == 1)[0]
                        this.$emit('update:default',defaultImg)
                        this.$emit('update:loading', false);
                    })
                    .catch((error) => {
                        this.$emit('update:loading', false);
                        this.$toasted.error('Error occurred while uploading Images. Please refresh your browser');
                    });

                this.$refs.imageDropzone.removeFile(file);
            },
            uploadPhotos: function () {
                if(this.vehicleId == null){
                    this.$toasted.error('Please save the vehicle before uploading Image');
                    return;
                }
                this.$emit('update:loading', true);
                let formData = new FormData();
                let ins = document.getElementById('vehicle-photos').files.length;
                for (let x = 0; x < ins; x++) {
                    formData.append("photos[]", document.getElementById('vehicle-photos').files[x]);
                }
                formData.append( 'vehicle_id', this.vehicleId);

                axios.post('/admin/vehicle/upload/photos',formData)
                    .then( (response) => {
                        this.images = response.data.images;
                        let defaultImg = this.images.filter(r => r.default == 1)[0]
                        this.$emit('update:default',defaultImg)
                        this.$emit('update:loading', false);
                    })
                    .catch((error) => {
                        this.$emit('update:loading', false);
                        this.$toasted.error('Error occurred while uploading Images. Please refresh your browser');
                    });
            },
            updatePhoto: function (id,action) {
                this.$emit('update:loading', true);
                axios.post('/admin/vehicle/update/photos',{
                    photo_id : id,
                    vehicle_id: this.vehicleId,
                    action: action
                    })
                    .then( (response) => {
                        this.images = response.data.images;
                        let defaultImg = this.images.filter(r => r.default == 1)[0]
                        this.$emit('update:default',defaultImg)
                        this.$emit('update:loading', false);
                    })
                    .catch((error) =>  {
                        this.$emit('update:loading', false);
                        this.$toasted.error(error)
                    });
            },

            updateYoutube: function () {
                this.$emit('update:loading', true);
                axios.post('/admin/vehicle/update/youtube',{
                    vehicle_id: this.vehicleId,
                    youtubeLink: this.youtubeLink,
                    type: 'youtube'
                })
                    .then( (response) => {
                        this.$emit('update:loading', false);
                    })
                    .catch((error) =>  {
                        this.$emit('update:loading', false);
                        this.$toasted.error(error)
                    });
            },
            onEnd: function () {
                let i = 0;
                this.images = this.images.map( r => {r.position = i++; return r});
                this.$emit('update:loading', true);
                axios.post('/admin/vehicle/update/photos/position',{
                    vehicle_id: this.vehicleId,
                    photos: this.images,
                    action: 'position'
                })
                    .then( (response) => {
                        this.$emit('update:loading', false);
                    })
                    .catch((error) =>  {
                        this.$emit('update:loading', false);
                        this.$toasted.error(error)
                    });
            },
        }
    }
</script>

<style scoped>


</style>