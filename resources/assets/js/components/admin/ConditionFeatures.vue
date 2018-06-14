<template>
    <div class="tab-pane fade" id="description-&-features" role="tabpanel" aria-labelledby="description-&-features-tab">
        <h4 class="mb-4">Vehicle Description</h4>
        <div class="form-row">
            <div class="col-md-12 mb-4">
                <label for="description" class="text-right" style="display: block">{{charCount}} Characters remaining</label>
                <textarea class="form-control" id="description" rows="3" maxlength="1000" v-model="description"></textarea>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <label for="referenceNumber">Reference Number</label>
                <input id="referenceNumber" v-model="referenceNumber" type="text" class="form-control" aria-label="Reference Number" aria-describedby="referenceNumber">
            </div>
        </div>
        <h4 class="mb-4">Vehicle Features</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="card feature-list">
                    <div class="card-header">
                        <strong>Add features ({{featuresAvailable.length}})</strong>
                    </div>
                    <div class="card-body">
                        <div v-for="fa in featuresAvailable" class="d-flex">
                            <div>{{fa.feature}}</div>
                            <span class="ml-auto add-feature" @click="addFeature(fa)">
                                <i class="fas fa-plus-circle text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card feature-list">
                    <div class="card-header">
                        <strong>Remove features ({{features.length}})</strong>
                    </div>
                    <div class="card-body">
                        <div v-for="fa in features" class="d-flex">
                            <div>{{fa.feature}}</div>
                            <span class="ml-auto add-feature" @click="removeFeature(fa)">
                                <i class="fas fa-minus-circle text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: "condition-features",
        props:['vdescription','vreferenceNumber','allFeatures','vfeatures'],
        data: function () {
            return {
                description: this.vdescription,
                referenceNumber: this.vreferenceNumber,
                features: this.vfeatures ? this.vfeatures : [],
            }
        },
        computed: {
            featuresAvailable: function () {
                let features =  this.features;
                let data = this.allFeatures.filter( r => {
                    let fc = this.features.filter( f => f.id == r.id)
                    return fc.length > 0 ? false : true;

                });

                return data;
            },
            charCount: function () {
                let desc = this.description ? this.description.length : 0;
                return 1000 - desc;
            }
        },
        watch:{
            description: function (newVal) {
                this.$emit('update:description', newVal);
            },
            referenceNumber: function (newVal) {
                this.$emit('update:referenceNumber', newVal);
            },
            features: function (newVal) {
                this.$emit('update:feature', newVal);
            }
        },
        methods: {
            addFeature: function (fa) {
                this.features.push(fa);
            },
            removeFeature: function (fa) {
                this.features.pop(fa);
            }
        }
    }
</script>

<style scoped>
.add-feature{
    cursor: pointer;
}
.feature-list{
    max-height: 300px;
    height: 100%;
    margin-bottom: 10px;
}
.feature-list .card-body{
    overflow-y: scroll;
}
</style>