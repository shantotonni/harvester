<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Harvester Info List']">
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" @click="createHarvesterInfo">
                                <i class="fas fa-plus"></i>
                                Add Harvester Info
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

                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <input v-model="query" type="text" class="form-control" placeholder="Search by product name">
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
<!--                                            <th class="text-left">Product Name </th>-->
                                            <th class="text-left">Product Category</th>
                                            <th class="text-left">Model Name</th>
                                            <th class="text-left">Horse Power</th>
                                            <th class="text-left">Details</th>
                                            <th class="text-left">Image</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="( harvester_info, i) in harvester_infos" :key=" harvester_info.id"
                                            v-if=" harvester_infos.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
<!--                                            <td class="text-left">{{ harvester_info.product_name }}</td>-->
                                            <td class="text-left">{{ harvester_info.name }}</td>
                                            <td class="text-left">{{ harvester_info.model_name }}</td>
                                            <td class="text-right">{{ harvester_info.horse_power }}</td>
                                            <td class="text-left" v-html="harvester_info.details "></td>
                                            <td class="text-left">
                                                <img v-if="harvester_info.image" height="40" width="40"
                                                     :src="tableImage(harvester_info.image)" alt="">
                                            </td>
                                            <td class="text-center">
                                                <button @click="edit(harvester_info)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button @click="destroy(harvester_info.id)"
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
                                        @paginate="query === '' ? getAllHarvesterInfo() : searchData()"
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
        <div class="modal fade" id="harvesterinfoModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Harvester
                            Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">
                            ×
                        </button>
                    </div>
                    <form @submit.prevent="editMode ? update() : store()" @keydown="form.onKeydown($event)"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
<!--                                    <div class="col-md-6">-->
<!--                                        <div class="form-group">-->
<!--                                            <label>Product Name</label>-->
<!--                                            <input type="text" name="product_name" v-model="form.product_name"-->
<!--                                                   class="form-control"-->
<!--                                                   :class="{ 'is-invalid': form.errors.has('product_name') }">-->
<!--                                            <div class="error" v-if="form.errors.has('product_name')"-->
<!--                                                 v-html="form.errors.get('product_name')"/>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Product Category</label>
                                            <select name="product_id" id="product_id" class="form-control" v-model="form.product_id"
                                                    :class="{ 'is-invalid': form.errors.has('product_id') }" @change="getAllModelByProduct()">
                                                <option disabled value="">Select Product Category</option>
                                                <option :value="product.id" v-for="(product , index) in products" :key="index">{{ product.name }}
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
                                                        :key="index">{{model.model_name}}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('model_id')"
                                                 v-html="form.errors.get('model_id')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Horse Power</label>
                                            <input type="text" name="To Hour" v-model="form.horse_power"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('horse_power') }">
                                            <div class="error" v-if="form.errors.has('horse_power')"
                                                 v-html="form.errors.get('horse_power')"/>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image <small>(Image type:jpeg,jpg,png,svg)</small></label>
                                            <input @change="changeImage($event)" type="file" name="image"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('image') }">
                                            <div class="error" v-if="form.errors.has('image')"
                                                 v-html="form.errors.get('image')"/>
                                            <img v-if="form.image" :src="showImage(form.image)" alt="" height="40px"
                                                 width="40px">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Details</label>
                                        <vue-editor  name="details"
                                                     v-model="form.details"
                                                     :class="{ 'is-invalid': form.errors.has('details') }"></vue-editor>
                                        <div class="error" v-if="form.errors.has('details')"
                                             v-html="form.errors.get('details')"/>
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
                                {{ editMode ? "Update" : "Create" }} Harvester Info
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
import {VueEditor} from "vue2-editor";
export default {
    components: {
        VueEditor
    },
    name: "List",
    data() {
        return {
            harvester_infos: [],
            models: [],
            products: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                // product_name: '',
                model_id: '',
                product_id: '',
                details: '',
                image: '',
                horse_power: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllHarvesterInfo();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Harvester Info | Harvester';
        this.getAllHarvesterInfo();
    },
    methods: {
        getAllHarvesterInfo() {
            this.isLoading = true;
            axios.get('/api/harvester-info?page=' + this.pagination.current_page ).then((response) => {
                this.harvester_infos = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/harvester-info/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.harvester_infos = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllHarvesterInfo();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#harvesterinfoModal").modal("hide");
        },
        createHarvesterInfo() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#harvesterinfoModal").modal("show");
            this.getAllProduct();
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/harvester-info").then(response => {
                $("#harvesterinfoModal").modal("hide");
                this.getAllHarvesterInfo();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(harvester_info) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(harvester_info);
            this.getAllProduct();
            this.getAllModelByProduct();
            $("#harvesterinfoModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/harvester-info/" + this.form.id).then(response => {
                $("#harvesterinfoModal").modal("hide");
                this.getAllHarvesterInfo();
            }).catch(e => {
                this.isLoading = false;
            });
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
                return window.location.origin + "/harvester/public/images/HarvesterInfo/" + this.form.image;
            }
        },
        tableImage(image) {
            return window.location.origin + "/harvester/public/images/HarvesterInfo/" + image;
        },
        getAllProductModel() {
            axios.get('/api/get-all-product-model').then((response) => {
                console.log(response)
                this.models = response.data.data;
            }).catch((error) => {

            })
        },
        getAllProduct() {
            axios.get('/api/get-all-products').then((response) => {
                console.log(response)
                this.products = response.data.data;
            }).catch((error) => {

            })
        },
        getAllModelByProduct() {
            console.log(this.form.product_id)
            axios.get( '/api/get-all-model-by-product/' + this.form.product_id).then((response) => {
                console.log(response)
                this.models = response.data.data;
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
                    axios.delete('api/harvester-info/' + id).then((response) => {
                        this.getAllHarvesterInfo();
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
