<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Service Engineer List']">
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" @click="createServiceEngineer">
                                <i class="fas fa-plus"></i>
                                Add Service Engineer
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
                                                    <select name="" id="" v-model="district_id" class="form-control">
                                                        <option disabled value="">Select District</option>
                                                        <option :value="district.id" v-for="(district , index) in districts" :key="index">{{ district.name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-3">
                                                <button type="submit" @click="getAllServiceEngineer" class="btn btn-success"><i class="mdi mdi-filter"></i>Filter
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools">
                                        <input v-model="query" type="text" class="form-control" placeholder="Search by Enginner name">
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">District</th>
                                            <th class="text-left">Service Engineer</th>
                                            <th class="text-left">Address</th>
                                            <th class="text-left">Mobile</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(service_engineer, i) in service_engineers" :key="service_engineer.id"
                                            v-if="service_engineers.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ service_engineer.district_name }}</td>
                                            <td class="text-left">{{ service_engineer.engineer_name }}</td>
                                            <td class="text-left">{{ service_engineer.address }}</td>
                                            <td class="text-right">{{ service_engineer.mobile }}</td>
                                            <td class="text-center">
                                                <button @click="edit(service_engineer)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
                                                <button @click="destroy(service_engineer.id)"
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
                                        @paginate="query === '' ? getAllServiceEngineer() : searchData()"
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
        <div class="modal fade" id="engineerModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Service Engineer</h5>
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
                                            <label>District</label>
                                            <select name="text" id="district_id" class="form-control" v-model="form.district_id"
                                                    :class="{ 'is-invalid': form.errors.has('district_id') }">
                                                <option disabled value="">Select District</option>
                                                <option :value="district.id" v-for="(district , index) in districts" :key="index">{{ district.name}}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('district_id')"
                                                 v-html="form.errors.get('district_id')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Engineer Name</label>
                                            <input type="text" name="engineer_name"
                                                   v-model="form.engineer_name"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('engineer_name') }">
                                            <div class="error" v-if="form.errors.has('engineer_name')"
                                                 v-html="form.errors.get('engineer_name')"/>
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
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">
                                Close
                            </button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">
                                {{ editMode ? "Update" : "Create" }} Service Enginner
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
            service_engineers: [],
            districts: [],
            pagination: {
                current_page: 1
            },
            query: "",
            district_id:'',
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                district_id: '',
                engineer_name: '',
                address: '',
                mobile: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllServiceEngineer();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Service Engineers List | Harvester';
        this.getAllServiceEngineer();
        this.getAllDistricts();
    },
    methods: {
        getAllServiceEngineer() {
            this.isLoading = true;
            axios.get('/api/service-engineer?page=' + this.pagination.current_page +"&district_id="+ this.district_id ).then((response) => {
                // console.log('data', response.data.data)
                this.service_engineers = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/service-engineer/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.service_engineers = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllServiceEngineer();
            this.district_id="";
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#engineerModal").modal("hide");
        },
        createServiceEngineer() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#engineerModal").modal("show");
            this.getAllDistricts();


        },
        store() {
            this.form.busy = true;
            this.form.post("/api/service-engineer").then(response => {
                $("#engineerModal").modal("hide");
                this.getAllServiceEngineer();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(service_engineer) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(service_engineer);
            this.getAllDistricts();
            $("#engineerModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/service-engineer/" + this.form.id).then(response => {
                $("#engineerModal").modal("hide");
                this.getAllServiceEngineer();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        getAllDistricts() {
            axios.get('/api/get-all-districts').then((response) => {
              console.log(response)
                this.districts = response.data.districts;
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
                    axios.delete('api/service-engineer/' + id).then((response) => {
                        this.getAllServiceEngineer();
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
