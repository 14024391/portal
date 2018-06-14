<template>
    <div class="tab-pane fade show active" id="vechile-details" role="tabpanel" aria-labelledby="vechile-details-tab">
            <h4 class="mb-4">Registration Details</h4>
            <div class="form-row">
                <div class="col-md-6 col-lg-4 mb-4" v-if="showCategory">
                    <label>Category</label>
                    <autocomplete
                            :select="true"
                            :items="vehicledata.categories"
                            :loopKey="'id'"
                            :loopVal="'category'"
                            :initialKey="'id'"
                            :initialVal="vehicle.category"
                            :input.sync="vehicle.category"
                            :required="true">
                    </autocomplete>
                    <div class="invalid-feedback">
                        Please choose a Category.
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showSubCategory">
                    <label>Sub Category</label>
                    <autocomplete
                            :select="true"
                            :items="subcategories"
                            :loopKey="'id'"
                            :loopVal="'sub_category'"
                            :initialKey="'id'"
                            :initialVal="vehicle.subcategory"
                            :input.sync="vehicle.subcategory"
                            :required="true">
                    </autocomplete>
                    <div class="invalid-feedback">
                        Please choose a SubCategory.
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4">
                    <label>New or Used</label>
                    <div class="border-0">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" v-model="vehicle.vehicleNew" type="radio" id="isNewRadio1" name="condition" :value="true">
                            <label class="form-check-label" for="isNewRadio1">New</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" v-model="vehicle.vehicleNew" type="radio" id="isNewRadio2"  name="condition" :value="false">
                            <label class="form-check-label" for="isNewRadio2">Used</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row" v-if="showSerialNumber">
                <div id="group-serialno" class="input-group mb-4 col-md-6 col-lg-4">
                    <div class="input-group-prepend">
                        <img src="/images/reg-plate-icon.svg">
                    </div>
                    <input type="text" v-model="vehicle.serialNumber" class="form-control form-control-lg" placeholder="ENTER REG" aria-label="ENTER REG" aria-describedby="basic-addon1">
                </div>
                <div class="input-group mb-4 col-md-6 col-lg-4">
                    <button type="link" class="btn btn-primary " @click.stop.prevent="lookup">LOOKUP</button>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 col-lg-4 mb-4 add-to-dropdown" >
                    <label>Make</label>
                    <autocomplete
                                  :select="true"
                                  :items="vehicledata.makes"
                                  :loopKey="'id'"
                                  :loopVal="'make'"
                                  :initialKey="'id'"
                                  :initialVal="vehicle.make"
                                  :input.sync="vehicle.make"
                                  :required="isModelRequired">
                    </autocomplete>
                    <div class="invalid-feedback">
                        Please choose Vehicle Model.
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4 add-to-dropdown">
                    <label>Model</label>
                    <autocomplete
                                  :select="true"
                                  :items="models"
                                  :loopKey="'id'"
                                  :loopVal="'model'"
                                  :initialKey="'id'"
                                  :initialVal="vehicle.model"
                                  :input.sync="vehicle.model"
                                  :required="isModelRequired">
                    </autocomplete>
                    <div class="invalid-feedback">
                        Please choose Vehicle Model.
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showDerivative">
                    <label for="derivative">Derivative</label>
                    <input id="derivative" v-model="vehicle.derivative" type="text" class="form-control"  aria-label="Derivative" aria-describedby="derivative">
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showRegistration">
                    <label>Registration Year</label>
                    <autocomplete
                                  :select="false"
                                  :items="regYears"
                                  :loopKey="'id'"
                                  :loopVal="'year'"
                                  :initialKey="'id'"
                                  :initialVal="vehicle.registrationYear"
                                  :input.sync="vehicle.registrationYear"
                                  :required="true">
                    </autocomplete>
                    <div class="invalid-feedback">
                        Please choose Registration Year.
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4 add-to-dropdown" v-if="showRegistration">
                    <label>Registration Plate</label>
                    <autocomplete
                                  :select="true"
                                  :items="vehicledata.registrationPlates"
                                  :loopKey="'id'"
                                  :loopVal="'plate'"
                                  :initialKey="'plate'"
                                  :initialVal="vehicle.registrationPlate"
                                  :input.sync="vehicle.registrationPlate"
                                  :required="false">
                    </autocomplete>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showMileage">
                    <label for="mileage">Mileage</label>
                    <div class="input-group">
                        <input id="mileage" v-model="vehicle.mileage" type="text" class="form-control" aria-label="Mileage" aria-describedby="Mileage">
                        <div class="input-group-append">
                            <select v-model="vehicle.mileageUnit" class="form-control">
                                <option value="Miles">Miles</option>
                                <option value="KM">KM</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4 add-to-dropdown" v-if="showBodyType">
                    <label>Body Type</label>
                    <autocomplete
                                  :select="true"
                                  :items="bodyTypes"
                                  :loopKey="'id'"
                                  :loopVal="'body_type'"
                                  :initialKey="'body_type'"
                                  :initialVal="vehicle.bodyType"
                                  :input.sync="vehicle.bodyType"
                                  :required="false">
                    </autocomplete>
                    <div class="invalid-feedback">
                        Please choose Body Type.
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4 add-to-dropdown" v-if="showCabType">
                    <label>Cab Type</label>
                    <autocomplete
                                  :select="true"
                                  :items="vehicledata.cabTypes"
                                  :loopKey="'id'"
                                  :loopVal="'cab_type'"
                                  :initialKey="'cab_type'"
                                  :initialVal="vehicle.cabType"
                                  :input.sync="vehicle.cabType"
                                  :required="false">
                    </autocomplete>
                    <div class="invalid-feedback">
                        Please choose Cab Type.
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showAxleDrive">
                    <label for="axleDrive">Axle / Drive</label>
                    <select id="axleDrive" v-model="vehicle.axleDrive" class="form-control" :required="isAxleDriveRequired">
                        <option value=""></option>
                        <option v-for="item in axleDrives" :value="item">{{item}}</option>
                    </select>
                    <div class="invalid-feedback">
                        Please choose Axle.
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showDriverPosition">
                    <label>Driver Position</label>
                    <div class="form-control border-0">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" v-model="vehicle.isLeftDriverPosition" type="radio" id="isLeftDriverPosition1" name="isLeftDriverPosition" :value="true">
                            <label class="form-check-label" for="isLeftDriverPosition1">Left</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" v-model="vehicle.isLeftDriverPosition" type="radio" id="isLeftDriverPosition2"  name="isLeftDriverPosition" :value="false">
                            <label class="form-check-label" for="isLeftDriverPosition2">Right</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showManufacturerYear">
                    <label>Select manufacturer year.</label>
                    <autocomplete
                            :items="manfYears"
                            :loopKey="'id'"
                            :loopVal="'year'"
                            :initialKey="'id'"
                            :initialVal="vehicle.manufacturerYear"
                            :input.sync="vehicle.manufacturerYear"
                            :required="true">
                    </autocomplete>
                    <div class="invalid-feedback">
                        Please choose Manufacturer Year.
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showHoursUsed">
                    <label for="hoursUsed">Hours in operation</label>
                    <input id="hoursUsed" v-model="vehicle.hoursUsed" type="text" class="form-control"  aria-label="hoursUsed" aria-describedby="hoursUsed">
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showWheelbase">
                    <label for="wheelbaseType">Wheelbase</label>
                    <select id="wheelbaseType" v-model="vehicle.wheelbaseType" class="form-control">
                        <option>Choose the wheelbase type...</option>
                        <option value="LWB">LWB</option>
                        <option value="MWB">MWB</option>
                        <option value="SWB">SWB</option>
                    </select>
                </div>
            </div>
            <h4 class="my-4" v-if="showSalePrice">Pricing details</h4>
            <div class="form-row" v-if="showSalePrice">
                <div class="col-md-6 col-lg-4 mb-4">
                    <label for="salePrice">Sale Price</label>
                    <div class="input-group">
                        <input id="salePrice" v-model="vehicle.salePrice" type="number" class="form-control" aria-label="Sale-Price" aria-describedby="sale-price" required>
                        <div class="input-group-append">
                            <select v-model="vehicle.salePriceCurrency" class="form-control">
                                <option value="GBP">GBP</option>
                                <option value="EUR">EUR</option>
                            </select>
                        </div>
                        <div class="invalid-feedback">
                            Please choose a Sale Price.
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showVat">
                    <label>Show +VAT</label>
                    <div class="form-control border-0">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" v-model="vehicle.vat" type="radio" id="showvat1" name="showVat" :value="true">
                            <label class="form-check-label" for="showVat1">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" v-model="vehicle.vat" type="radio" id="showvat2"  name="showVat" :value="false">
                            <label class="form-check-label" for="showvat2">No</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showPoa">
                    <label>Show price as POA</label>
                    <div class="form-control border-0">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" v-model="vehicle.poa" type="radio" id="showpoa1" name="showpoa" :value="true">
                            <label class="form-check-label" for="showPoa1">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" v-model="vehicle.poa" type="radio" id="showpoa2"  name="showpoa" :value="false">
                            <label class="form-check-label" for="showPoa2">No</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showMonthlyLease">
                    <label for="monthlyLease">Monthly Lease Price</label>
                    <div class="input-group">
                        <input id="monthlyLease" v-model="vehicle.monthlyLease" type="number" class="form-control" aria-label="Monthly Lease Price" aria-describedby="monthlyLease">
                        <div class="input-group-append">
                            <select v-model="vehicle.monthlyLeaseCurrency" class="form-control">
                                <option value="GBP">GBP</option>
                                <option value="EUR">EUR</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</template>

<script>
    export default {
        name: "vehicle-details",
        props: ['type','pvehicle','vehicledata'],
        data: function () {
            return {
                formUrl: '/admin/vehicle/store',
                formAction: 'store',
                category: [],
                subcategories: [],
                models: [],
                bodyTypes: [],
                vehicle: this.pvehicle,
                currYear: (new Date()).getFullYear(),
                showRegistration: false,
                showMileage: false,
                showBodyType: false,
                showAxleDrive: false,
                showCabType: false,
                showDriverPosition: false,
                showSalePrice: false,
                showVat: false,
                showPoa: false,
                showMonthlyLease: false,
                showMonthLease:false,
                showSubmitBtn: true,
                showCategory: true,
                showSubCategory: false,
                showSerialNumber: true,
                showDerivative: true,
                showWheelbase: false,
                showManufacturerYear: false,
                showHoursUsed: false,
                isAxleDriveRequired: false,
            }
        },
        mounted: function(){
            this.bodyTypes = this.vehicledata.bodyTypes.filter( r => r.type_id == this.type.id);
            if(this.type.type != 'Truck'){
                this.setShowAttribs(this.type.type);
                this.$emit('update:showSpecifications', true);
            }
            if(this.pvehicle.vehicle_id != null){
                this.formUrl = '/admin/vehicle/update';
                this.formAction = 'update';
                let catid = this.pvehicle.category;
                this.category = this.vehicledata.categories.find( r => r.id == catid);
                this.subcategories = this.vehicledata.subcategories.filter( r => r.category_id == catid)
                if(this.type.type === 'Truck'){
                    this.setShowAttribs(this.type.type,this.category.category);
                }else{
                    this.setShowAttribs(this.type.type,null);
                }
                this.models = this.vehicledata.models.filter( r => r.make_id == this.pvehicle.make);
                this.vehicle.model = this.pvehicle.model;
                this.$emit('update:showSpecifications', true);
            }
        },
        updated: function(){
            this.$emit('update:vehicleDetails', this.vehicle);
        },
        watch:{
            'vehicle.category': function (newValue) {
                this.category = this.vehicledata.categories.filter( r => r.id == newValue)[0];
                this.subcategories = this.vehicledata.subcategories.filter( r => r.category_id == newValue)
                this.vehicle.category = newValue;
                if(this.type.type == 'Truck' && this.category){
                    this.setShowAttribs(this.type.type,this.category.category);
                }else{
                    this.setShowAttribs(this.type.type,null);
                }
                if(this.category){
                    this.$emit('update:showSpecifications', true);
                }else{
                    this.$emit('update:showSpecifications', false);
                }

            },
            'vehicle.make': function (newValue) {
                this.models = this.vehicledata.models.filter( r => r.make_id == newValue)
                this.vehicle.make = newValue;

            },
            'vehicle.model': function (newValue) {
                let model = this.vehicledata.models.filter( r => r.id == newValue)[0]
                this.vehicle.model = newValue;
            },
        },
        computed: {
            isModelRequired: function () {
                if (this.type.type == 'Truck' || this.type.type == 'Car' || this.type.type == 'Van'){
                    return true;
                }
                return false;
            },
            axleDrives: function () {
                switch (this.type.type){
                    case 'Truck':
                        return ['4x2','4x4','6x2','6x4','6x6','8x2','8x4','8x6','8x8'];
                        break;
                    case 'Plant':
                        return ['4x2','4x4','6x2','6x4','6x6','8x2','8x4','8x6','8x8'];
                        break;
                    case 'Farm':
                        return ['4x2','4x4','6x2','6x4','6x6','8x2','8x4','8x6'];
                        break;
                    case 'Car':
                        return [];
                        break;
                    case 'Van':
                        return ['4x2','4x4','6x2','6x4','6x6'];
                        break;
                }
            },
            regYears: function () {
                let currYear = (new Date()).getFullYear();
                let years = [];
                for (let i = currYear; currYear - i <=30; i--) {
                    years.push({'id': i,'year': ''+i+''})
                }
                return years;
            },
            manfYears: function () {
                let currYear = (new Date()).getFullYear();
                let years = [];
                for (let i = currYear; currYear - i <= 50; i--) {
                    years.push({'id': i,'year': ''+i+''})
                }
                return years;
            }
        },
        methods: {
            setShowAttribs: function (type,cat) {
                switch (true){
                    case (cat == 'Rigid Trucks'):
                        this.showRegistration = true;
                        this.showMileage = true;
                        this.showBodyType = true;
                        this.showAxleDrive = true;
                        this.showCabType = true;
                        this.showDriverPosition = true;
                        this.showWheelbase = false;
                        this.showSalePrice = true;
                        this.showVat = true;
                        this.showPoa = true;
                        this.showMonthlyLease = true;
                        this.showDerivative = true;
                        this.showSerialNumber = true;
                        this.showManufacturerYear = false;
                        this.showSubCategory = false;
                        this.showHoursUsed = false;
                        this.isAxleDriveRequired = true;
                        break;
                    case (cat == 'Tractor Units'):
                        this.showRegistration = true;
                        this.showMileage = true;
                        this.showBodyType = false;
                        this.showAxleDrive = true;
                        this.showCabType = true;
                        this.showDriverPosition = true;
                        this.showWheelbase = false;
                        this.showSalePrice = true;
                        this.showVat = true;
                        this.showPoa = true;
                        this.showMonthlyLease = true;
                        this.showDerivative = true;
                        this.showSerialNumber = true;
                        this.showManufacturerYear = false;
                        this.showSubCategory = false;
                        this.showHoursUsed = false;
                        this.isAxleDriveRequired = true;
                        break;
                    case (cat == 'Trailers'):
                        this.showRegistration = false;
                        this.showMileage = false;
                        this.showBodyType = true;
                        this.showAxleDrive = false;
                        this.showCabType = false;
                        this.showDriverPosition = false;
                        this.showWheelbase = false;
                        this.showSalePrice = true;
                        this.showVat = true;
                        this.showPoa = true;
                        this.showMonthlyLease = true;
                        this.showDerivative = false;
                        this.showSerialNumber = false;
                        this.showManufacturerYear = true;
                        this.showSubCategory = true;
                        this.showHoursUsed = false;
                        this.isAxleDriveRequired = false;
                        break;
                    case (type == 'Plant' || type == 'Farm'):
                        this.showRegistration = false;
                        this.showMileage = false;
                        this.showBodyType = false;
                        this.showAxleDrive = true;
                        this.showCabType = false;
                        this.showDriverPosition = false;
                        this.showWheelbase = false;
                        this.showSalePrice = true;
                        this.showVat = true;
                        this.showPoa = true;
                        this.showMonthlyLease = true;
                        this.showDerivative = true;
                        this.showSerialNumber = false;
                        this.showManufacturerYear = true;
                        this.showSubCategory = true;
                        this.showHoursUsed = true;
                        break;
                    case (type == 'Car'):
                        this.showCategory = false;
                        this.showSubCategory = false;
                        this.showSerialNumber = true;
                        this.showRegistration = true;
                        this.showDerivative = true;
                        this.showMileage = true;
                        this.showBodyType = true;
                        this.showAxleDrive = false;
                        this.showCabType = false;
                        this.showDriverPosition = false;
                        this.showWheelbase = false;
                        this.showSalePrice = true;
                        this.showVat = true;
                        this.showPoa = false;
                        this.showMonthlyLease = false;
                        this.showManufacturerYear = false;
                        this.showHoursUsed = false;
                        break;
                    case (type == 'Van'):
                        this.showCategory = false;
                        this.showSubCategory = false;
                        this.showSerialNumber = true;
                        this.showRegistration = true;
                        this.showDerivative = true;
                        this.showMileage = true;
                        this.showBodyType = true;
                        this.showAxleDrive = true;
                        this.showCabType = true;
                        this.showDriverPosition = true;
                        this.showWheelbase = true;
                        this.showSalePrice = true;
                        this.showVat = true;
                        this.showPoa = false;
                        this.showMonthlyLease = false;
                        this.showManufacturerYear = false;
                        this.showHoursUsed = false;
                        break;
                     default:
                         this.showRegistration = false;
                         this.showMileage = false;
                         this.showBodyType = false;
                         this.showAxleDrive = false;
                         this.showCabType = false;
                         this.showDriverPosition = false;
                         this.showWheelbase = false;
                         this.showSalePrice = false;
                         this.showVat = false;
                         this.showPoa = false;
                         this.showMonthlyLease = false;
                         this.showDerivative = false;
                         this.showSerialNumber = false;
                         this.showManufacturerYear = false;
                         this.showSubCategory = false;
                         this.showHoursUsed = false;
                         this.isAxleDriveRequired = false;
                         break
                }
            },

            lookup: function () {
                this.$emit('update:loading', true);
                let vehicleVrm = this.vehicle.serialNumber;
                axios.post('/admin/vehicle/lookup',{
                        vehicleVrm: vehicleVrm,
                        type: this.type
                    })
                    .then( (response) => {
                        let resp = response.data;
                        if(resp.error){
                            this.$toasted.error(resp.message)
                            this.$emit('update:loading', false);
                        }else if (resp.data.AuthenticationFailureDetails) {
                            this.$toasted.error(resp.data.AuthenticationFailureDetails.Reason)
                            this.$emit('update:loading', false);
                        }
                        else{
                            let response = resp.data.Response;
                            switch (response.StatusCode){
                                case 'KeyInvalid':
                                    this.$toasted.error(response.StatusMessage);
                                    break;
                                case 'SandboxLimitation':
                                    this.$toasted.error(response.StatusMessage);
                                    break;
                                case 'Success':
                                    axios.get('/admin/vehicles/vehicledata/'+this.type.id)
                                        .then( (resp) => {
                                            this.$emit('update:vehicleData',resp.data.data);
                                            this.$emit('updateFromLookup',response.DataItems);
                                        })
                                        .catch((error) => {
                                            this.$toasted.error(error);
                                        });
                                    break;
                            }
                            this.$emit('update:loading', false);
                        }
                    })
                    .catch((error) => {
                        this.$emit('update:loading', false);
                        this.$toasted.error(error)
                    });
            },
        }
    }
</script>

<style scoped>
    #group-serialno img{
        height:100%;
    }
    #group-serialno input{
        padding-top: 0;
        padding-bottom: 0;
        font-size: 1.8rem;
        font-family: 'OptimusPrinceps',Arial;
        text-align: center;
        max-width: 185px;
    }
    .btn-lookup{
        padding: 0 2rem;
    }
</style>