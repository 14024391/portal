<template>
    <div>
        <h2>Update Models</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="makes">Select Make</label>
                    <select class="form-control" id="makes" v-model='make'>
                        <option>Select Make</option>
                        <option v-for="make in makes" :key="make.id" :value="make">{{make.make}}</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <h5>Models</h5>
                <div class="row" v-if="make">
                    <div class="col-md-8">
                        <div class="form-group model-field" v-for="model in models" :key="model.id">
                            <input type="text" v-model="model.model" class="form-control"/>

                            <button class="btn btn-link delete-model" @click="deleteModel(model.id)"><i class="fas fa-minus-circle text-danger" ></i></button>
                        </div>
                        <div class="form-group"  v-if="make && models.length > 0">
                            <button class="btn btn-primary" @click="updateModels">Update</button>
                        </div>
                    </div>
                </div>
                <p v-if="!make">Select make to view models</p>
                <p v-if="make && models.length == 0">Selected make does not have any model</p>
            </div>
            <div class="col-md-6" v-if="make">
                <label for="newmodel" class="h5 mx-sm-3 ">Add New Model</label>
                <div class="form-inline">
                    <div class="form-group mx-sm-3 mb-2">
                        <input type="text" class="form-control" id="newmodel" v-model="newModel" placeholder="Model name">
                    </div>
                    <button class="btn btn-primary mb-2" @click="addModel">Add</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['makes'],
        data: function () {
            return {
                make:'',
                models: [],
                newModel:'',
             }
        },
        watch:{
            make: function (newVal) {
                this.getModels(newVal);
            }
        },
        methods: {
            getModels: function(val){
                axios.get('/api/makes/'+val.id+'/models')
                    .then( (response) => {
                        this.models = response.data.data;
                    })
                    .catch((error) => {
                        this.$toasted.error('Error occurred while fetching data. Please refresh your browser')
                    });
            },
            updateModels: function () {
                axios.post('/admin/settings/make/'+this.make.id+'/models/update',{
                    'models' : this.models
                    })
                    .then( (response) => {
                        if(response.data.error){
                            this.$toasted.error(response.data.errorMessage)
                        }else{
                            this.$toasted.success('Updated Successfully')
                        }

                    })
                    .catch((error) => {
                        this.$toasted.error('Error occurred while fetching data. Please refresh your browser')
                    });
            },
            addModel: function () {
                axios.post('/admin/settings/make/'+this.make.id+'/models/add',{
                    'model' : this.newModel
                })
                    .then( (response) => {
                        if(response.data.error){
                            this.$toasted.error(response.data.errorMessage)
                        }else{
                            this.models = response.data.models;
                            this.$toasted.success('Updated Successfully')
                            this.newModel = '';
                        }

                    })
                    .catch((error) => {
                        this.$toasted.error('Error occurred while fetching data. Please refresh your browser')
                    });
            },
            deleteModel: function (id) {
                axios.post('/admin/settings/make/'+this.make.id+'/models/delete',{
                    delete_id : id
                })
                    .then( (response) => {
                        if(response.data.error){
                            this.$toasted.error(response.data.errorMessage)
                        }else{
                            this.models = response.data.models;
                            this.$toasted.success('Updated Successfully')
                            this.deleteId = '';
                        }

                    })
                    .catch((error) => {
                        this.$toasted.error('Error occurred while fetching data. Please refresh your browser')
                    });
            }
        },
    }
</script>

<style scoped>
    .model-field {
        position: relative;
    }
    .model-field .delete-model{
        position: absolute;
        top:0px;
        right: -40px;
        font-size:1rem;
        cursor:pointer;
    }
</style>