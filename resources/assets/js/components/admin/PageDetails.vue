<template>
    <div class="d-sm-flex mb-3">
        <div class="mr-auto p-2">
            <div class="d-md-flex">
                <div class="flex-row">
                    <div class="form-vehicle-image">
                        <img :src="pageDetails.image ? '/storage/small/'+pageDetails.image.image : '/images/no-vehicle-image.gif'">
                    </div>
                </div>
                <div class="flex-row">
                    <h3 class="mb-1">{{pageDetails.type}} {{pageDetails.category ? '(' + pageDetails.category + ')' : ''}}</h3>
                    <div>
                        {{pageDetails.make ? pageDetails.make : 'Make' | uppercase}},
                        {{pageDetails.model ? pageDetails.model : 'Model' | uppercase}},
                        {{pageDetails.derivative ? pageDetails.derivative : 'Derivative' | uppercase}}</div>
                    <div>Reg: {{pageDetails.reg ? pageDetails.reg : 'Unknown'}}</div>
                </div>
            </div>
        </div>
        <div class="p-2 text-sm-right">
            <h2>{{ pageDetails.salePriceCurrency == 'EUR' ? '€' : '£'}} {{ salePrice}}</h2>
            <div>{{ pageDetails.mileage ? pageDetails.mileage : 'Mileage' }} {{ pageDetails.mileage ? pageDetails.mileageUnit : '' }}</div>
            <div>last updated: {{ pageDetails.updatedAt ? pageDetails.updatedAt : 'Never'}}</div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "page-details",
        props: ['pageDetails'],
        computed: {
            salePrice: function () {
                let price = parseFloat(this.pageDetails.salePrice);
                if(!price)
                    return 'Price';
                return price.toFixed(0).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
            }
        }
    }
</script>

<style scoped>

</style>