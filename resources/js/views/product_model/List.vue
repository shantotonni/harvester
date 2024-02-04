<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Product Model List']">
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" @click="createHarvesterInfo">
                                <i class="fas fa-plus"></i>
                                Add Product Model
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
                                        <input v-model="query" type="text" class="form-control" placeholder="Search by model name">
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">Model Name</th>
                                            <th class="text-left">Model Name Bengal</th>
                                            <th class="text-left">Model Code</th>
                                            <th class="text-left">Details</th>
                                            <th class="text-left">Product Name </th>
                                            <th class="text-left">Image</th>

                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="( model, i) in models" :key=" model.id"
                                            v-if=" models.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ model.model_name }}</td>
                                            <td class="text-left">{{ model.model_name_bn }}</td>
                                            <td class="text-left">{{ model.model_code }}</td>
                                            <td class="text-left" v-html="model.details "></td>
                                            <td class="text-left">{{ model.name }}</td>
                                            <td class="text-left">
                                                <img v-if="model.image" height="40" width="40"
                                                     :src="tableImage(model.image)" alt="">
                                            </td>
                                            <td class="text-center">
                                                <button @click="edit(model)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllModelByProduct() : searchData()"
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
        <div class="modal fade" id="productmodelModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Product Category</label>
                                            <select name="product_id" id="product_id" class="form-control" v-model="form.product_id"
                                                    :class="{ 'is-invalid': form.errors.has('product_id') }">
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
                                            <input type="text" name="model_name" v-model="form.model_name"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('model_name') }">
                                            <div class="error" v-if="form.errors.has('model_name')"
                                                 v-html="form.errors.get('model_name')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Model Name Bangla</label>
                                            <input type="text" name="name" v-model="form.model_name_bn"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('model_name_bn') }">
                                            <div class="error" v-if="form.errors.has('model_name_bn')"
                                                 v-html="form.errors.get('model_name_bn')"/>
                                        </div>
                                    </div>  <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Model Code</label>
                                            <input type="text" name="code" v-model="form.model_code"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('model_code') }">
                                            <div class="error" v-if="form.errors.has('model_code')"
                                                 v-html="form.errors.get('model_code')"/>
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

                                </div>
                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">
                                Close
                            </button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">
                                {{ editMode ? "Update" : "Create" }} Product Model
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
                name: '',
                model_name:'',
                model_name_bn:'',
                model_code:'',
                product_id: '',
                details: '',
                image: '',

            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllModelByProduct();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Product Model | Harvester';
        this.getAllModelByProduct();
    },
    methods: {
        getAllModelByProduct() {
            this.isLoading = true;
            axios.get('/api/product-model?page=' + this.pagination.current_page ).then((response) => {
                this.models = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/product-model/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.models = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllModelByProduct();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#productmodelModal").modal("hide");
        },
        createHarvesterInfo() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#productmodelModal").modal("show");
            this.getAllProduct();
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/product-model").then(response => {
                $("#productmodelModal").modal("hide");
                this.getAllModelByProduct();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(model) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(model);
            this.getAllProduct();
            $("#productmodelModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/product-model/" + this.form.id).then(response => {
                $("#productmodelModal").modal("hide");
                this.getAllModelByProduct();
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
                return window.location.origin + "/harvester/public/images/ProductModel/" + this.form.image;
            }
        },
        tableImage(image) {
            return window.location.origin + "/harvester/public/images/ProductModel/" + image;
        },
        getAllProduct() {
            axios.get('/api/get-all-products').then((response) => {
                this.products = response.data.products;
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
                    axios.delete('api/product-model/' + id).then((response) => {
                        this.getAllModelByProduct();
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
