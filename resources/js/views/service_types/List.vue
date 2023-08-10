<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Service Type List']">
            </breadcrumb>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="datatable" v-if="!isLoading">
                            <div class="card-body">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <input v-model="query" type="text" class="form-control" placeholder="Search">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-success btn-sm" @click="createServiceType">
                                            <i class="fas fa-plus"></i>
                                            Add Service Type
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
                                            <th class="text-left">Name</th>
                                            <th class="text-left">Bangla Name</th>
                                            <th class="text-left">Code</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(service_type, i) in service_types" :key="service_type.id"
                                            v-if="service_types.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ service_type.name }}</td>
                                            <td class="text-left">{{ service_type.name_bn }}</td>
                                            <td class="text-left">{{ service_type.code }}</td>
                                            <td class="text-left">
                                                <button @click="edit(service_type)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button @click="destroy(service_type.id)"
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
                                        @paginate="query === '' ? getAllServiceType() : searchData()"
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
        <div class="modal fade" id="servicetypeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Service
                            Type</h5>
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
                                            <label>Name</label>
                                            <input type="text" name="name"
                                                   v-model="form.name"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('name') }">
                                            <div class="error" v-if="form.errors.has('name')"
                                                 v-html="form.errors.get('name')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Bangla Name</label>
                                            <input type="text" name="name_bn" v-model="form.name_bn"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('name_bn') }">
                                            <div class="error" v-if="form.errors.has('name_bn')"
                                                 v-html="form.errors.get('name_bn')"/>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Code</label>
                                            <input type="text" name="Code" v-model="form.code"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('code') }">
                                            <div class="error" v-if="form.errors.has('code')"
                                                 v-html="form.errors.get('code')"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                                        @click="closeModal">
                                    Close
                                </button>
                                <button :disabled="form.busy" type="submit" class="btn btn-primary">
                                    {{ editMode ? "Update" : "Create" }} Service Type
                                </button>
                            </div>

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
            service_types: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                name: '',
                name_bn: '',
                code: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllServiceType();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Service Type List | Harvester';
        this.getAllServiceType();
    },
    methods: {
        getAllServiceType() {
            this.isLoading = true;
            axios.get('/api/service-types?page=' + this.pagination.current_page).then((response) => {
                console.log('data', response.data.data)
                this.service_types = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/service-types/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.service_types = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllServiceType();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#servicetypeModal").modal("hide");
        },
        createServiceType() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#servicetypeModal").modal("show");
            this.getAllServiceType();
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/service-types").then(response => {
                $("#servicetypeModal").modal("hide");
                this.getAllServiceType();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(service_type) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(service_type);
            this.getAllServiceType();
            $("#servicetypeModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/service-types/" + this.form.id).then(response => {
                $("#servicetypeModal").modal("hide");
                this.getAllServiceType();
            }).catch(e => {
                this.isLoading = false;
            });
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
                    axios.delete('api/service-types/' + id).then((response) => {
                        this.getAllServiceType();
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
