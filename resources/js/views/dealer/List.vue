<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Dealer List']">
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" @click="createDealer">
                                <i class="fas fa-plus"></i>
                                Add Dealer
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
                                                    <select name="" id="" v-model="area_id" class="form-control">
                                                        <option disabled value="">Select Area</option>
                                                        <option :value="area.id" v-for="(area , index) in areas" :key="index">{{ area.name_bn }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <button type="submit" @click="getAllDealer" class="btn btn-success"><i class="mdi mdi-filter"></i>Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <input v-model="query" type="text" class="form-control" placeholder="Search by Dealer name">
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">Area</th>
                                            <th class="text-left">Responsible person</th>
                                            <th class="text-left">Address</th>
                                            <th class="text-left">Mobile</th>
                                            <th class="text-left">Lat</th>
                                            <th class="text-left">Long</th>
                                            <th class="text-left">Image</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(dealer, i) in dealers" :key="dealer.id"
                                            v-if="dealers.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ dealer.area_name_bn }}</td>
                                            <td class="text-left">{{ dealer.dealer_name }}</td>
                                            <td class="text-left">{{ dealer.address }}</td>
                                            <td class="text-right">{{ dealer.mobile }}</td>
                                            <td class="text-right">{{ dealer.lat }}</td>
                                            <td class="text-right">{{ dealer.long }}</td>
                                            <td class="text-left">
                                                <img v-if="dealer.image" height="40" width="40"
                                                     :src="tableImage(dealer.image)" alt="">
                                            </td>
                                            <td class="text-left">
                                                <button @click="edit(dealer)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button @click="destroy(dealer.id)"
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
                                        @paginate="query === '' ? getAllDealer() : searchData()"
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
        <div class="modal fade" id="dealerModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Dealer </h5>
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
                                            <label>Area</label>
                                            <select name="text" id="area_id" class="form-control" v-model="form.area_id"
                                                    :class="{ 'is-invalid': form.errors.has('area_id') }">
                                                <option disabled value="">Select Area</option>
                                                <option :value="area.id" v-for="(area , index) in areas" :key="index">{{ area.name_bn}}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('area_id')"
                                                 v-html="form.errors.get('area_id')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Dealer Name</label>
                                            <input type="text" name="dealer_name"
                                                   v-model="form.dealer_name"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('dealer_name') }">
                                            <div class="error" v-if="form.errors.has('dealer_name')"
                                                 v-html="form.errors.get('dealer_name')"/>
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
                                            <input type="text" name="long" v-model="form.long"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('long') }">
                                            <div class="error" v-if="form.errors.has('long')"
                                                 v-html="form.errors.get('long')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image</label>
                                            <input @change="changeImage($event)" type="file" name="image" class="form-control" :class="{ 'is-invalid': form.errors.has('image') }">
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
                                {{ editMode ? "Update" : "Create" }} Dealer
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
            dealers: [],
            areas: [],
            pagination: {
                current_page: 1
            },
            query: "",
            area_id:'',
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                area_id: '',
                address: '',
                dealer_name: '',
                mobile: '',
                lat: '',
                long: '',
                image: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllDealer();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Dealer List | Harvester';
        this.getAllDealer();
        this.getAllAreas();
    },
    methods: {
        getAllDealer() {
            this.isLoading = true;
            axios.get('/api/dealer-list?page=' + this.pagination.current_page +"&area_id="+ this.area_id ).then((response) => {
                // console.log('data', response.data.data)
                this.dealers = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/dealer-list/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.dealers = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllDealer();
            this.area_id="";
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#dealerModal").modal("hide");
        },
        createDealer() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#dealerModal").modal("show");
            this.getAllAreas();
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/dealer-list").then(response => {
                $("#dealerModal").modal("hide");
                this.getAlldealer();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(dealer) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(dealer);

            this.getAllAreas();
            $("#dealerModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/dealer-list/" + this.form.id).then(response => {
                $("#dealerModal").modal("hide");
                this.getAllDealer();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        getAllAreas() {
            axios.get('/api/get-all-areas').then((response) => {
              console.log(response)
                this.areas = response.data.areas;
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
                return window.location.origin + "/harvester/public/images/dealer/" + this.form.image;
            }
        },
        tableImage(image) {
            return window.location.origin + "/harvester/public/images/dealer/" + image;
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
                    axios.delete('api/dealer-list/' + id).then((response) => {
                        this.getAllDealer();
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
