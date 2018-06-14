<template>
    <div class="tab-pane fade show" id="vehicle-specification" role="tabpanel" aria-labelledby="vehicle-specification-tab" v-show="isVisible">
            <h4 class="mb-4" v-show="showEngineTitle">{{ !showFuelType ? 'Vehicle' :  'Engine & Vehicle'}}</h4>
            <div class="form-row">
                <div class="col-md-6 col-lg-4 mb-4" v-if="showFuelType">
                    <label for="fuelType">Fuel type</label>
                    <select id="fuelType" class="form-control" v-model="engineInfo.fuelType">
                        <option value="">Select a Fuel Type...</option>
                        <option v-for="item in fuelTypes" v-bind:value="item.id">{{item.fuel_type}}</option>
                    </select>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showEmissions">
                    <label for="emissionClass">Emission Class</label>
                    <select id="emissionClass" class="form-control" v-model="engineInfo.emissions">
                        <option>Select the emission class...</option>
                        <option v-for="item in emissionClasses" v-bind:value="item.id">{{item.emission_class}}</option>
                    </select>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showPower">
                    <label for="power">Engine power</label>
                    <div class="input-group">
                        <input id="power" type="text" class="form-control" aria-label="Engine power" aria-describedby="basic-addon1" v-model="engineInfo.power" placeholder="Enter the engine power..">
                        <div class="input-group-append">
                            <select class="form-control" v-model="engineInfo.powerUnit">
                                <option value="hp">hp</option>
                                <option value="bhp">bhp</option>
                                <option value="kw">kw</option>
                                <option value="ps">ps</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showSpeed">
                    <label for="speed">Max Speed</label>
                    <div class="input-group">
                        <input id="speed" type="text" class="form-control" aria-label="Speed" aria-describedby="basic-speed" v-model="specification.speed" placeholder="Enter max speed..">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.speedUnit">
                                <option value="mph">mph</option>
                                <option value="kph">kph</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showCapacity">
                    <label for="capacity">Engine capacity</label>
                    <div class="input-group">
                        <input id="capacity" type="text" class="form-control" aria-label="Engine capacity" aria-describedby="basic-addon1" v-model="engineInfo.capacity" placeholder="Enter the engine capacity...">
                        <div class="input-group-append">
                            <select class="form-control" v-model="engineInfo.capacityUnit">
                                <option value="ltr">ltr</option>
                                <option value="cc">cc</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mb-4" v-if="showTransmission">
                    <label>Transmission</label>
                    <div class="form-control border-0">
                        <div class="form-check form-check-inline">
                            <input v-model="engineInfo.transmission" class="form-check-input" type="radio" id="transmission1" name="transmission" value="Manual">
                            <label class="form-check-label" for="transmission1">Manual</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input v-model="engineInfo.transmission" class="form-check-input" type="radio" id="transmission2"  name="transmission" value="Semi Automatic">
                            <label class="form-check-label" for="transmission2">Semi Automatic</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input v-model="engineInfo.transmission" class="form-check-input" type="radio" id="transmission3"  name="transmission" value="Automatic">
                            <label class="form-check-label" for="transmission3">Automatic</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showNumberOfSeats">
                    <label>Number of seats</label>
                    <autocomplete
                            :items="seats"
                            :loopKey="'id'"
                            :loopVal="'seat'"
                            :initialKey="'id'"
                            :initialVal="specification.numberOfSeats"
                            :input.sync="specification.numberOfSeats"
                            :required="false">
                    </autocomplete>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showColour">
                    <label>Colour</label>
                    <autocomplete
                            :items="colours"
                            :loopKey="'id'"
                            :loopVal="'colour'"
                            :initialKey="'id'"
                            :initialVal="specification.colour"
                            :input.sync="specification.colour"
                            :required="false">
                    </autocomplete>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showNumberOfDoors">
                    <label for="numberOfDoors">Number of doors</label>
                    <select id="numberOfDoors" class="form-control" v-model="specification.numberOfDoors">
                        <option value=""></option>
                        <option v-for="i in 7" :value="i">{{i}}</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 col-lg-4 mb-4" v-if="showWheelbase">
                    <label for="wheelbase">Wheelbase</label>
                    <div class="input-group">
                        <input id="wheelbase" type="text" class="form-control" aria-label="wheelbase" aria-describedby="basic-wheelbase" v-model="specification.wheelbase" placeholder="Enter the wheelbase..">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.wheelbaseUnit">
                                <option value="cm">cm</option>
                                <option value="yd">yd</option>
                                <option value="ft">ft</option>
                                <option value="in">in</option>
                                <option value="mm">mm</option>
                                <option value="m">m</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showTrailerAxis">
                    <label for="trailerAxis">Axles</label>
                    <select id="trailerAxis" class="form-control" v-model="specification.trailerAxis">
                        <option></option>
                        <option v-for="i in 10" :value="i">{{i}}</option>
                    </select>
                </div>
            </div>
            <h4 class="my-4" v-if="showCapacityTitle">Capacity</h4>
            <div class="form-row">
                <div class="col-md-6 col-lg-4 mb-4" v-if="showMaxPayload">
                    <label for="maxPayload">Max Payload</label>
                    <div class="input-group">
                        <input id="maxPayload" type="text" class="form-control" v-model="specification.maxPayload" aria-label="Enter max playload" placeholder="Enter max payload..." aria-describedby="basic-maxpayload">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.payloadUnit">
                                <option value="Kg">kg</option>
                                <option value="Ton">Ton</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showGrossWeight">
                    <label for="grossWeight">Gross Vehicle Weight</label>
                    <div class="input-group">
                        <input id="grossWeight" type="text" class="form-control" v-model="specification.grossWeight" aria-label="Enter the gross vehicle weight" placeholder="Enter the gross vehicle weight..." aria-describedby="basic-addon1">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.weightUnit">
                                <option value="Kg">Kg</option>
                                <option value="Ton">Ton</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showGrossTrailingWeight">
                    <label for="trailerWeightUnit">Gross Trailing Weight</label>
                    <div class="input-group">
                        <input id="trailerWeightUnit" type="text" class="form-control" v-model="specification.trailerWeight" aria-label="Enter trailing weight" placeholder="Enter trailing weight..." aria-describedby="basic-trailerWeight">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.trailerWeightUnit">
                                <option value="Kg">Kg</option>
                                <option value="Ton">Ton</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showVolume">
                    <label for="volumecapacity">Volume / Capacity</label>
                    <div class="input-group">
                        <input id="volumecapacity" type="text" class="form-control"  aria-label="Gross vehicle weight" aria-describedby="basic-addon1" placeholder="Enter the volume..." v-model="specification.volume">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.volumeUnit">
                                <option value="m3">m3</option>
                                <option value="cm3">cm3</option>
                                <option value="mm3">mm3</option>
                                <option value="ltr">ltr</option>
                                <option value="gal">gal</option>
                                <option value="yd3">yd3</option>
                                <option value="ht3">ft3</option>
                                <option value="in3">in3</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row" v-if="showInternalLength">
                <div class="col-md-6 col-lg-4 mb-4">
                    <label for="internalLength">Internal length</label>
                    <div class="input-group">
                        <input id="internalLength" type="text" class="form-control"  aria-label="Gross vehicle weight" aria-describedby="basic-addon1" placeholder='Enter the internal length' v-model="specification.internalLength">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.internalUnit">
                                <option value="cm">cm</option>
                                <option value="yd">yd</option>
                                <option value="ft">ft</option>
                                <option value="in">in</option>
                                <option value="mm">mm</option>
                                <option value="m">m</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showInternalWidth">
                    <label for="internalWidth">Internal width</label>
                    <div class="input-group">
                        <input id="internalWidth" type="text" class="form-control"  aria-label="Gross vehicle weight" aria-describedby="basic-addon1" placeholder="Enter the internal width..." v-model="specification.internalWidth">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.internalUnit">
                                <option value="cm">cm</option>
                                <option value="yd">yd</option>
                                <option value="ft">ft</option>
                                <option value="in">in</option>
                                <option value="mm">mm</option>
                                <option value="m">m</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showInternalHeight">
                    <label for="internalHeight">Internal height</label>
                    <div class="input-group">
                        <input id="internalHeight" type="text" class="form-control"  aria-label="Gross vehicle weight" aria-describedby="basic-addon1" placeholder="Enter the internal height..." v-model="specification.internalHeight">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.internalUnit">
                                <option value="cm">cm</option>
                                <option value="yd">yd</option>
                                <option value="ft">ft</option>
                                <option value="in">in</option>
                                <option value="mm">mm</option>
                                <option value="m">m</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row" v-if="showExternalHeight">
                <div class="col-md-6 col-lg-4 mb-4">
                    <label for="externalLength">External length</label>
                    <div class="input-group">
                        <input id="externalLength" type="text" class="form-control"  aria-label="Gross vehicle weight" aria-describedby="basic-addon1" placeholder="Enter the external length..." v-model="specification.externalLength">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.externalUnit">
                                <option value="cm">cm</option>
                                <option value="yd">yd</option>
                                <option value="ft">ft</option>
                                <option value="in">in</option>
                                <option value="mm">mm</option>
                                <option value="m">m</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showExternalWidth">
                    <label for="externalWidth">External width</label>
                    <div class="input-group">
                        <input id="externalWidth" type="text" class="form-control"  aria-label="Gross vehicle weight" aria-describedby="basic-addon1" placeholder="Enter the external width..." v-model="specification.externalWidth">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.externalUnit">
                                <option value="cm">cm</option>
                                <option value="yd">yd</option>
                                <option value="ft">ft</option>
                                <option value="in">in</option>
                                <option value="mm">mm</option>
                                <option value="m">m</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showExternalHeight">
                    <label for="volume-capacity">External height</label>
                    <div class="input-group">
                        <input id="volume-capacity" type="text" class="form-control"  aria-label="Gross vehicle weight" aria-describedby="basic-addon1" placeholder="Enter the external height..." v-model="specification.externalHeight">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.externalUnit">
                                <option value="cm">cm</option>
                                <option value="yd">yd</option>
                                <option value="ft">ft</option>
                                <option value="in">in</option>
                                <option value="mm">mm</option>
                                <option value="m">m</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <h4 class="my-4" v-if="showDimensionTitle">Operating Dimensions</h4>
            <div class="form-row" v-if="showDimensionTitle">
                <div class="col-md-6 col-lg-4 mb-4" v-if="showLiftingCapacity">
                    <label for="lifting-capacity">Lifting Capacity</label>
                    <div class="input-group">
                        <input id="lifting-capacity" type="text" class="form-control"  aria-label="Lifting capacity" aria-describedby="basic-liftingcapacity" placeholder="Enter the lifting capacity..." v-model="specification.liftingCapacity">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.liftingCapacityUnit">
                                <option value="Kg">Kg</option>
                                <option value="Ton">Ton</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showMaximumHeight">
                    <label for="maximumHeight">Max Height</label>
                    <div class="input-group">
                        <input id="maximumHeight" type="text" class="form-control"  aria-label="Max Height" aria-describedby="basic-maximumHeight" placeholder="Enter the Maximum Height..." v-model="specification.maximumHeight">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.maximumHeightUnit">
                                <option value="cm">cm</option>
                                <option value="yd">yd</option>
                                <option value="ft">ft</option>
                                <option value="in">in</option>
                                <option value="mm">mm</option>
                                <option value="m">m</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showMaximumReach">
                    <label for="maximumReach">Max Reach</label>
                    <div class="input-group">
                        <input id="maximumReach" type="text" class="form-control"  aria-label="Max Reach" aria-describedby="basic-maximumReach" placeholder="Enter the Maximum Reach..." v-model="specification.maximumReach">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.maximumReachUnit">
                                <option value="cm">cm</option>
                                <option value="yd">yd</option>
                                <option value="ft">ft</option>
                                <option value="in">in</option>
                                <option value="mm">mm</option>
                                <option value="m">m</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showOperatingWidth">
                    <label for="operatingWidth">Operating Width</label>
                    <div class="input-group">
                        <input id="operatingWidth" type="text" class="form-control"  aria-label="Operating Width" aria-describedby="basic-operatingWidth" placeholder="Enter the Operating Width..." v-model="specification.operatingWidth">
                        <div class="input-group-append">
                            <select class="form-control" v-model="specification.operatingWidthUnit">
                                <option value="cm">cm</option>
                                <option value="yd">yd</option>
                                <option value="ft">ft</option>
                                <option value="in">in</option>
                                <option value="mm">mm</option>
                                <option value="m">m</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-4" v-if="showOperatingType">
                    <label for="operatingType">Operating Type</label>
                    <div class="input-group">
                        <select  id="operatingType" class="form-control" v-model="specification.operatingType">
                            <option>Choose the operating type...</option>
                            <option value="Handheld">Handheld</option>
                            <option value="Ride In">Ride In</option>
                            <option value="Ride On">Ride On</option>
                        </select>
                    </div>
                </div>
            </div>
    </div>
</template>

<script>
    export default {
        name: "vehicle-specifications",
        props: ['type','category','fuelTypes','emissionClasses','colours','vengineInfo','vspecification','isVisible'],

        data: function () {
            return {
                engineInfo: this.vengineInfo,
                specification: this.vspecification,
                showEngineTitle: true,
                showFuelType: true,
                showEmissions: false,
                showPower: false,
                showCapacity: false,
                showCapacityTitle: false,
                showTransmission: false,
                showNumberOfSeats: false,
                showColour: false,
                showMaxPayload: false,
                showGrossWeight: false,
                showVolume: false,
                showNumberOfDoors: false,
                showInternalLength: false,
                showInternalWidth: false,
                showInternalHeight: false,
                showExternalLength: false,
                showExternalWidth: false,
                showExternalHeight: false,
                showDimensionTitle: false,
                showLiftingCapacity: false,
                showMaximumHeight: false,
                showMaximumReach:false,
                showOperatingWidth: false,
                showOperatingType: false,
                showSpeed: false,
                showWheelbase: false,
                showAxleDrive: true,
                showTrailerAxis: false,
                showGrossTrailingWeight: false,
            }
        },
        watch: {
            category: function (newValue) {
                this.setShowAttribs(this.type.type,newValue);
            }
        },
        computed:{
            seats: function () {
                let seats = [];
                for (let i = 1; i  <= 100; i++) {
                    seats.push({'id': i,'seat': ''+i+''})
                }
                return seats;
            }
        },
        mounted: function () {
            this.setShowAttribs(this.type.type,this.category);
        },
        updated: function(){
            //this.$emit('update:specifications', this.specification);
            //this.$emit('update:engineInfo', this.engineInfo);
        },
        methods:{
            setShowAttribs: function (type,category) {
                switch (true){
                    case (category == 'Rigid Trucks'):
                        this.showEngineTitle = true;
                        this.showFuelType = true;
                        this.showEmissions = true;
                        this.showPower = true;
                        this.showCapacity = true;
                        this.showTransmission = true;
                        this.showNumberOfSeats = true;
                        this.showColour = true;
                        this.showGrossWeight = true;
                        this.showVolume = true;
                        this.showInternalLength = true;
                        this.showInternalWidth = true;
                        this.showInternalHeight = true;
                        this.showExternalLength = true;
                        this.showExternalWidth = true;
                        this.showExternalHeight = true;
                        break;
                    case (category == 'Tractor Units'):
                        this.showEngineTitle = true;
                        this.showFuelType = true;
                        this.showEmissions = true;
                        this.showPower = true;
                        this.showCapacity = true;
                        this.showTransmission = true;
                        this.showNumberOfSeats = true;
                        this.showColour = true;
                        this.showGrossWeight = true;
                        break;
                    case (category == 'Trailers'):
                        this.showEngineTitle = true;
                        this.showFuelType = false;
                        this.showEmissions = false;
                        this.showPower = false;
                        this.showCapacity = false;
                        this.showTransmission = false;
                        this.showNumberOfSeats = false;
                        this.showColour = true;
                        this.showMaxPayload = false;
                        this.showGrossWeight = true;
                        this.showVolume = true;
                        this.showInternalLength = true;
                        this.showInternalWidth = true;
                        this.showInternalHeight = true;
                        this.showExternalLength = true;
                        this.showExternalWidth = true;
                        this.showExternalHeight = true;
                        this.showTrailerAxis = true;
                        break;
                    case (type == 'Plant'):
                        this.showFuelType = true;
                        this.showEngineTitle = true;
                        this.showEmissions = false;
                        this.showSpeed = true;
                        this.showPower = true;
                        this.showCapacityTitle = true;
                        this.showCapacity = true;
                        this.showTransmission = false;
                        this.showNumberOfSeats = false;
                        this.showColour = true;
                        this.showMaxPayload = true;
                        this.showGrossWeight = true;
                        this.showVolume = true;
                        this.showGrossTrailingWeight = true;
                        this.showInternalLength = true;
                        this.showInternalWidth = true;
                        this.showInternalHeight = true;
                        this.showExternalLength = true;
                        this.showExternalWidth = true;
                        this.showExternalHeight = true;
                        this.showDimensionTitle = true;
                        this.showLiftingCapacity = true;
                        this.showMaximumHeight = true;
                        this.showMaximumReach = true;
                        this.showOperatingWidth = true;
                        this.showOperatingType = true;
                        this.showWheelbase = true;
                        this.showAxleDrive = true;
                        this.showTrailerAxis = true;
                        break;
                    case (type == 'Farm'):
                        this.showFuelType = true;
                        this.showEngineTitle = true;
                        this.showSpeed = true;
                        this.showPower = true;
                        this.showCapacityTitle = true;
                        this.showColour = true;
                        this.showMaxPayload = true;
                        this.showGrossWeight = true;
                        this.showVolume = true;
                        this.showGrossTrailingWeight = true;
                        this.showInternalLength = true;
                        this.showInternalWidth = true;
                        this.showInternalHeight = true;
                        this.showExternalLength = true;
                        this.showExternalWidth = true;
                        this.showExternalHeight = true;
                        this.showDimensionTitle = true;
                        this.showMaximumReach = true;
                        this.showOperatingWidth = true;
                        this.showOperatingType = true;
                        this.showWheelbase = true;
                        this.showAxleDrive = true;
                        this.showTrailerAxis = true;
                        break;
                    case (type == 'Car'):
                        this.showFuelType = true;
                        this.showEngineTitle = true;
                        this.showEmissions = true;
                        this.showCapacity = true;
                        this.showTransmission = true;
                        this.showNumberOfSeats = false;
                        this.showColour = true;
                        this.showNumberOfDoors = true;
                        break;
                    case (type == 'Van'):
                        this.showFuelType = true;
                        this.showEngineTitle = true;
                        this.showEmissions = true;
                        this.showPower = true;
                        this.showCapacity = true;
                        this.showTransmission = true;
                        this.showNumberOfSeats = false;
                        this.showColour = true
                        this.showNumberOfSeats = true;
                        this.showNumberOfDoors = true;
                        this.showCapacityTitle = true;
                        this.showCapacity = true;
                        this.showMaxPayload = true;
                        this.showGrossWeight = true;
                        this.showVolume = true;
                        this.showGrossTrailingWeight = true;
                        this.showInternalLength = true;
                        this.showInternalWidth = true;
                        this.showInternalHeight = true;
                        this.showExternalLength = true;
                        this.showExternalWidth = true;
                        this.showExternalHeight = true;
                        break;
                }
            },
        }
    }
</script>

<style scoped>

</style>