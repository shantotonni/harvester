<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Fuel Pump List']">
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" @click="createFuel">
                                <i class="fas fa-plus"></i>
                                Add Fuel Pump
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
                                                <button type="submit" @click="getAllFuel " class="btn btn-success"><i class="mdi mdi-filter"></i>Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <input v-model="query" type="text" class="form-control" placeholder="Search by owner name">
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">District</th>
                                            <th class="text-left">Owner name</th>
                                            <th class="text-left">Fuel Pump name </th>
                                            <th class="text-left">Address</th>
                                            <th class="text-left">Mobile</th>
                                            <th class="text-left">Lat</th>
                                            <th class="text-left">Long</th>
                                            <th class="text-left">Image</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(fuel, i) in fuels" :key="fuel.id"
                                            v-if="fuels.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ fuel.district_name }}</td>
                                            <td class="text-left">{{ fuel.owner_name }}</td>
                                            <td class="text-left">{{ fuel.fuel_pump_name }}</td>
                                            <td class="text-left">{{ fuel.address }}</td>
                                            <td class="text-right">{{ fuel.mobile }}</td>
                                            <td class="text-right">{{ fuel.lat }}</td>
                                            <td class="text-right">{{ fuel.lon }}</td>
                                            <td class="text-left">
                                                <img v-if="fuel.image" height="40" width="40"
                                                     :src="tableImage(fuel.image)" alt="">
                                            </td>
                                            <td class="text-left">
                                                <button @click="edit(fuel)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button @click="destroy(fuel.id)"
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
                                        @paginate="query === '' ? getAllFuel () : searchData()"
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
        <div class="modal fade" id="fuelModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Fuel  Pump </h5>
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
                                            <label>Owner Name</label>
                                            <input type="text" name="owner_name"
                                                   v-model="form.owner_name"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('owner_name') }">
                                            <div class="error" v-if="form.errors.has('owner_name')"
                                                 v-html="form.errors.get('owner_name')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Fuel Pump Name</label>
                                            <input type="text" name="fuel_pump_name"
                                                   v-model="form.fuel_pump_name"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('fuel_pump_name') }">
                                            <div class="error" v-if="form.errors.has('fuel_pump_name')"
                                                 v-html="form.errors.get('fuel_pump_name')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Mobile</label>
                                            <input type="text" name="mobile" v-model="form.mobile"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('mobile') }">
                                            <div class="error" v-if="form.errors.has('mobile')"
                                                 v-html="form.errors.get('mobile')"/>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="Name" v-model="form.address"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('address') }">
                                            <div class="error" v-if="form.errors.has('address')"
                                                 v-html="form.errors.get('address')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>lat</label>
                                            <input type="text" name="lat" v-model="form.lat"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('lat') }">
                                            <div class="error" v-if="form.errors.has('lat')"
                                                 v-html="form.errors.get('lat')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>long</label>
                                            <input type="text" name="lon" v-model="form.lon"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('lon') }">
                                            <div class="error" v-if="form.errors.has('lon')"
                                                 v-html="form.errors.get('lon')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image</label>
                                            <input @change="changeImage($event)" required type="file" name="image" class="form-control" :class="{ 'is-invalid': form.errors.has('image') }">
                                            <div class="error" v-if="form.errors.has('image')" v-html="form.errors.get('image')" />
                                            <img v-if="form.image" :src="showImage(form.image)" alt="" height="40px" width="40px">
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
                                {{ editMode ? "Update" : "Create" }} Fuel  Pump
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</template>

<script>

export default {
    name: "List",
    data() {
        return {
            fuels: [],
            districts: [],
            pagination: {
                current_page: 1
            },
            query: "",
            district_id:'',
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                district_id: '',
                owner_name: '',
                address: '',
                fuel_pump_name: '',
                mobile: '',
                lat: '',
                lon: '',
                image: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllFuel();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Fuel Pump List | Harvester';
        this.getAllFuel();
        this.getAllDistricts();
    },
    methods: {
        getAllFuel() {
            this.isLoading = true;
            axios.get('/api/fuel-pump-list?page=' + this.pagination.current_page +"&district_id="+ this.district_id ).then((response) => {
                // console.log('data', response.data.data)
                this.fuels = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/fuel-pump-list/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.fuels = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllFuel();
            this.district_id="";
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#fuelModal").modal("hide");
        },
        createFuel() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#fuelModal").modal("show");
            this.getAllDistricts();
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/fuel-pump-list").then(response => {
                $("#fuelModal").modal("hide");
                this.getAllFuel();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(fuel) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(fuel);

            this.getAllDistricts();
            $("#fuelModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/fuel-pump-list/" + this.form.id).then(response => {
                $("#fuelModal").modal("hide");
                this.getAllFuel();
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
        changeImage(event) {
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = event => {
                this.form.image = event.target.result;
            };
            reader.readAsDataURL(file);
        },
        showImage() {
            let img = this.form.image;
            if (img.length > 100) {
                return this.form.image;
            } else {
                return window.location.origin + "/harvester/public/images/fuel/" + this.form.image;
            }
        },
        tableImage(image) {
            return window.location.origin + "/harvester/public/images/fuel/" + image;
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
                    axios.delete('api/fuel-pump-list/' + id).then((response) => {
                        this.getAllFuel();
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    })
                }
            })
        }
    },
}
</script>

<style scoped>

</style>
