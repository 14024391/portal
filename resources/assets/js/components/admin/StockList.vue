<template>
    <div>
        <div id="stock-filters" class="d-print-none">
            <div class="container">
                <div>
                    <div class="row">
                        <div class="col col-md-12 mb-2">
                            <label class="filter-label">Channel</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="righttruck" value="Rigid Trucks" v-model="filterCategory">
                                <label class="form-check-label" for="righttruck">Rigid Truck</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="tractorunit" value="Tractor Units" v-model="filterCategory">
                                <label class="form-check-label" for="tractorunit">Tractor Units</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="trailers" value="Trailers" v-model="filterCategory">
                                <label class="form-check-label" for="trailers">Trailers</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="plant" value="Plant" v-model="filterType">
                                <label class="form-check-label" for="plant">Plant</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="farm" value="Farm" v-model="filterType">
                                <label class="form-check-label" for="farm">Farm</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="car" value="Car" v-model="filterType">
                                <label class="form-check-label" for="car">Car</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="van" value="Van" v-model="filterType">
                                <label class="form-check-label" for="van">Van</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <label class="filter-label">Stock Status</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="instock" value="In Stock" v-model="filterStatus">
                                <label class="form-check-label" for="instock">In Stock</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="sold" value="Sold" v-model="filterStatus">
                                <label class="form-check-label" for="sold">Sold</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="duein" value="Due In" v-model="filterStatus">
                                <label class="form-check-label" for="duein">Due In</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="Reserved" value="Reserved" v-model="filterStatus">
                                <label class="form-check-label" for="Reserved">Reserved</label>
                            </div>
                        </div>
                        <div id="filter-search" class="col-12 col-md-4 mr-auto">
                            <input type="text" class="form-control" @input="debounceFilterInput" placeholder="Search for your stock..."/>
                            <i class="fas fa-search search-icon"></i>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <label class="filter-label">Advert Status</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="advertised" :value="1" v-model="filterAdvertised">
                                <label class="form-check-label" for="advertised">Advertised</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="notadvertised" :value="0" v-model="filterAdvertised">
                                <label class="form-check-label" for="notadvertised">Not Advertised</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="myWebYes" :value="1" v-model="myWebYes">
                                <label class="form-check-label" for="myWebYes">Website</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="featured" :value="1" v-model="featured">
                                <label class="form-check-label" for="featured">Featured</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="latest" :value="1" v-model="latest">
                                <label class="form-check-label" for="latest">Latest</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container my-4">
            <div class="d-none d-print-block">
                <div class="d-flex">
                    <div class="p-2 h5">A & M Commercials Ltd Stock List</div>
                    <div class="ml-auto p-2">
                        <img src="/images/logo.png" alt="A&M Commercial Logo" style="max-height:50px;">
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="h3 text-primary">Stock Overview</div>
                        <div class="ml-auto">Showing <span class="text-primary">{{startIndex + 1}}</span> to <span class="text-primary">{{filteredData.length < endIndex ? filteredData.length : endIndex}}</span> out of <span class="text-primary">{{filteredData.length}}</span></div>
                    </div>
                    <div class="d-lg-flex stock-stats flex-row">
                        <div class="pb-2">
                            <a onclick="window.print();return false;" style="cursor: pointer" class="d-print-none"><i class="fas fa-print text-primary"></i> Print Stocklist</a>
                            <span class="text-primary">{{stats.trucks}}</span> Trucks
                            <span class="text-primary">{{stats.plants}}</span> Plant
                            <span class="text-primary">{{stats.farms}}</span> Farm
                            <span class="text-primary">{{stats.cars}}</span> Cars
                            <span class="text-primary">{{stats.vans}}</span> Vans
                        </div>
                        <div class="ml-auto pb-2 d-print-none">

                            <div class="d-flex align-items-center">
                                <div>Show</div>
                                <select v-model="show" class="form-control form-control-sm mx-2">
                                    <option :value="50">50</option>
                                    <option :value="100">100</option>
                                    <option :value="200">200</option>
                                    <option :value="400">400</option>
                                    <option :value="1000">ALL</option>
                                </select>
                                <input type="hidden" v-model="showMassAction"/>
                                <button type="button" class="btn btn-primary btn-sm" @click="showMassAction = !showMassAction" v-if="!showMassAction">Mass Action</button>
                                <span class="px-2" style="width:120px" v-if="showMassAction">With Selected</span>
                                <select class="form-control form-control-sm px-2" style="max-width: 150px;" v-if="showMassAction" v-model="massAction" v-on:change="confirmMassAction">
                                    <option v-for="(act,index) in actions" :value="index">{{act}}</option>
                                </select>
                                <span class="px-2 clear-mass-action" v-if="showMassAction" @click="showMassAction = false">
                                    <i class="fas fa-times text-danger"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <table id="stocklist" class="table table-sm table-striped table-bordered table-responsive-lg">
                        <thead class="text-white bg-primary">
                        <tr>
                            <th scope="col" class="text-center" style="width: 100px;"><i class="fas fa-camera-retro" style="font-size: 1rem;"></i></th>
                            <th style="min-width: 90px" scope="col" @click="sortBy('type')">Channel <span v-html="sortIcon('type')"></span></th>
                            <th style="min-width: 100px" scope="col" @click="sortBy('category')">Category <span v-html="sortIcon('category')"></span></th>
                            <th scope="col" @click="sortBy('serialNumber')">Reg/ ID <span v-html="sortIcon('serialNumber')"></span></th>
                            <th style="min-width: 60px" scope="col" @click="sortBy('registrationYear')">Year <span v-html="sortIcon('registrationYear')"></span></th>
                            <th scope="col" @click="sortBy('make')" style="width: 320px">Vehicle details <span v-html="sortIcon('make')"></span></th>
                            <th scope="col" @click="sortBy('saleprice')">Price <span v-html="sortIcon('saleprice')"></span></th>
                            <th style="min-width: 105px" scope="col" @click="sortBy('updated_at')">Created <span v-html="sortIcon('updated_at')"></span></th>
                            <th style="min-width: 80px" scope="col" @click="sortBy('soldStatus')">Status <span v-html="sortIcon('soldStatus')"></span></th>
                            <th scope="col" class="d-print-none"><i class="fas fa-cog"></i></th>
                            <th style="min-width: 46px" scope="col" class="d-print-none">
                                <img src="/images/autotrader-icon.png" style="height: 16px;padding-bottom: 5px;"/>
                                <i class="fas fa-tv"></i>
                            </th>
                            <th v-if="showMassAction" class="d-print-none"></th>
                        </tr>
                        </thead>
                        <tbody :class="{'mass-action':showMassAction}">
                        <tr class="vehicle-link" v-for="(vehicle,index) in filteredData" :class="{'vehicle-selected': selected.indexOf(vehicle.id) != -1}" v-if="index >= startIndex && index < endIndex">
                            <td @click="openLink(vehicle.id)">
                                <div class="form-vehicle-image">
                                    <img :src="vehicle.image">
                                </div>
                            <td @click="openLink(vehicle.id)">
                                <span class="d-print-none"><i :class="'vehicle-icon icon-'+vehicle.type"></i></span>
                                <span class="d-none d-print-block">{{vehicle.type}}</span>
                            </td>
                            <td @click="openLink(vehicle.id)">{{vehicle.category}}</td>
                            <td @click="openLink(vehicle.id)">{{vehicle.serialNumber ? vehicle.serialNumber: vehicle.referenceNumber}}</td>
                            <td @click="openLink(vehicle.id)">{{vehicle.registrationYear}}</td>
                            <td @click="openLink(vehicle.id)">{{vehicle.make}}, {{vehicle.model}} {{vehicle.derivative ? ',' : ''}} {{vehicle.derivative}}</td>
                            <td @click="openLink(vehicle.id)" class="text-right"><span class="is-poa">{{vehicle.poa ? '*': ''}}</span>£{{vehicle.saleprice}}</td>
                            <td @click="openLink(vehicle.id)" class="text-right">{{vehicle.created_date}}</td>
                            <td @click="openLink(vehicle.id)">{{vehicle.soldStatus}}</td>
                            <td class="d-print-none">
                                <div class="dropdown text-center settings">
                                    <button :id="'settings'+vehicle.id" class="btn btn-link" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <span><i class="fas fa-cog"></i></span>
                                    </button>
                                    <div class="dropdown-menu" :aria-labelledby="'settings'+vehicle.id">
                                        <a class="dropdown-item" @click.prevent="setSold(vehicle.id)"><i class="fas fa-gavel"></i> <span>Mark As {{vehicle.soldStatus == 'Sold' ? 'In Stock' : 'Sold'}}</span></a>
                                        <a v-if="vehicle.soldStatus !== 'Reserved'" class="dropdown-item" @click.prevent="setReserved(vehicle.id)"><i class="fas fa-gavel"></i> <span>Mark As Reserved</span></a>
                                        <a class="dropdown-item" @click.prevent="setPOA(vehicle.id)"><i class="fas fa-gavel"></i> <span>{{vehicle.poa ? 'Remove POA' : 'Set as POA'}}</span></a>
                                        <a class="dropdown-item" @click.prevent="setFeatured(vehicle.id)"><i class="far fa-star"></i> <span>{{vehicle.featured ? 'Remove': 'Set'}} Featured</span></a>
                                        <a class="dropdown-item" @click.prevent="setLatest(vehicle.id)"><i class="fas fa-clock"></i> <span>{{vehicle.latest ? 'Remove': 'Set'}} Latest</span></a>
                                        <a class="dropdown-item" data-toggle="modal" data-target="#deleteModal" @click="setDelete(vehicle.id)"><i class="far fa-trash-alt"></i> <span>Delete</span></a>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center d-print-none">
                                <span @click="setAutoTraderWebYes(vehicle.id)">
                                    <span v-show="vehicle.autoTraderWebYes"><i class="fas fa-circle fa-xs text-primary"></i></span>
                                    <span v-show="!vehicle.autoTraderWebYes"><i class="far fa-circle fa-xs text-primary"></i></span>
                                </span>
                                <span @click="setMyWebYes(vehicle.id)">
                                    <span v-show="vehicle.myWebYes"><i class="fas fa-circle fa-xs text-primary"></i></span>
                                    <span v-show="!vehicle.myWebYes"><i class="far fa-circle fa-xs text-primary"></i></span>
                                </span>
                            </td>
                            <td v-if="showMassAction" style="width:24px" class="d-print-none">
                                <span v-if="selected.indexOf(vehicle.id) !== -1">
                                    <i class="fas fa-check text-success" :class="selected.indexOf(vehicle.id) !== -1"></i>
                                </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1" @click="changePage(-1)">Previous</a>
                            </li>
                            <li class="page-item" v-for="i in numPages" :class="{active: i === currPage}">
                                <a class="page-link" href="#" @click="currPage = i">{{i}} <span class="sr-only" v-if="i === currPage">(current)</span> </a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" @click="changePage(1)">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Delete Vehicle</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this Vehicle.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger" @click="deleteVehicle">Delete Vehicle</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MassAction -->
        <div class="modal fade" id="massAction" tabindex="-1" role="dialog" aria-labelledby="massActionModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="massActionLabel">{{this.actions[this.massAction]}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to perform this Action on Selected Vehicles
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" @click="performMassAction">Confirm</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['pvehicles','images','stats','Reg/ ID','message'],
        data: function () {
            let fType = JSON.parse(localStorage.getItem('filterType'));
            let fCategory = JSON.parse(localStorage.getItem('filterCategory'));
            let fStatus = JSON.parse(localStorage.getItem('filterStatus'));
            let fAdvertised = JSON.parse(localStorage.getItem('filterAdvertised'));
            let show = JSON.parse(localStorage.getItem('show'));
            let fmyWebYes = JSON.parse(localStorage.getItem('myWebYes'));
            let ffeatured = JSON.parse(localStorage.getItem('featured'));
            let flatest = JSON.parse(localStorage.getItem('latest'));

            return {
                loading: false,
                filterType: fType ? fType : [],
                filterCategory: fCategory ? fCategory : [],
                filterStatus: fStatus ? fStatus : [],
                debounceFilterStatus: [],
                filterAdvertised: fAdvertised ? fAdvertised : [],
                myWebYes: fmyWebYes? fmyWebYes : [],
                featured: ffeatured ? ffeatured : '',
                latest: flatest ? flatest : '',
                filterKey: '',
                sortOrders: {
                    'type' : 1,
                    'category': 1,
                    'serialNumber': 1,
                    'registrationYear': 1,
                    'make': 1,
                    'saleprice': 1,
                    'soldStatus': 1,
                    'updated_at': 1
                },
                sortKey: '',
                vehicles: this.pvehicle,
                searchText: '',
                deleteId: null,
                actions: {
                    inStock: 'Mark As InStock',
                    sold: 'Mark As Sold',
                    poa: 'Set as POA',
                    removePoa: 'Remove POA',
                    featured: 'Set Featured',
                    latest: 'Set Latest',
                    delete: 'Delete',
                    visibleOnWeb: 'Visible on Web',
                    visibleOnAutoTrader: 'visible On AutoTrader',
                    hideOnWeb: 'Hide On Web',
                    hideOnAutoTrader: 'Hide On AutoTrader'
                },
                showMassAction: false,
                massAction: '',
                selected: [],
                show: show ? show : 50,
                currPage: 1,
                numPages: 1,
                endIndex:1,
                vehicleData: this.pvehicles
            }
        },
        mounted: function(){
            this.$toasted.success(this.message);
        },
        watch: {
            showMassAction: function (newVal) {
                if(!newVal){
                    this.selected = []
                }
            },
            filterType: function (val) {
                localStorage.setItem('filterType',JSON.stringify(val));
            },
            filterCategory: function (val) {
                localStorage.setItem('filterCategory',JSON.stringify(val));
            },
            filterStatus: function (val) {
                localStorage.setItem('filterStatus',JSON.stringify(val));
                this.debounceStatusInput();
            },
            filterAdvertised: function (val) {
                localStorage.setItem('filterAdvertised',JSON.stringify(val));
            },
            show: function (val) {
                localStorage.setItem('show',JSON.stringify(val));
            },
            myWebYes: function (val) {
                localStorage.setItem('myWebYes',JSON.stringify(val));
            },
            featured: function (val) {
                localStorage.setItem('featured',JSON.stringify(val));
            },
            latest: function (val) {
                localStorage.setItem('latest',JSON.stringify(val));
            },
        },
        computed: {
            filteredData: function () {
                let data = this.vehicleData;
                let filterType = this.filterType;
                let filterCategory = this.filterCategory;
                let filterStatus = this.debounceFilterStatus;
                let filterAdvertised = this.filterAdvertised;
                let myWebYes = this.myWebYes;
                let featured = this.featured;
                let latest = this.latest;
                let filterKey = this.filterKey.toLowerCase();
                let sortKey = this.sortKey;
                let order = this.sortOrders[sortKey] || 1;
                if (filterKey !== "") {
                    data = data.filter(function (row) {
                        return Object.keys(row).some(function (key) {
                            return String(row[key]).toLowerCase().indexOf(filterKey) > -1
                        });
                    });
                }
                if (sortKey) {
                    data = data.slice().sort(function (a, b) {
                        a = a[sortKey];
                        b = b[sortKey];
                        return (a === b ? 0 : a > b ? 1 : -1) * order;
                    })
                }
                if(filterCategory.length > 0 || filterType.length > 0){
                    data = data.filter( r => {
                        let cflag = filterCategory.length > 0 ? filterCategory.indexOf(r.category) !== -1 : false;
                        let tflag = filterType.length > 0 ? filterType.indexOf(r.type) !== -1 : false;
                        return cflag || tflag;
                    });
                }
                if(filterStatus.length > 0){
                    data = data.filter( r => filterStatus.indexOf(r.soldStatus) !== -1);
                }
                if(filterAdvertised.length > 0){
                    data = data.filter( r => filterAdvertised.indexOf(r.autoTraderWebYes) !== -1);
                }
                if(myWebYes == 1){
                    data = data.filter( r => r.myWebYes == 1);
                }
                if(featured == 1){
                    data = data.filter( r => r.featured == 1);
                }
                if(latest == 1){
                    data = data.filter( r => r.latest == 1);
                }

                this.numPages = Math.ceil(data.length/this.show);
                return data;
            },
            startIndex: function () {
                let currPage = this.currPage;
                this.endIndex = currPage * this.show;
                return (currPage - 1) * this.show;
            }
        },
        methods:{
            changePage: function (val) {
              this.currPage = this.currPage + val;
              if(this.currPage < 1) this.currPage = 1;
              if(this.currPage > this.numPages) this.currPage = this.numPages;
            },
            openLink: function (id) {
                if(!this.showMassAction){
                    window.open('/admin/vehicles/'+id);
                }else{
                    if(this.selected.find(r => r === id)){
                        this.selected.pop(id)
                    }else{
                        this.selected.push(id)
                    }
                }
            },
            sortBy: function (key) {
                this.sortKey = key;
                this.sortOrders[key] = this.sortOrders[key] * -1
            },
            sortClass: function (key) {
                return this.sortKey === key ? this.sortOrders[key] === 1 ? 'fa-sort-up' : 'fa-sort-down' : 'fa-sort';
            },
            sortIcon: function (key) {
                return this.sortKey === key ? this.sortOrders[key] === 1 ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>' : '<i class="fas fa-sort"></i>';
            },
            settings: function (id) {
                $('#settings'+id).dropdown('toggle');
            },
            setSold: function (id) {
              this.updateVehicle({id: id, soldStatus: 1, type: 'soldStatus'});
            },
            setReserved: function (id) {
                this.updateVehicle({id: id, reservedStatus: 1, type: 'reservedStatus'});
            },
            setFeatured: function (id) {
                this.updateVehicle({id: id, featured: 1, type: 'featured' });
            },
            setLatest: function (id) {
                this.updateVehicle({id: id, latest: 1, type: 'latest'});
            },
            setAutoTraderWebYes: function (id) {
                this.updateVehicle({id: id, autoTraderWebYes: 1, type: 'autoTraderWebYes'});
            },
            setMyWebYes: function (id) {
                this.updateVehicle({id: id, myWebYes: 1, type: 'myWebYes'});
            },
            setPOA: function (id) {
                this.updateVehicle({id: id, poa: 1,type: 'poa'});
            },
            confirmMassAction: function () {
                if(this.selected.length > 0){
                    $('#massAction').modal({
                        show: true
                    });
                }else{
                    this.$toasted.error("Please select vehicles prior performing any action")
                    this.massAction = '';
                }
            },
            performMassAction: function () {
                this.loading = true;
                axios({
                    method: 'post',
                    url: '/admin/stock/update',
                    data: {ids : this.selected, action: this.massAction}
                }).then( (response) => {
                        if(response.data.error){
                            this.$toasted.error(response.data.errorMessage);
                            this.loading = false;
                        }
                        else {
                            this.$toasted.success('Updated Successfully');
                            location.reload();
                        }
                        this.loading = false;
                    }).catch((error) => {
                        this.loading = false;
                        this.$toasted.error(error);
                });
            },
            updateVehicle(obj){
                axios.post('/admin/vehicle/update/status',obj)
                    .then( (response) => {
                        if(response.data.error){
                            this.$toasted.error(response.data.errorMessage);
                        }
                        else {
                            this.vehicleData = this.vehicleData.map(r => {
                                if(r.id === obj.id){
                                    switch (obj.type){
                                        case 'soldStatus':
                                            r.autoTraderWebYes = r.soldStatus === 'In Stock' ? 0 : r.autoTraderWebYes;
                                            r.myWebYes = r.soldStatus === 'In Stock' ? 0 : r.myWebYes;
                                            r.soldStatus = r.soldStatus ==='Sold' ? 'In Stock' : 'Sold';
                                            break;
                                        case 'reservedStatus':
                                            r.soldStatus = 'Reserved';
                                            break;
                                        case 'featured':
                                            r.featured = !r.featured;
                                            break;
                                        case 'latest':
                                            r.latest = !r.latest;
                                            break;
                                        case 'autoTraderWebYes':
                                            r.autoTraderWebYes = !r.autoTraderWebYes;
                                            break;
                                        case 'myWebYes':
                                            r.myWebYes = !r.myWebYes;
                                            break;
                                        case 'poa':
                                            r.poa = !r.poa;
                                            break;
                                    }
                                }
                                return r;
                            });
                            this.$toasted.success('Updated Successfully');
                        }
                        this.loading = false;
                    })
                    .catch((error) => {
                        this.loading = false;
                        this.$toasted.error(error);
                    });
            },
            setDelete: function (id) {
                this.deleteId = id;
            },
            deleteVehicle: function () {
                $('#deleteModal').modal('hide');
                this.$toasted.info("Deleting ....");
                let id = this.deleteId;
                this.deleteId = null;
                axios.post('/admin/vehicle/destroy',{
                    vehicle_id: id,
                    isAjax: 1,
                })
                    .then( (response) => {
                        if(response.data.error){
                            this.$toasted.error(response.data.errorMessage);
                            this.loading = false;
                        }
                        else {
                            this.$toasted.success('Deleted Successfully');
                            this.vehicleData = this.vehicleData.filter(r => r.id !== id);
                        }
                        this.loading = false;
                    })
                    .catch((error) => {
                        this.loading = false;
                        this.$toasted.error(error);
                    });
            },
            debounceFilterInput: _.debounce(function (e) {
                if(e.target.value.length > 1){
                    this.filterKey = e.target.value;
                }else{
                    this.filterKey = "";
                }
            }, 500),
            debounceStatusInput: _.debounce(function (e) {
                this.debounceFilterStatus = this.filterStatus;
            }, 500),
        }
    }

</script>

<style scoped>
    .vehicle-link td{
        position: relative;
    }
    .table-striped tbody.mass-action tr:nth-of-type(odd){
        background: #FFF;
    }
    .vehicle-selected td{
        position: relative;
    }
    .mass-action td:after{
        background-color: rgba(255, 255, 255, 0.6) !important;
        content: "";
        position: absolute;
        height: 100%;
        width: 100%;
        left:0;
        top:0;

    }
    .mass-action td:last-child:after{
        display: none;
    }
    .vehicle-selected td:after{
        background-color: rgba(216, 216, 216, 0.7) !important;
        content: "";
        position: absolute;
        height: 100%;
        width: 100%;
        left:0;
        top:0;
    }
    .clear-mass-action{
        cursor: pointer;
    }
</style>