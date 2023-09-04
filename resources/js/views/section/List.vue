<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Section List']">
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
                                                <input v-model="query" type="text" class="form-control" placeholder="Search by name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-success btn-sm" @click="createSectionList">
                                            <i class="fas fa-plus"></i>
                                            Add Section
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
                                        <tr v-for="(section, i) in sections" :key="section.section_id"
                                            v-if="sections.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ section.section_name }}</td>
                                            <td class="text-left">{{ section.code }}</td>
                                            <td class="text-center">
                                                <button @click="edit(section)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
<!--                                                <button @click="destroy(section.section_id)"-->
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
                                        @paginate="query === '' ? getAllSectionList() : searchData()"
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
        <div class="modal fade" id="sectionModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Section</h5>
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
                                            <label>Section name</label>
                                            <input type="text" name="name"
                                                   v-model="form.section_name"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('section_name') }">
                                            <div class="error" v-if="form.errors.has('section_name')"
                                                 v-html="form.errors.get('section_name')"/>
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
                                    {{ editMode ? "Update" : "Create" }} Section
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
            sections: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                section_id: '',
                section_name: '',
                code: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllSectionList();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Section List | Harvester';
        this.getAllSectionList();
    },
    methods: {
        getAllSectionList() {
            this.isLoading = true;
            axios.get('/api/section-list?page=' + this.pagination.current_page).then((response) => {
                console.log('data', response.data.data)
                this.sections = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/section-list/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.sections = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllSectionList();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#sectionModal").modal("hide");
        },
        createSectionList() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#sectionModal").modal("show");
            this.getAllSectionList();
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/section-list").then(response => {
                $("#sectionModal").modal("hide");
                this.getAllSectionList();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(section) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(section);
            this.getAllSectionList();
            $("#sectionModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/section-list/" + this.form.section_id).then(response => {
                $("#sectionModal").modal("hide");
                this.getAllSectionList();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        destroy(section_id) {
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
                    axios.delete('api/section-list/' + section_id).then((response) => {
                        this.getAllSectionList();
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
