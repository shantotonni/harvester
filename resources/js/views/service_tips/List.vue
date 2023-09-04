<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Service Tips List']">
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" @click="createServiceTips">
                                <i class="fas fa-plus"></i>
                                Add Service Tips
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
                                        <input v-model="query" type="text" class="form-control" placeholder="Search by name">
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">Name</th>
                                            <th class="text-left">Type</th>
                                            <th class="text-left">Description</th>
                                            <th class="text-left">Video link</th>
                                            <th class="text-left">Image</th>
                                            <th class="text-left">Status</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="( service_tip, i) in service_tips" :key=" service_tip.id"
                                            v-if=" service_tips.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ service_tip.title }}</td>
                                            <td class="text-left">{{ service_tip.type}}</td>
                                            <td class="text-left" v-html="service_tip.description"></td>
                                            <td class="text-left">{{ service_tip.video_link }}</td>
                                            <td class="text-left">
                                                <img v-if="service_tip.image" height="40" width="40"
                                                     :src="tableImage(service_tip.image)" alt="">
                                            </td>
                                            <td class="text-left">
                                                <span class="badge badge-success" v-if="service_tip.active == 'Y'">Active</span>
                                                <span class="badge badge-success" v-else>InActive</span>
                                            </td>
                                            <td class="text-left">
                                                <button @click="edit(service_tip)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button @click="destroy(service_tip.id)"
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
                                        @paginate="query === '' ? getAllServiceTips() : searchData()"
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
        <div class="modal fade" id="servicetipsModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }}Service Tips</h5>
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
                                            <label>Title</label>
                                            <input type="text" name="title" v-model="form.title"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('title') }">
                                            <div class="error" v-if="form.errors.has('title')"
                                                 v-html="form.errors.get('title')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Type</label>
                                            <input type="text" name="type" v-model="form.type"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('type') }">
                                            <div class="error" v-if="form.errors.has('type')"
                                                 v-html="form.errors.get('type')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Video Link</label>
                                            <input type="text" name="To Hour" v-model="form.video_link"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('video_link') }">
                                            <div class="error" v-if="form.errors.has('video_link')"
                                                 v-html="form.errors.get('video_link')"/>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Image</label>
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
                                        <label>Description</label>
                                        <vue-editor  name="description"
                                                     v-model="form.description"
                                                     :class="{ 'is-invalid': form.errors.has('description') }"></vue-editor>
                                        <div class="error" v-if="form.errors.has('description')"
                                             v-html="form.errors.get('description')"/>
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
                                {{ editMode ? "Update" : "Create" }} Service Tips
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
            service_tips: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                title: '',
                type:'',
                description: '',
                video_link: '',
                active: '',
                image: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllServiceTips();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Service Tips | Harvester';
        this.getAllServiceTips();
    },
    methods: {
        getAllServiceTips() {
            this.isLoading = true;
            axios.get('/api/service-tips?page=' + this.pagination.current_page ).then((response) => {
                this.service_tips = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/service-tips/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.service_tips = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllServiceTips();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#servicetipsModal").modal("hide");
        },
        createServiceTips() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#servicetipsModal").modal("show");
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/service-tips").then(response => {
                $("#servicetipsModal").modal("hide");
                this.getAllServiceTips();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(service_tip) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(service_tip);
            $("#servicetipsModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/service-tips/" + this.form.id).then(response => {
                $("#servicetipsModal").modal("hide");
                this.getAllServiceTips();
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
                return window.location.origin + "/harvester/public/images/ServiceTips/" + this.form.image;
            }
        },
        tableImage(image) {
            return window.location.origin + "/harvester/public/images/ServiceTips/" + image;
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
                    axios.delete('api/service-tips/' + id).then((response) => {
                        this.getAllServiceTips();
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
