<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['District Wise Seasonal Crops List']">
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="card-tools">
                                <button type="button" class="btn btn-success btn-sm" @click="createSeasonalCrop">
                                <i class="fas fa-plus"></i>
                                Add District Wise Seasonal Crops
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" @click="reload">
                                <i class="fas fa-sync"></i>
                                Reload
                            </button>
                        </div>
                    </div>
                </div>
            </breadcrumb>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="datatable" v-if="!isLoading">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <select name="" id="" v-model="district_id" class="form-control">
                                                        <option disabled value="">Select District</option>
                                                        <option :value="district.id" v-for="(district , index) in districts" :key="index">{{ district.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <button type="submit" @click="getAllDistrictWiseSeasonalCrops" class="btn btn-success"><i class="mdi mdi-filter"></i>Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <input v-model="query" type="text" class="form-control" placeholder="Search by seasonal crops">
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">District</th>
                                            <th class="text-left">Seasonal Crops</th>
                                            <th class="text-left">From Date</th>
                                            <th class="text-left">To Date</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(seasonal_crop, i) in seasonal_crops" :key="seasonal_crop.id"
                                            v-if="seasonal_crops.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ seasonal_crop.district_name }}</td>
                                            <td class="text-left">{{ seasonal_crop.seasonal_crops_name }}</td>
                                            <td class="text-left">{{ seasonal_crop.date_from }}</td>
                                            <td class="text-left">{{ seasonal_crop.date_to }}</td>
                                            <td class="text-center">
                                                <button @click="edit(seasonal_crop)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button @click="destroy(seasonal_crop.id)"
                                                        class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllDistrictWiseSeasonalCrops() : searchData()"
                                    ></pagination>
                                </div>
                            </div>
                        </div>
                        <div v-else>
                            <skeleton-loader :row="14"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  Modal content for the above example -->
        <div class="modal fade" id="harvesterserviceModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} District Wise Seasonal Crops</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">
                            ×
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>District</label>
                                            <select name="text" id="district_id" class="form-control" v-model="form.district_id"
                                                    :class="{ 'is-invalid': form.errors.has('district_id') }">
                                                <option disabled value="">Select District</option>
                                                <option :value="district.id" v-for="(district , index) in districts" :key="index">{{ district.name}}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('district_id')"
                                                 v-html="form.errors.get('district_id')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Seasonal Crops</label>
                                            <select name="text" id="seasonal_crops_id" class="form-control" v-model="form.seasonal_crops_id"
                                                    :class="{ 'is-invalid': form.errors.has('seasonal_crops_id') }">
                                                <option disabled value="">Select Seasonal Crop</option>
                                                <option :value="crop.id" v-for="(crop , index) in crops" :key="index">{{ crop.name }}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('seasonal_crops_id')"
                                                 v-html="form.errors.get('seasonal_crops_id')"/>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>From Hour</label>
                                            <datepicker name="From hour"
                                                        placeholder="Enter To Date"
                                                        :format="customFormatter"
                                                        v-model="form.date_from"
                                                        :class="{ 'is-invalid': form.errors.has('date_from') }"> </datepicker>

                                            <div class="error" v-if="form.errors.has('date_from')"
                                                 v-html="form.errors.get('date_from')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>To Hour</label>
                                            <datepicker name="To Hour" v-model="form.date_to"
                                                        :format="customFormatter"
                                                        placeholder="Enter To Date"
                                                        :class="{ 'is-invalid': form.errors.has('date_to') }"> </datepicker>

                                            <div class="error" v-if="form.errors.has('date_to')"
                                                 v-html="form.errors.get('date_to')"/>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">
                                Close
                            </button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">
                                {{ editMode ? "Update" : "Create" }} District Wise Seasonal Crops
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import Datepicker from 'vuejs-datepicker';
    import moment from "moment";
export default {

    name: "List",
    components: {
        Datepicker
    },
    data() {
        return {
            seasonal_crops: [],
            districts: [],
            crops: [],
            pagination: {
                current_page: 1
            },
            district_id:'',
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                district_id: '',
                seasonal_crops_id: '',
                date_from: '',
                date_to: '',

            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllDistrictWiseSeasonalCrops();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'District Wise Seasonal Crops | Harvester';
        this.getAllDistrictWiseSeasonalCrops();
        this.getAllDistricts();
    },
    methods: {
        getAllDistrictWiseSeasonalCrops() {
            this.isLoading = true;
            axios.get('/api/district-wise-seasonal-crops?page=' + this.pagination.current_page +"&district_id="+ this.district_id).then((response) => {
                this.seasonal_crops = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/district-wise-seasonal-crops/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.seasonal_crops = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.district_id="";
            this.query = "";
            this.getAllDistrictWiseSeasonalCrops();
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#harvesterserviceModal").modal("hide");
        },
        createSeasonalCrop() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#harvesterserviceModal").modal("show");
            this.getAllDistricts();
            this.getAllCrops();
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/district-wise-seasonal-crops").then(response => {
                $("#harvesterserviceModal").modal("hide");
                this.getAllDistrictWiseSeasonalCrops()

            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(seasonal_crop) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(seasonal_crop);
            $("#harvesterserviceModal").modal("show");
            this.getAllDistricts();
            this.getAllCrops();
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/district-wise-seasonal-crops/" + this.form.id).then(response => {
                $("#harvesterserviceModal").modal("hide");
                this.getAllDistrictWiseSeasonalCrops();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        getAllDistricts() {
            axios.get('/api/get-all-districts').then((response) => {
                console.log(response)
                this.districts = response.data.districts;
            }).catch((error) => {

            })
        },

        getAllCrops() {
            axios.get('/api/get-all-crops').then((response) => {
                console.log(response)
                this.crops = response.data.crops;
            }).catch((error) => {

            })
        },
        destroy(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete('api/district-wise-seasonal-crops/' + id).then((response) => {
                        this.getAllDistrictWiseSeasonalCrops();
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    })
                }
            })
        },
        customFormatter(date) {
            return moment(date).format('MMMM Do YYYY');
        },
    },
}
</script>

<style scoped>

</style>
