<template>
    <div>
        <div class="page-main-actions">
            <div class="page-actions">
                <div class="d-flex align-items-center justify-content-between">
                    <h3 class="page-title mb-4">Permissions</h3>
                    <button class="btn btn-primary" @click="updatePermissions">Update</button>
                </div>
            </div>
        </div>
        <div class="card-box">
            <div class="row">
                <div class="col col-md-4 col-lg-3">
                    <div class="nav flex-column nav-pills pb-2 text-capitalize" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a v-for="role in roles" class="nav-link mt-2" :class="{active: activeRole == role.id}" @click="getPermissions(role.id)">{{role.description}}</a>
                    </div>
                </div>
                <div class="col col-md-8 col-lg-9">
                    <div v-for="group in groups">
                        <h5 class="text-capitalize mb-0">{{group}}</h5>
                        <hr class="mt-0">
                        <div class="row mb-5">
                            <div v-for="perm in permissions" class="col col-md-3" v-if="perm.group == group">
                                <input type="checkbox" v-model="selected" :value="perm.id" :id="'permission_'+perm.id">
                                {{perm.name}}
                            </div>
                        </div>
                    </div>
                    <div class="loading-screen d-flex align-items-center justify-content-center" v-if="loading">
                        <div>
                            Updating Permissions ...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
           'roles',
           'groups',
           'permissions'
        ],
        data: function () {
            return{
                activeRole: 1,
                rolePermission: null,
                selected:[],
                loading: false,
            }
        },
        mounted() {
            this.getPermissions(1);
        },
        methods:{
            getPermissions: function (id) {
                this.loading = true;
                this.activeRole = id;
                axios.post('/admin/roles/'+this.activeRole+'/permissions')
                    .then((data) => {
                      this.rolePermission = data;
                      this.selected = data.data;
                      this.loading = false;
                    })
                    .catch(function () {
                        this.$toasted.show('An error occurred while updating',{type: 'error'});
                    })
            },
            updatePermissions: function () {
                this.loading = true;
                axios.post('/admin/roles/'+this.activeRole+'/permissions/update',{'selected' : this.selected})
                    .then((data) => {
                        if(!data.error){
                            this.rolePermission = data;
                            this.selected = data.data;
                            this.$toasted.show('Updated Successfully',{type: 'success'});
                        }else{
                            this.$toasted.show(data.error,{type: 'error'});
                        }
                        this.loading = false;
                    })
                    .catch(function (resp) {

                    });

            }
        }
    }
</script>
<style>
    .loading-screen{
        position: absolute;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(241, 241, 241, .8);
        margin: 0 -15px;
    }
</style>