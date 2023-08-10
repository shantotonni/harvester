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
                                                <span class="badge badge-warning" v-if="job_card.job_status == 'pending'">pending</span>
                                            </td>
                                            <td class="text-center">
                                                <router-link :to="`/harvester/service-request-details/${job_card.id}`" class="btn btn-primary btn-sm btn-xs"><i class="far fa-eye"></i></router-link>
                                                <!--                                                <button type="button" @click="edit(delivery)" class="btn btn-success btn-sm btn-xs"><i class="far fa-edit"></i></button>-->
                                                <!--                                                <button type="button" @click="destroy(delivery.id)" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>-->
                                            </td>
                                        </tr>
                                        <tr v-else>
                                            <td class="text-center">No Data Found</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <br>
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
    </div>
</template>

<script>

export default {
    name: "List",
    data() {
        return {
            job_cards: [],
            technicians: [],
            service_types: [],
            pagination: {
                current_page: 1
            },
            query: "",
            district_id:'',
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                technitian_id: '',
                customer_id: '',
                engineer_id: '',
                customer_name: '',
                remarks: '',
                service_wanted_at: '',
                service_start_at: '',
                service_end_at: '',
                job_status: '',
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
        document.title = 'Service Request List | Harvester';
        this. getAllPendingServiceRequestList();
        this. getAllSections();
        this. getAllServiceType();
        this. getAllTechnician();
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


        getAllTechnician(){
            axios.get('/api/get-all-technician').then((response)=>{
                this.technicians = response.data.data;
            }).catch((error)=>{

            })
        },
        getAllSections(){
            axios.get('/api/get-all-sections').then((response)=>{
                console.log(response)
                this.problem_section = response.data.data;
            }).catch((error)=>{

            })
        },
        getAllServiceType(){
            axios.get('/api/get-all-service-type').then((response)=>{
                this.service_types = response.data.data;
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
