<template>
    <div class="tab-pane fade" id="condition-&-history" role="tabpanel" aria-labelledby="condition-&-history-tab">
        <h4 class="mb-4">Condition</h4>
        <div class="form-row">
            <div class="col-md-6 col-lg-4 mb-4">
                <label for="bodyCondition">Body Condition</label>
                <select id="bodyCondition" class="form-control" v-model="condition.bodyCondition">
                    <option value="">Select the body condition...</option>
                    <option value="100">New</option>
                    <option value="95">Excellent</option>
                    <option value="85">Clean</option>
                    <option value="65">Good</option>
                    <option value="50">Average</option>
                    <option value="35">Poor</option>
                </select>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <label for="interiorCondition">Interior Condition</label>
                <select id="interiorCondition" class="form-control" v-model="condition.interiorCondition">
                    <option value="">Select the interior condition...</option>
                    <option value="100">New</option>
                    <option value="95">Excellent</option>
                    <option value="85">Clean</option>
                    <option value="65">Good</option>
                    <option value="50">Average</option>
                    <option value="35">Poor</option>
                </select>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <label for="tyreCondition">TyreCondition</label>
                <select id="tyreCondition" class="form-control" v-model="condition.tyreCondition">
                    <option value="">Select the tyre condition...</option>
                    <option value="100">New</option>
                    <option value="90">90%</option>
                    <option value="75">75%</option>
                    <option value="50">50%</option>
                    <option value="25">25%</option>
                </select>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <label for="overallCondition">Overall Condition</label>
                <select id="overallCondition" class="form-control" v-model="condition.overallCondition">
                    <option value="">Select the overall condition...</option>
                    <option value="100">New</option>
                    <option value="95">Excellent</option>
                    <option value="85">Clean</option>
                    <option value="65">Good</option>
                    <option value="50">Average</option>
                    <option value="35">Poor</option>
                </select>
            </div>
        </div>
        <h4 class="mb-4" v-show="showHistory">History</h4>
        <div class="form-row" v-show="showHistory">
            <div class="col-md-6 col-lg-4 mb-4">
                <label for="previousHistory">Previous Owners</label>
                <select id="previousHistory" class="form-control" v-model="history.previousOwner">
                    <option value="">Select the number of previous owners...</option>
                    <option v-for="i in 20" :value="i-1">{{i-1}}</option>
                </select>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
                <label for="serviceHistory">Service History</label>
                <select id="serviceHistory" class="form-control" v-model="history.serviceHistory">
                    <option value="">Select a service history...</option>
                    <option value="Full franchise service history">Full franchise service history</option>
                    <option value="Full service history partially by franchise">Full service history partially by franchise</option>
                    <option value="Full service history non franchise">Full service history non franchise</option>
                    <option value="Partial service history">Partial service history</option>
                    <option value="No service history">No service history</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6 col-lg-4 mb-4" v-show="showMot">
                <label for="motdatepicker">MOT next due in...</label>
                <div type="text" v-model="history.motDate" id="motdatepicker"
                     data-language='en' data-position="right top"
                     data-min-view="months" data-view="months" data-date-format="MM yyyy"
                ></div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4" v-show="showInspectionDate">
                <label for="lastInspectionDate">Last inspection date</label>
                <div type="text" v-model="history.lastInspectionDate" id="lastInspectionDate"
                     data-language='en' data-position="right top" data-date-format="dd-mm-yyyy"
                ></div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "condition--history",
        props:['type','vhistory','vcondition'],
        data: function () {
            return {
                condition:this.vcondition,
                history: this.vhistory,
                showHistory: false,
                showMot: false,
                showInspectionDate: false
            }
        },

        mounted: function () {
            $('#motdatepicker').datepicker({
                onSelect: (formattedDate, date, inst) => {
                    this.history.motDate = formattedDate

                }
            });
            $('#lastInspectionDate').datepicker({
                onSelect: (formattedDate, date, inst) => {
                    this.history.lastInspectionDate = formattedDate
                }
            });

            switch (this.type){
                case 'Truck':
                    this.showHistory = true;
                    this.showMot = true;
                    this.showInspectionDate = true;
                    break;
                case 'Plant':
                    this.showHistory = false;
                    this.showMot = false;
                    this.showInspectionDate = false;
                    break;
                case 'Farm':
                    this.showHistory = false;
                    this.showMot = false;
                    this.showInspectionDate = false;
                    break;
                case 'Car':
                    this.showHistory = true;
                    this.showMot = false;
                    this.showInspectionDate = false;
                    break;
                case 'Van':
                    this.showHistory = true;
                    this.showMot = true;
                    this.showInspectionDate = false;
                    break;
            }
        },
        updated: function() {
            this.$emit('update:condition', this.condition);
            this.$emit('update:history', this.history);
        },
    }
</script>

<style scoped>

</style>