<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Harvester Service Details List']">
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" @click="createHarvesterService">
                                <i class="fas fa-plus"></i>
                                Add Harvester Service Details
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
                                                    <select name="" id="" v-model="model_id" class="form-control">
                                                        <option disabled value="">Select Model</option>
                                                        <option :value="model.id" v-for="(model , index) in models"
                                                                :key="index">{{ model.model_name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <button type="submit" @click="getAllHarvesterServiceDetails"
                                                        class="btn btn-success"><i class="mdi mdi-filter"></i>Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <input v-model="query" type="text" class="form-control" placeholder="Search by model">
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">Model</th>
                                            <th class="text-left">Servicing Type</th>
                                            <th class="text-left">From hour</th>
                                            <th class="text-left">To hour</th>
                                            <th class="text-left">Fixed Hour</th>
                                            <th class="text-left">Parts Name</th>
                                            <th class="text-left">Parts Code</th>
                                            <th class="text-left">Price</th>
                                            <th class="text-left">Quantity</th>
                                            <th class="text-left">Servicing Status</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(harvester_service, i) in harvester_services"
                                            :key="harvester_service.id"
                                            v-if="harvester_services.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ harvester_service.model_name }}</td>
                                            <td class="text-left">{{ harvester_service.servicing_name }}</td>
                                            <td class="text-right">{{ harvester_service.from_hr }}</td>
                                            <td class="text-right">{{ harvester_service.to_hr }}</td>
                                            <td class="text-right">{{ harvester_service.fix_hour }}</td>
                                            <td class="text-left">{{ harvester_service.parts_name }}</td>
                                            <td class="text-left">{{ harvester_service.parts_code }}</td>
                                            <td class="text-right">{{ harvester_service.price }}</td>
                                            <td class="text-right">{{ harvester_service.quantity }}</td>
                                            <td class="text-right">{{ harvester_service.servicing_status }}</td>
                                            <td class="text-center">
                                                <button @click="edit(harvester_service)" class="btn btn-success btn-sm">
                                                    <i
                                                        class="far fa-edit"></i></button>
                                                <button @click="destroy(harvester_service.id)"
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
                                        @paginate="query === '' ? getAllHarvesterServiceDetails() : searchData()"
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
        <div class="modal fade" id="harvesterserviceModal" tabindex="-1" role="dialog"
             aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Harvester
                            Service Details</h5>
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
                                            <label>Model</label>
                                            <select name="text" id="model_id" class="form-control"
                                                    v-model="form.model_id"
                                                    :class="{ 'is-invalid': form.errors.has('model_id') }">
                                                <option disabled value="">Select Model</option>
                                                <option :value="model.id" v-for="(model , index) in models"
                                                        :key="index">{{ model.model_name }}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('model_id')"
                                                 v-html="form.errors.get('model_id')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Servicing Type</label>
                                            <select name="text" id="servicing_type_id" class="form-control"
                                                    v-model="form.servicing_type_id"
                                                    :class="{ 'is-invalid': form.errors.has('servicing_type_id') }">
                                                <option disabled value="">Select Service Type</option>
                                                <option :value="servicing_type.id"
                                                        v-for="(servicing_type , index) in servicing_types"
                                                        :key="index">{{ servicing_type.name }}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('servicing_type_id')"
                                                 v-html="form.errors.get('servicing_type_id')"/>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>From Hour</label>
                                            <input type="text" name="From hour"
                                                   v-model="form.from_hr"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('from_hr') }">
                                            <div class="error" v-if="form.errors.has('from_hr')"
                                                 v-html="form.errors.get('from_hr')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>To Hour</label>
                                            <input type="text" name="To Hour" v-model="form.to_hr"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('to_hr') }">
                                            <div class="error" v-if="form.errors.has('to_hr')"
                                                 v-html="form.errors.get('to_hr')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Fixed Hour</label>
                                            <input type="text" name="Fixed Hour" v-model="form.fix_hour"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('fix_hour') }">
                                            <div class="error" v-if="form.errors.has('fix_hour')"
                                                 v-html="form.errors.get('fix_hour')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Product Name</label>
                                            <input type="text" name="product_name" v-model="form.product_name"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('product_name') }">
                                            <div class="error" v-if="form.errors.has('product_name')"
                                                 v-html="form.errors.get('product_name')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Parts Name</label>
                                            <input type="text" name="parts_name" v-model="form.parts_name"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('parts_name') }">
                                            <div class="error" v-if="form.errors.has('parts_name')"
                                                 v-html="form.errors.get('parts_name')"/>
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Quantity</label>
                                            <input type="number" name="quantity" v-model="form.quantity"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('quantity') }">
                                            <div class="error" v-if="form.errors.has('quantity')"
                                                 v-html="form.errors.get('quantity')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Parts Code</label>
<!--                                            <select name="ProductCode" id="ProductCode" class="form-control"-->
<!--                                                    v-model="form.ProductCode"-->
<!--                                                    :class="{ 'is-invalid': form.errors.has('ProductCode') }"-->
<!--                                                    @change="getAllPriceByMirror()">-->
<!--                                                <option disabled value="">Select Parts Code</option>-->
<!--                                                <option :value="mirror_product.ProductCode"-->
<!--                                                        v-for="(mirror_product , index) in mirror_products"-->
<!--                                                        :key="index">{{ mirror_product.ProductCode }} - -->
<!--                                                    {{ mirror_product.ProductName }}-->
<!--                                                </option>-->
<!--                                            </select>-->
                                            <multiselect
                                                v-model="form.ProductCode"
                                                :options="mirror_products"
                                                :multiple="false"
                                                :searchable="true"
                                                :close-on-select="true"
                                                :show-labels="true"
                                                label="ProductName"
                                                track-by="ProductCode"
                                                @change="getAllPriceByMirror()"
                                                placeholder="Pick a parts"></multiselect>
<!--                                            <div class="error" v-if="form.errors.has('ProductCode')"-->
<!--                                                 v-html="form.errors.get('ProductCode')"/>-->
                                        </div>
                                    </div>


<!--                                    <form action="/action_page.php">-->
<!--                                        <label for="cars">Choose a car:</label>-->
<!--                                        <select name="cars" id="cars">-->
<!--                                            <option value="volvo">Volvo</option>-->
<!--                                            <option value="saab">Saab</option>-->
<!--                                            <option value="opel">Opel</option>-->
<!--                                            <option value="audi">Audi</option>-->
<!--                                        </select>-->
<!--                                        <br><br>-->
<!--                                        <input type="submit" value="Submit">-->
<!--                                    </form>-->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Servicing Status</label>
                                            <select type="text" name="servicing_status" v-model="form.servicing_status"
                                                    class="form-control"
                                                    :class="{ 'is-invalid': form.errors.has('servicing_status') }">
                                                <option disabled value="">Select Service Status</option>
                                                <option >
                                                    Adjustable
                                                </option>
                                                <option >
                                                    Changable
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('servicing_status')"
                                                 v-html="form.errors.get('servicing_status')"/>
                                        </div>
                                   </div>



<!--                 <div class="col-md-6">&ndash;&gt;-->
<!--                                        <div class="form-group">-->
<!--                                            <label>Price</label>-->
<!--                                            <div name="UnitPrice" id="UnitPrice" class="form-control"-->
<!--                                                 v-model="form.UnitPrice"-->
<!--                                                 :class="{ 'is-invalid': form.errors.has('UnitPrice') }"-->
<!--                                                 v-for="(prices , index) in prices" :key="index">-->
<!--                                                {{ prices.UnitPrice }}-->

<!--                                            </div>-->
<!--                                            <div class="error" v-if="form.errors.has('UnitPrice')"-->
<!--                                                 v-html="form.errors.get('UnitPrice')"/>-->
<!--                                        </div>-->
<!--                                    </div>-->

                                </div>


                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">
                                Close
                            </button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">
                                {{ editMode ? "Update" : "Create" }} Harvester Service Details
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
export default {
    components: {
        Multiselect
    },
    name: "List",
    data() {
        return {
            harvester_services: [],
            models: [],
            servicing_types: [],
            mirror_products: [],
            prices: [],
            pagination: {
                current_page: 1
            },
            model_id: '',
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                model_id: '',
                servicing_type_id: '',
                from_hr: '',
                to_hr: '',
                fix_hour: '',
                parts_name: '',
                parts_code: '',
                price: '',
                quantity: '',
                servicing_status: '',
                ProductCode: '',

            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllHarvesterServiceDetails();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Harvester Service Details | Harvester';
        this.getAllHarvesterServiceDetails();
        this.getAllProductModel();
    },
    methods: {
        getAllHarvesterServiceDetails() {
            this.isLoading = true;
            axios.get('/api/harvester-service-details?page=' + this.pagination.current_page + "&model_id=" + this.model_id).then((response) => {
                this.harvester_services = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/harvester-service-details/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.harvester_services = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.model_id = "";
            this.query = "";
            this.getAllHarvesterServiceDetails();
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#harvesterserviceModal").modal("hide");
        },
        createHarvesterService() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#harvesterserviceModal").modal("show");
            this.getAllMirrorProduct();
            this.getAllProductModel();
            this.getAllServicingType();
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/harvester-service-details").then(response => {
                $("#harvesterserviceModal").modal("hide");
                this.getAllHarvesterServiceDetails()

            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(harvester_service) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(harvester_service);
            $("#harvesterserviceModal").modal("show");
            this.getAllMirrorProduct();
            this.getAllProductModel();
            this.getAllServicingType();

        },
        update() {
            this.form.busy = true;
            this.form.put("/api/harvester-service-details/" + this.form.id).then(response => {
                $("#harvesterserviceModal").modal("hide");
                this.getAllHarvesterServiceDetails();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        getAllPriceByMirror() {
            axios.get('/api/get-all-mirror-price/' + this.form.ProductCode).then((response) => {
                console.log(response)
                this.prices = response.data.prices;
            }).catch((error) => {

            })
        },
        getAllProductModel() {
            axios.get('/api/get-all-product-model').then((response) => {
                this.models = response.data.models;
            }).catch((error) => {

            })
        },
        getAllMirrorProduct() {
            axios.get('/api/get-all-mirror-product').then((response) => {
                this.mirror_products = response.data.mirror_products;
            }).catch((error) => {

            })
        },


        getAllServicingType() {
            axios.get('/api/get-all-servicing-type').then((response) => {
                this.servicing_types = response.data.servicing_types;
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
                    axios.delete('api/harvester-service-details/' + id).then((response) => {
                        this.getAllHarvesterServiceDetails();
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
