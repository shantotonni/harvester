<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Pending Service Request List']">
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="card-tools">
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
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-center">SN</th>
                                            <th class="text-center">Engineer</th>
                                            <th class="text-center">Technician</th>
                                            <th class="text-center">Service Type</th>
                                            <th class="text-center">Section Name</th>
                                            <th class="text-center">Contact P.Name</th>
                                            <th class="text-center">Contact Number</th>
                                            <th class="text-center">Remarks</th>
                                            <th class="text-center">S.Wanted Date</th>
                                            <th class="text-center">S.Started Date</th>
                                            <th class="text-center">S.End Date</th>
                                            <th class="text-center">Service Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(job_card, i) in job_cards" :key="job_card.id" v-if="job_cards.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-center">{{ job_card.engineer }}</td>
                                            <td class="text-center">{{ job_card.technitian}}</td>
                                            <td class="text-center">{{ job_card.service_type }}</td>
                                            <td class="text-center">{{ job_card.section }}</td>
                                            <td class="text-center">{{ job_card.customer_name }}</td>
                                            <td class="text-center">{{ job_card.customer_moblie }}</td>
                                            <td class="text-center">{{ job_card.remarks }}</td>
                                            <td class="text-center">{{ job_card.service_wanted_at }}</td>
                                            <td class="text-center">{{ job_card.service_start_at }}</td>
                                            <td class="text-center">{{ job_card.service_end_at }}</td>
                                            <td class="text-center">
                                                <span class="badge badge-warning" v-if="job_card.job_status == 'started'">started</span>
                                                <span class="badge badge-warning" v-if="job_card.job_status == ''">pending</span>
                                            </td>
                                            <td class="text-center">
                                                <router-link :to="`service-request-details/${job_card.id}`" class="btn btn-primary btn-sm btn-xs"><i class="far fa-eye"></i></router-link>
                                                <button type="button" @click="edit(job_card)" class="btn btn-success btn-sm btn-xs">Assign</button>
<!--                                                <button type="button" @click="destroy(job_card.id)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>-->
                                            </td>
                                        </tr>
                                        <tr v-else>
                                            <td class="text-center">No Data Found</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
                                    <pagination
                                        v-if="pagination.last_page > 1"
                                        :pagination="pagination"
                                        :offset="5"
                                        @paginate="query === '' ? getAllPendingServiceRequestList() : searchData()"
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
        <div class="modal fade" id="fuelModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">Assign</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true" @click="closeModal">
                            ×
                        </button>
                    </div>
                    <form @submit.prevent="assign()" @keydown="form.onKeydown($event)"
                          enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Technician</label>
                                            <select name="text" id="technician_id" class="form-control" v-model="form.technician_id" :class="{ 'is-invalid': form.errors.has('technician_id') }">
                                                <option disabled value="">Select Technician</option>
                                                <option :value="technician.id" v-for="(technician , index) in technicians" :key="index">{{ technician.name}}
                                                </option>
                                            </select>
                                            <div class="error" v-if="form.errors.has('technician_id')" v-html="form.errors.get('technician_id')"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
                            <button :disabled="form.busy" type="submit" class="btn btn-primary">
                                Assign
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
            job_cards: [],
            technicians: [],
            pagination: {
                current_page: 1
            },
            query: "",
            district_id:'',
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                technician_id: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this. getAllPendingServiceRequestList();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Pending Service Request List | Harvester';
        this. getAllPendingServiceRequestList();
        this. getAllSections();
    },
    methods: {
        getAllPendingServiceRequestList() {
            this.isLoading = true;
            axios.get('/api/pending-service-request-list?page=' + this.pagination.current_page).then((response) => {
                console.log( response)
                this.job_cards = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        edit(fuel) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.id = fuel.id
            this.getAllTechnician();
            $("#fuelModal").modal("show");
        },
        getAllTechnician(){
            axios.get('/api/get-all-technician').then((response)=>{
                console.log(response.data)
                this.technicians = response.data.technitians;
            }).catch((error)=>{

            })
        },
        assign(){
            this.form.busy = true;
            this.form.post("/api/assign-technician").then(response => {
                $("#fuelModal").modal("hide");
                this.$toaster.success('Assigned Successfully');
                this.getAllPendingServiceRequestList();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        closeModal() {
            $("#fuelModal").modal("hide");
        },
        searchData() {
            axios.get("/api/search/pending-service-request-list/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.job_cards = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllPendingServiceRequestList();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        getAllSections(){
            axios.get('/api/get-all-sections').then((response)=>{
                console.log(response)
                this.problem_section = response.data.data;
            }).catch((error)=>{

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
                    axios.delete('api/pending-service-request-list/' + id).then((response) => {
                        this.getAllPendingServiceRequestList();
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
