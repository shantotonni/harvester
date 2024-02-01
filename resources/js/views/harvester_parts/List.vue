<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Harvester Parts List']">
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" @click="createHarvesterParts">
                                <i class="fas fa-plus"></i>
                                Add Harvester Parts
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
                                        <input v-model="query" type="text" class="form-control" placeholder="Search">
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">Parts Code</th>
                                            <th class="text-left">Custom Name</th>
                                            <th class="text-left">section</th>
                                            <th class="text-left">Model</th>
                                            <th class="text-left">Unit Price</th>
                                            <th class="text-left">Parts Type</th>
                                            <th class="text-left">Image</th>

                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="( harvester_part, i) in harvester_parts" :key=" harvester_part.parts_id" v-if=" harvester_parts.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ harvester_part.parts }} - {{ harvester_part.ProductName }}</td>
                                            <td class="text-left">{{ harvester_part.custom_name }}</td>
                                            <td class="text-left">{{ harvester_part.section_name }}</td>
<!--                                            <td class="text-left">{{ harvester_part.model_name}}</td>-->
                                            <td class="text-left" >
                                                    <li v-for="( model, i) in harvester_part.product_model">
                                                        {{model.model_name}}
                                                    </li>
                                            </td>
                                            <td class="text-right">{{ harvester_part.UnitPrice}}</td>
                                            <td class="text-left">{{ harvester_part.parts_type}}</td>
                                            <td class="text-left">
                                                <img v-if="harvester_part.image" height="40" width="40" :src="tableImage(harvester_part.image)" alt="">
                                            </td>
                                            <td class="text-center">
                                                <button @click="edit(harvester_part)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button @click="destroy(harvester_part.parts_id)"
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
                                        @paginate="query === '' ? getAllHarvesterPart() : searchData()"
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
        <div class="modal fade" id="harvesterpartsModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Harvester Parts</h5>
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
                                            <label>Parts Code</label>
                                            <multiselect
                                                v-model="form.ProductCode"
                                                :options="mirror_products"
                                                :multiple="false"
                                                :searchable="true"
                                                :close-on-select="true"
                                                :show-labels="true"
                                                label="ProductCode"
                                                track-by="ProductCode"
                                                @change="getAllPriceByMirror($event)"
                                                placeholder="Pick a parts"></multiselect>
                                            <div class="error" v-if="form.errors.has('ProductCode')" v-html="form.errors.get('ProductCode')"/>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Custom Name</label>
                                            <input type="text" name="custom_name" v-model="form.custom_name" class="form-control" :class="{ 'is-invalid': form.errors.has('custom_name') }">
                                            <div class="error" v-if="form.errors.has('custom_name')" v-html="form.errors.get('custom_name')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Section</label>
                                            <select name="text" id="section_id" class="form-control" v-model="form.section_id" :class="{ 'is-invalid': form.errors.has('section_id') }">
                                                <option disabled value="">Select Section</option>
                                                <option :value="section.id" v-for="(section , index) in sections" :key="index">{{ section.name }}</option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('section_id')" v-html="form.errors.get('section_id')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label> Models</label>
                                            <multiselect
                                                v-model="form.product_model"
                                                :options="models"
                                                :multiple="true"
                                                :searchable="true"
                                                :close-on-select="true"
                                                :show-labels="false"
                                                label="model_name"
                                                track-by="model_name"
                                                placeholder="Pick a value"></multiselect>
                                            <div class="error" v-if="form.errors.has('model_name')" v-html="form.errors.get('model_name')"/>
                                        </div>
                                    </div>
<!--                                    <div class="col-md-6">-->
<!--                                        <div class="form-group">-->
<!--                                            <label>Model</label>-->
<!--                                            <select name="text" id="product_model_id" class="form-control" v-model="form.product_model_id" :class="{ 'is-invalid': form.errors.has('product_model_id') }">-->
<!--                                                <option disabled value="">Select Model</option>-->
<!--                                                <option :value="model.id" v-for="(model , index) in models" :key="index">{{ model.model_name }}</option>-->
<!--                                            </select>-->
<!--                                            <div class="error" v-if="form.errors.has('product_model_id')" v-html="form.errors.get('product_model_id')"/>-->
<!--                                        </div>-->
<!--                                    </div>-->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image <small>(Image type:jpeg,jpg,png,svg)</small></label>
                                            <input @change="changeImage($event)" type="file" name="image" class="form-control" :class="{ 'is-invalid': form.errors.has('image') }">
                                            <div class="error" v-if="form.errors.has('image')" v-html="form.errors.get('image')"/>
                                            <img v-if="form.image" :src="showImage(form.image)" alt="" height="40px" width="40px">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Parts Type</label>
                                            <select name="text" id="parts_type" class="form-control"
                                                    v-model="form.parts_type"
                                                    :class="{ 'is-invalid': form.errors.has('parts_type') }">
                                                <option disabled value="">Select Parts Type</option>
                                                <option value="Classic">Classic</option>
                                                <option value="Premium">Premium</option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('parts_type')"
                                                 v-html="form.errors.get('parts_type')"/>
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
                        {{ editMode ? "Update" : "Create" }} Harvester Parts
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
import Multiselect from "vue-multiselect";

export default {
    components: {
        VueEditor,
        Multiselect
    },
    name: "List",
    data() {
        return {
            harvester_parts: [],
            models: [],
            sections: [],
            harvester_model: [],
            mirror_products: [],
            pagination: {
                current_page: 1
            },
            query: "",
            parts: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                parts_id:'',
                custom_name: '',
                //product_model_id: '',
                ProductCode: '',
                section_id: '',
                image: '',
                parts_type: '',
                product_model: [],
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllHarvesterPart();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Harvester Parts | Harvester';
        this.getAllHarvesterPart();
    },
    methods: {
        getAllHarvesterPart() {
            this.isLoading = true;
            axios.get('/api/harvester-parts?page=' + this.pagination.current_page).then((response) => {
                this.harvester_parts = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/harvester-parts/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.harvester_parts = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllHarvesterPart();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#harvesterpartsModal").modal("hide");
        },
        createHarvesterParts() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#harvesterpartsModal").modal("show");
            this.getAllMirrorProduct();
            this.getAllProductModel();
            this.getAllSections();

        },
        store() {
            this.form.busy = true;
            this.form.post("/api/harvester-parts").then(response => {
                $("#harvesterpartsModal").modal("hide");
                this.getAllHarvesterPart()
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(harvester_part) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(harvester_part);
            this.getAllMirrorProduct();
            this.getAllProductModel();
            this.getAllSections();
            $("#harvesterpartsModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/harvester-parts/" + this.form.parts_id).then(response => {
                $("#harvesterpartsModal").modal("hide");
                this.getAllHarvesterPart();
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
                return window.location.origin + "/harvester/public/images/HarvesterParts/" + this.form.image;
            }
        },
        tableImage(image) {
            return window.location.origin + "/harvester/public/images/HarvesterParts/" + image;
        },
        getAllProductModel() {
            axios.get('/api/get-all-product-model').then((response) => {
                this.models = response.data.data;
            }).catch((error) => {

            })
        },
        getAllPriceByMirror() {
            axios.get('/api/get-all-mirror-price/' + this.form.ProductCode).then((response) => {
                this.prices = response.data.prices;
            }).catch((error) => {

            })
        },
        getAllMirrorProduct() {
            axios.get('/api/get-all-mirror-product').then((response) => {
                this.mirror_products = response.data.mirror_products;
            }).catch((error) => {

            })
        },
        getAllSections() {
            axios.get('/api/get-all-sections').then((response) => {
                console.log(response)
                this.sections = response.data.sections;
            }).catch((error) => {

            })
        },
        destroy(parts_id) {
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
                    axios.delete('api/harvester-parts/' + parts_id).then((response) => {
                        this.getAllHarvesterPart();
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
