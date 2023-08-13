<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Customer List']"/>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="datatable" v-if="!isLoading">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <input v-model="query" type="text" class="form-control"
                                                       placeholder="Search">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-success btn-sm" @click="createCustomer">
                                            <i class="fas fa-plus"></i>
                                            Add Customer
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" @click="reload">
                                            <i class="fas fa-sync"></i>
                                            Reload
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">Customer Name</th>
                                            <th class="text-left">Customer Address</th>
                                            <th class="text-left">Mobile Number</th>
                                            <th class="text-left">Product Name</th>
                                            <th class="text-left">Model Name</th>
                                            <th class="text-left">Service Hour</th>
                                            <th class="text-left">Area</th>
                                            <th class="text-left">District</th>
                                            <th class="text-left">Address</th>
                                            <th class="text-left">Chassis no</th>
                                            <th class="text-left"> Image</th>
                                            <th class="text-left"> Customer Type</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(customer, i) in customers" :key="customer.CustomerID"
                                            v-if="customers.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ customer.name }}</td>
                                            <td class="text-left">{{ customer.address }}</td>
                                            <td class="text-right">{{ customer.mobile }}</td>
                                            <td class="text-left">{{ customer.product_name }}</td>
                                            <td class="text-left">{{ customer.model_name }}</td>
                                            <td class="text-left">{{ customer.service_hour }}</td>
                                            <td class="text-left">{{ customer.area_name }}</td>
                                            <td class="text-left">{{ customer.district_name }}</td>
                                            <td class="text-left">{{ customer.address }}</td>
                                            <td class="text-left">{{ customer.chassis }}</td>
                                            <td class="text-left">{{ customer.image }}</td>
                                            <td class="text-left">{{ customer.customer_type }}</td>
                                            <td class="text-left">
                                                <button @click="edit(customer)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <!--                                                    <button @click="show(company)" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button>-->
                                                <!--                                                    <button @click="destroy(customer)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllCustomer() : searchData()"
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
        <div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }}
                            Customer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"
                                @click.prevent="closeModal">×
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Customer Name</label>
                                            <input v-model="form.name" type="text" name="name" class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('name') }">
                                            <div class="error" v-if="form.errors.has('name')"
                                                 v-html="form.errors.get('name')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Customer Mobile</label>
                                            <input v-model="form.mobile" type="number" name="mobile"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('mobile') }">
                                            <div class="error" v-if="form.errors.has('mobile')"
                                                 v-html="form.errors.get('mobile')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Customer Email</label>
                                            <input v-model="form.email" type="email" name="email" class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('email') }">
                                            <div class="error" v-if="form.errors.has('email')"
                                                 v-html="form.errors.get('email')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6" v-if="!editMode">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="password" v-model="form.password"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('password') }">
                                            <div class="error" v-if="form.errors.has('password')"
                                                 v-html="form.errors.get('password')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>District</label>
                                            <select name="text" id="district_id" class="form-control"
                                                    v-model="form.district_id"
                                                    :class="{ 'is-invalid': form.errors.has('district_id') }">
                                                <option disabled value="">Select District</option>
                                                <option :value="district.id" v-for="(district , index) in districts"
                                                        :key="index">{{ district.name }}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('district_id')"
                                                 v-html="form.errors.get('district_id')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Product Category</label>
                                            <select name="product_id" id="product_id" class="form-control"
                                                    v-model="form.product_id"
                                                    :class="{ 'is-invalid': form.errors.has('product_id') }"
                                                    @change="getAllModelByProduct()">
                                                <option disabled value="">Select Product Category</option>
                                                <option :value="product.id" v-for="(product , index) in products"
                                                        :key="index">{{ product.name }}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('product_id')"
                                                 v-html="form.errors.get('product_id')"/>
                                        </div>
                                    </div>

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
                                            <label>Image</label>
                                            <input @change="changeImage($event)" type="file" name="Image"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('Image') }">
                                            <div class="error" v-if="form.errors.has('Image')"
                                                 v-html="form.errors.get('Image')"/>
                                            <img v-if="form.Image" :src="showImage(form.Image)" alt="" height="40px"
                                                 width="40px">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Service Hour</label>
                                            <input v-model="form.service_hour" type="text" name="service_hour"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('service_hour') }">
                                            <div class="error" v-if="form.errors.has('service_hour')"
                                                 v-html="form.errors.get('service_hour')"/>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Customer Address</label>
                                            <input v-model="form.address" type="text" name="address"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('address') }">
                                            <div class="error" v-if="form.errors.has('address')"
                                                 v-html="form.errors.get('address')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6" v-if="!editMode">
                                        <div class="form-group">
                                            <label>Chassis</label>
                                            <input type="text" name="chassis" v-model="form.chassis"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('chassis') }">
                                            <div class="error" v-if="form.errors.has('chassis')"
                                                 v-html="form.errors.get('chassis')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6" v-if="!editMode">
                                        <div class="form-group">
                                            <label>Customer Type</label>
                                            <input type="text" name="customer_type" v-model="form.customer_type"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('customer_type') }">
                                            <div class="error" v-if="form.errors.has('customer_type')"
                                                 v-html="form.errors.get('customer_type')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Area</label>
                                            <select name="text" id="area_id" class="form-control" v-model="form.area_id"
                                                    :class="{ 'is-invalid': form.errors.has('area_id') }">
                                                <option disabled value="">Select Area</option>
                                                <option :value="area.id" v-for="(area , index) in areas" :key="index">
                                                    {{ area.name }}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('area_id')"
                                                 v-html="form.errors.get('area_id')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Purchase Date</label>
                                        <datepicker name="To Hour" v-model="form.data_of_purchase"
                                                    :format="customFormatter"
                                                    placeholder="Enter To Date"
                                                    :class="{ 'is-invalid': form.errors.has('data_of_purchase') }" style="width: 100%;height: 32px" > </datepicker>
                                        <div class="error" v-if="form.errors.has('data_of_purchase')"
                                             v-html="form.errors.get('data_of_purchase')"/>
                                    </div>
                                </div>
                                </div>

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            @click.prevent="closeModal">Close
                    </button>
                    <button :disabled="form.busy" type="submit" class="btn btn-primary">
                        {{ editMode ? "Update" : "Create" }} Customer
                    </button>
                </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
    </div>
</template>

<script>
import Datepicker from "vuejs-datepicker";
import moment from "moment";

document.title = 'Customer List | Harvester';
export default {
    name: "List",
    components: {
        Datepicker
    },
    data() {
        return {
            customers: [],
            districts: [],
            areas: [],
            companies: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                name: '',
                mobile: '',
                password: '',
                product_id: '',
                model_id: '',
                service_hour: '',
                data_of_purchase: '',
                area_id: '',
                district_id: '',
                address: '',
                chassis: '',
                chassis_image: '',
                customer_type: '',
                image: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllCustomer();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        this.getAllCustomer();
    },
    methods: {
        getAllCustomer() {
            this.isLoading = true;
            axios.get('/api/customer?page=' + this.pagination.current_page).then((response) => {
                this.customers = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/customer/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.customers = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllCustomer();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#customerModal").modal("hide");
        },
        createCustomer() {
            this.getAllCompany();
            this.getAllDistricts();
            this.getAllArea();
            this.getAllModelByProduct();
            this.getAllProduct();
            this.getAllProductModel();
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#customerModal").modal("show");
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/customer").then(response => {
                $("#customerModal").modal("hide");
                this.getAllCustomer();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(customer) {
            this.getAllCompany();
            this.getAllDistricts();
            this.getAllArea();
            this.getAllModelByProduct();
            this.getAllProduct();
            this.getAllProductModel();
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(customer);
            $("#customerModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/customer/" + this.form.id).then(response => {
                $("#customerModal").modal("hide");
                this.getAllCustomer();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        changeImage(event) {
            let file = event.target.files[0];
            let reader = new FileReader();
            reader.onload = event => {
                this.form.Image = event.target.result;
            };
            reader.readAsDataURL(file);
        },
        showImage() {
            let img = this.form.Image;
            if (img.length > 100) {
                return this.form.Image;
            } else {
                return window.location.origin + "/Harvester/images/customer/" + this.form.Image;
            }
        },
        getAllProductModel() {
            axios.get('/api/get-all-product-model').then((response) => {
                console.log(response)
                this.models = response.data.models;
            }).catch((error) => {

            })
        },
        getAllProduct() {
            axios.get('/api/get-all-products').then((response) => {
                console.log(response)
                this.products = response.data.products;
            }).catch((error) => {

            })
        },
        getAllModelByProduct() {
            axios.get('/api/get-all-model-by-product/' + this.form.product_id).then((response) => {
                console.log(response)
                this.models = response.data.data;
            }).catch((error) => {

            })
        },
        getAllArea() {
            axios.get('/api/get-all-areas').then((response) => {
                console.log('areas', response.data.areas)
                this.areas = response.data.areas;
            }).catch((error) => {

            })
        },
        getAllDistricts() {
            axios.get('/api/get-all-districts').then((response) => {
                console.log(response)
                this.districts = response.data.districts;
            }).catch((error) => {

            })
        },
        getAllCompany() {
            axios.get('/api/get-company').then((response) => {
                this.companies = response.data.data;
            }).catch((error) => {

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
