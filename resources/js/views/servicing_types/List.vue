<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Servicing Type List']">
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
                                        <button type="button" class="btn btn-success btn-sm" @click="createServicingType">
                                            <i class="fas fa-plus"></i>
                                            Add Servicing Type
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
                                            <th class="text-left">Code</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(servicing_type, i) in servicing_types" :key="servicing_type.id"
                                            v-if="servicing_types.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ servicing_type.name }}</td>
                                            <td class="text-left">{{ servicing_type.code }}</td>
                                            <td class="text-center">
                                                <button @click="edit(servicing_type)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
<!--                                                <button @click="destroy(servicing_type.id)"-->
<!--                                                        class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>-->
<!--                                                </button>-->
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllServicingType() : searchData()"
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
        <div class="modal fade" id="servicingtypeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Servicing
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
                                            <label>Code</label>
                                            <input type="number" name="Code" v-model="form.code"
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
                                    {{ editMode ? "Update" : "Create" }} Servicing Type
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
            servicing_types: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                name: '',
                code: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllServicingType();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Servicing Type List | Harvester';
        this.getAllServicingType();
    },
    methods: {
        getAllServicingType() {
            this.isLoading = true;
            axios.get('/api/servicing-types?page=' + this.pagination.current_page).then((response) => {
                console.log('data', response.data.data)
                this.servicing_types = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/servicing-types/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.servicing_types = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllServicingType();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#servicingtypeModal").modal("hide");
        },
        createServicingType() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#servicingtypeModal").modal("show");
            this.getAllServicingType();
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/servicing-types").then(response => {
                $("#servicingtypeModal").modal("hide");
                this.getAllServicingType();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(servicing_type) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(servicing_type);
            this.getAllServicingType();
            $("#servicingtypeModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/servicing-types/" + this.form.id).then(response => {
                $("#servicingtypeModal").modal("hide");
                this.getAllServicingType();
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
                    axios.delete('api/servicing-types/' + id).then((response) => {
                        this.getAllServicingType();
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
