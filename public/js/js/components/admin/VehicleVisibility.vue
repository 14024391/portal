<template>
    <div class="tab-pane fade" id="vehicle-visibility" role="tabpanel" aria-labelledby="vehicle-visibility-tab">
        <h4 class="mb-4">Where will the advert be visible?</h4>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">AutoTrader</label>
            <div class="col-sm-8 pt-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="autoTraderWebYes" v-model="autoTraderWebYes" id="autoTraderWebYes" value="1">
                    <label class="form-check-label" for="autoTraderWebYes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="autoTraderWebYes" v-model="autoTraderWebYes" id="autoTraderWebNo" value="0">
                    <label class="form-check-label" for="autoTraderWebNo">No</label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4  col-form-label">My Website</label>
            <div class="col-sm-8  pt-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="myWebYes" v-model="myWebYes" id="myWebYes" value="1">
                    <label class="form-check-label" for="myWebYes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="myWebYes" v-model="myWebYes" id="myWebNo" value="0">
                    <label class="form-check-label" for="myWebNo">No</label>
                </div>
            </div>
        </div>
        <h4 class="my-4">Stock Management</h4>
        <div class="form-group row">
            <label for="soldStatus" class="col-sm-4 col-form-label">Sold Status</label>
            <div class="col-sm-8">
                <select id="soldStatus" class="form-control" v-model="soldStatus" style="max-width:200px;">
                    <option value="In Stock">In Stock</option>
                    <option value="Due In">Due In</option>
                    <option value="Sold">Sold</option>
                    <option value="Reserved">Reserved</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Featured</label>
            <div class="col-sm-8 pt-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="featured" v-model="featured" id="featured1" value="1">
                    <label class="form-check-label" for="featured1">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="featured" v-model="featured" id="featured0" value="0">
                    <label class="form-check-label" for="featured0">No</label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-4 col-form-label">Latest</label>
            <div class="col-sm-8 pt-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="latest" v-model="latest" id="latest1" value="1">
                    <label class="form-check-label" for="latest1">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="latest" v-model="latest" id="latest0" value="0">
                    <label class="form-check-label" for="latest0">No</label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "vehicle-visibility",
        props: ['vSoldStatus','vAutoTraderWebYes','vMyWebYes','vFeatured','vlatest'],
        data: function () {
            return {
                soldStatus: this.vSoldStatus,
                autoTraderWebYes: this.vAutoTraderWebYes,
                myWebYes: this.vMyWebYes,
                featured: this.vFeatured,
                latest: this.vlatest,
            }
        },
        watch:{
            soldStatus: function (newVal) {
                this.$emit('update:soldStatus', newVal);
            },
            autoTraderWebYes: function (newVal) {
                this.$emit('update:autoTraderWebYes', parseInt(newVal));
            },
            myWebYes: function (newVal) {
                this.$emit('update:myWebYes', parseInt(newVal));
            },
            featured: function (newVal) {
                this.$emit('update:featured', parseInt(newVal));
            },
            latest: function (newVal) {
                this.$emit('update:latest', parseInt(newVal));
            },
        }
    }
</script>

<style scoped>

</style>