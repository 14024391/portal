<template>
    <div>
        <pageDetails-component :pageDetails="pageDetails"></pageDetails-component>
        <form id="vehicle-form" class="needs-validation" novalidate v-on:submit.prevent="onSubmit">
            <div class="d-sm-flex pb-2">
                <tabLinks-component></tabLinks-component>
                <div class="tab-content" id="v-pills-tabContent">
                    <vehicle-details
                            :type="type"
                            :vehicledata="vehicledata"
                            :pvehicle="vehicle.details"
                            :vehicleDetails.sync="vehicleDetails"
                            :showSpecifications.sync="tabs.specification"
                            :vehicleData.sync="vehicledata"
                            @updateFromLookup="updateFromLookup"
                            :loading.sync="loading"
                    ></vehicle-details>
                    <vehicle-specification
                            :type="type"
                            :isVisible="tabs.specification"
                            :fuelTypes="vehicledata.fuelTypes"
                            :emissionClasses="vehicledata.emissionClasses"
                            :category="pageDetails.category"
                            :colours="vehicledata.colours"
                            :vengineInfo="vehicle.engineInfo"
                            :vspecification="vehicle.specifications"
                            :specifications.sync="vehicleSpecifications"
                            :engineInfo.sync="vehicleEngineInfo"
                    ></vehicle-specification>
                    <vehicle-images
                        :vehicle-id="vehicle.details.vehicle_id"
                        :vimages="vehicle.images"
                        :videoLinks="vehicle.videoLinks"
                        :default.sync="defaultImage"
                        :loading.sync="loading"
                    ></vehicle-images>
                    <vehicle-history
                        :type="type.type"
                        :vcondition="vehicle.condition"
                        :vhistory="vehicle.history"
                        :condition.sync="vehicleCondition"
                        :history.sync="vehicleHistory"
                    ></vehicle-history>
                    <vehicle-features
                        :vdescription="vehicle.description"
                        :vreferenceNumber="vehicle.referenceNumber"
                        :allFeatures="vehicledata.features"
                        :vfeatures="vehicle.features"
                        :description.sync="vehicleDescription"
                        :referenceNumber.sync="vehicleReferenceNumber"
                        :feature.sync="vehicleFeatures"
                    ></vehicle-features>
                    <vehicle-visibility
                        :vSoldStatus="vehicle.soldStatus"
                        :vAutoTraderWebYes="vehicle.autoTraderWebYes"
                        :vMyWebYes="vehicle.myWebYes"
                        :vFeatured="vehicle.featured"
                        :vlatest="vehicle.latest"
                        :soldStatus.sync="soldStatus"
                        :autoTraderWebYes.sync="autoTraderWebYes"
                        :myWebYes.sync="myWebYes"
                        :featured.sync="featured"
                        :latest.sync="latest"
                    >
                    </vehicle-visibility>
                    <div id="loading-screen" class="d-flex align-items-center justify-content-center" v-if="loading">
                        <div>
                            <i class="fas fa-cog fa-5x fa-spin" style="color: #267ec8;"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 pt-2">
                <div class="d-flex">
                    <div class="ml-auto">
                        <a id="delete-vehicle-btn" class="btn btn-danger text-white" data-toggle="modal"  data-target="#deleteModal">Delete</a>
                        <button type="submit" class="btn btn-primary">SAVE</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>
<script>
    export default {
        props: ['type','pvehicle','pvehicledata'],

        data: function () {
            let image = this.pvehicle.images.filter(r => r.default == 1)[0];
            return {
                tabs: {
                    details: false,
                    specification: false,
                    images: false,
                    condition: false,
                    description: false,
                    advertising: false
                },
                vehicledata: this.pvehicledata,
                action: this.pvehicle.action,
                loading: false,
                formUrl: '/admin/vehicle/store',
                formAction: 'store',
                vehicle: this.pvehicle,
                vehicleDetails: this.pvehicle.details,
                vehicleSpecifications: this.pvehicle.specifications,
                vehicleEngineInfo: this.pvehicle.engineInfo,
                vehicleCondition: this.pvehicle.condition,
                vehicleHistory: this.pvehicle.history,
                vehicleDescription:'',
                vehicleReferenceNumber: this.pvehicle.referenceNumber,
                defaultImage: image,
                soldStatus: this.pvehicle.soldStatus,
                autoTraderWebYes: this.pvehicle.autoTraderWebYes,
                myWebYes: this.pvehicle.myWebYes,
                vehicleFeatures: this.pvehicle.features,
                featured: this.pvehicle.featured,
                latest: this.pvehicle.latest,
            }
        },
        mounted: function(){
            this.$emit('update:loading', false)
        },
        computed: {
            pageDetails: function () {
                let category = this.vehicledata.categories.filter( r => r.id == this.vehicleDetails.category)[0];
                let make = this.vehicledata.makes.filter( r => r.id == this.vehicleDetails.make)[0];
                let model = this.vehicledata.models.filter( r => r.id == this.vehicleDetails.model)[0];
                let image = this.defaultImage;
                let reg = this.vehicleDetails.serialNumber ? this.vehicleDetails.serialNumber: this.vehicleReferenceNumber;

                return {
                    type: this.type.type,
                    category: category ? category.category : '',
                    make: make ? make.make : '',
                    model: model ? model.model : '',
                    derivative: this.vehicleDetails.derivative,
                    salePrice: this.vehicleDetails.salePrice,
                    salePriceCurrency: this.vehicleDetails.salePriceCurrency,
                    mileage: this.vehicleDetails.mileage,
                    mileageUnit: this.vehicleDetails.mileageUnit,
                    reg: reg,
                    updatedAt: this.vehicleDetails.updated_at,
                    image: image
                }
            }
        },
        methods: {
            onSubmit: function (e) {
                this.loading = true;
                if(e.target.checkValidity() === false){
                    this.$toasted.error('Please fill all the required Fields');
                    this.loading = false;
                    e.target.classList.add('was-validated');
                } else {
                    axios.post(this.formUrl,{
                            action: this.action,
                            details: this.vehicleDetails,
                            engineInfo: this.vehicleEngineInfo,
                            specifications: this.vehicleSpecifications,
                            condition: this.vehicleCondition,
                            history: this.vehicleHistory,
                            description: this.vehicleDescription,
                            referenceNumber: this.vehicleReferenceNumber,
                            soldStatus: this.soldStatus,
                            autoTraderWebYes: this.autoTraderWebYes,
                            myWebYes: this.myWebYes,
                            features: this.vehicleFeatures,
                            featured: this.featured,
                            latest: this.latest
                    })
                        .then( (response) => {
                            if(response.data.error){
                                this.$toasted.error(response.data.errorMessage)
                                this.loading = false;
                            }
                            else {
                                if(this.action === 'STORE'){
                                    history.pushState({
                                        id: 'vehicle'
                                    }, 'vehicle | AM Commercials', response.data.url);
                                    this.action = 'UPDATE';
                                    location.reload();
                                }
                                this.vehicle = response.data.vehicle;
                                this.vehicleDetails = response.data.vehicle.details;
                                this.$toasted.success('Updated Successfully');
                                $('#vehicle-form').removeClass('was-validated');
                            }
                            this.loading = false;
                        })
                        .catch((error) => {
                            this.loading = false;
                            this.$toasted.error(error);
                        });
                }
            },
            /** update from Lookup */
            updateFromLookup: function (data) {
                let type = this.type.type;
                let reg = data.VehicleRegistration;
                let dimension = data.TechnicalDetails.Dimensions;
                let smmtDetails = data.SmmtDetails;
                let make = this.vehicledata.makes.find(r => r.make === reg.Make);
                let model = this.vehicledata.models.find( r => r.make_id === make.id && r.model === reg.Model)

                /** Update Vehicle Information */
                this.vehicle.details.make = make.id;
                this.vehicle.details.model = model.id;
                this.vehicle.details.derivative = smmtDetails.ModelVariant;
                this.vehicle.details.manufacturerYear = reg.YearOfManufacture;
                this.vehicle.details.registrationYear = reg.YearMonthFirstRegistered.slice(0,4);
                this.vehicle.details.registrationPlate = reg.Vrm.slice(2,4);
                let re1 = new RegExp('^'+smmtDetails.BodyStyle, 'i');
                let bodyType = this.vehicledata.bodyTypes.find(r => r.body_type.toLowerCase().match(re1));
                this.vehicle.details.bodyType = bodyType ? bodyType.body_type : null;
                this.vehicle.details.axleDrive = smmtDetails.DriveType.toLowerCase();
                let re2 = new RegExp('^'+smmtDetails.CabType, 'i');
                let cabType = this.vehicledata.cabTypes.find(r => r.cab_type.toLowerCase().match(re2));
                this.vehicle.details.cabType = cabType ? cabType.cab_type : null;
                this.vehicle.details.wheelbaseType = dimension.WheelBase;

                /** Update EngineInfo **/
                let fuelType = this.vehicledata.fuelTypes.find( r => r.fuel_type.toLowerCase() === smmtDetails.FuelType.toLowerCase())
                this.vehicle.engineInfo.fuelType = fuelType ? fuelType.id : null;
                this.vehicle.engineInfo.emissions = data.TechnicalDetails.General.EuroStatus;
                this.vehicle.engineInfo.power = data.TechnicalDetails.Performance.Power.Bhp;
                this.vehicle.engineInfo.powerUnit = 'bhp';
                this.vehicle.engineInfo.capacity = smmtDetails.EngineCapacity;
                this.vehicle.engineInfo.capacityUnit = 'cc';
                let transmission = smmtDetails.Transmission.toLowerCase();
                this.vehicle.engineInfo.transmission = transmission.charAt(0).toUpperCase() + transmission.slice(1);

                /** Update Specifications **/
                this.vehicle.specifications.numberOfSeats = reg.SeatingCapacity;
                let colour = this.vehicledata.colours.find( r => r.colour.toLowerCase() === reg.Colour.toLowerCase());
                this.vehicle.specifications.colour = colour ? colour.id : null;
                this.vehicle.specifications.numberOfDoors =  smmtDetails.NumberOfDoors;
                this.vehicle.specifications.numberOfSeats =  dimension.NumberOfSeats;
                this.vehicle.specifications.grossWeight =  dimension.GrossVehicleWeight ? dimension.GrossVehicleWeight :  dimension.GrossCombinedWeight;
                // specifications.weightUnit = 'kg';
                // specifications.volume = '';
                // specifications.volumeUnit = 'm3';
                // specifications.internalHeight = '';
                // specifications.internalWidth = '';
                // specifications.internalLength = '';
                // specifications.internalUnit = 'm';
                this.vehicle.specifications.externalWidth = dimension.Width;
                this.vehicle.specifications.externalHeight = dimension.Height;
                this.vehicle.specifications.externalLength = type == 'Car' ?  dimension.CarLength : '';
                //this.vehicle.specifications.externalUnit = 'm';

                /** Update VehicleHistory */
                this.vehicle.history.previousOwner = data.VehicleHistory.NumberOfPreviousKeepers;

                this.vehicleEngineInfo = this.vehicle.engineInfo;
                this.vehicleSpecifications = this.vehicle.specifications;
                this.vehicleHistory = this.vehicle.history;

            },
        },
    }
</script>

<style scoped>
    #v-pills-tabContent{
        position: relative;
    }
    #loading-screen{
        opacity: 0.8;
        background:#edf1f5;
        position: absolute;
        top: 0;
        width: 100%;
        height: 115%;
        margin-left: -8px;
        z-index: 999;
    }
    #delete-vehicle-btn{
        margin-right: 0.8rem;
    }
</style>