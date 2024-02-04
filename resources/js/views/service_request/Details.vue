<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Service Request Details']"/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane p-3 active" id="home-1" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                                    <tbody>
                                                    <tr>
                                                        <th style="width: 50%">Engineer name</th>
                                                        <th style="width: 50%">{{ job_cards.engineer }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Technician name</th>
                                                        <th style="width: 50%">{{ job_cards.technitian }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Area </th>
                                                        <th style="width: 50%">{{ job_cards.area }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Customer name</th>
                                                        <th style="width: 50%">{{ job_cards.customer_name }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Customer Mobile</th>
                                                        <th style="width: 50%">{{ job_cards.customer_moblie }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Creator</th>
                                                        <th style="width: 50%">{{ job_cards.creator_name }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Product</th>
                                                        <th style="width: 50%">{{ job_cards.product}}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Model</th>
                                                        <th style="width: 50%">{{ job_cards.model_name }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Contact person name</th>
                                                        <th style="width: 50%">{{ job_cards.customer_moblie }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Call By</th>
                                                        <th style="width: 50%">{{ job_cards.call_type }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Type</th>
                                                        <th style="width: 50%">{{ job_cards.service_type }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Income</th>
                                                        <th style="width: 50%">{{ job_cards.service_income }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service wanted date</th>
                                                        <th style="width: 50%">{{ job_cards.service_wanted_at }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Start date</th>
                                                        <th style="width: 50%">{{ job_cards.service_start_at }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service End date</th>
                                                        <th style="width: 50%">{{ job_cards.service_end_at }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Installation date</th>
                                                        <th style="width: 50%">{{ job_cards.installation_date }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Buy Date</th>
                                                        <th style="width: 50%">{{ job_cards.buy_date }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Visited Date</th>
                                                        <th style="width: 50%">{{ job_cards.visited_date }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Request No</th>
                                                        <th style="width: 50%">{{ job_cards.job_card_no }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Request Status</th>
                                                        <th style="width: 50%">{{ job_cards.job_status }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Hour</th>
                                                        <th style="width: 50%">{{ job_cards.hour }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Remarks</th>
                                                        <th style="width: 50%">{{ job_cards.remarks }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Income</th>
                                                        <th style="width: 50%">{{ job_cards.service_income }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Address</th>
                                                        <th style="width: 50%">{{ job_cards.address }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Chassis Number</th>
                                                        <th style="width: 50%">{{ job_cards.chassis_number }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Running Hour</th>
                                                        <th style="width: 50%">{{ job_cards.running_houre }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Section</th>
                                                        <th style="width: 50%">{{ job_cards.section }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Total Receviable Amount</th>
                                                        <th style="width: 50%">{{ job_cards.total_receviable }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Discount Amount</th>
                                                        <th style="width: 50%">{{ job_cards.discount_amount }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Total Service Cost</th>
                                                        <th style="width: 50%">{{ job_cards.total_service_cost }}</th>
                                                    </tr> <tr>
                                                        <th style="width: 50%">Invoice Number</th>
                                                        <th style="width: 50%">{{ job_cards.invoice_number }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Spare Parts</th>
                                                        <th style="width: 50%">{{ job_cards.spare_parts_sale }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">App Time</th>
                                                        <th style="width: 50%">{{ job_cards.time_app }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Date</th>
                                                        <th style="width: 50%">{{ job_cards.service_date }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Created At</th>
                                                        <th style="width: 50%">{{ job_cards.created_at }}</th>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
            engineers: [],
            technitians: [],
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
                technitian: '',
                area_id: '',
                customer_id: '',
                engineer_id: '',
                engineer: '',
                product_id: '',
                product: '',
                model_id: '',
                call_type_id: '',
                call_type: '',
                section_id: '',
                section: '',
                service_type_id: '',
                service_type: '',
                customer_name: '',
                customer_moblie: '',
                buy_date: '',
                visited_date: '',
                installation_date: '',
                service_date: '',
                created_at: '',
                service_income: '',
                address: '',
                chassis_number: '',
                creator_name: '',
                running_houre: '',
                time_app: '',
                spare_parts_sale: '',
                invoice_number: '',
                total_service_cost: '',
                discount_amount: '',
                total_receviable: '',
                hour: '',
                remarks: '',
                service_wanted_at: '',
                service_start_at: '',
                service_end_at: '',
                job_status: '',
                job_card_no: '',
            }),
        }
    },
    mounted() {
        document.title = 'Service Request Details | Harvester';
        this.getServiceRequestDetailsList();
        this.getAllTechnician();
        this.getAllEngineer();
        this.getAllServiceType();
    },

    methods: {
        getServiceRequestDetailsList(){
            this.isLoading = true;
            axios.get(`/api/service-request-details/${this.$route.params.id}`).then((response)=>{
                this.job_cards = response.data.data
                this.isLoading = false;
            }).catch((error)=>{

            })
        },

        getAllTechnician(){
            axios.get('/api/get-all-technician').then((response)=>{
                this.technicians = response.data.technicians;
            }).catch((error)=>{

            })
        },
        getAllEngineer(){
            axios.get('/api/get-all-engineer').then((response)=>{
                console.log(response)
                this.engineers = response.data.engineers;
            }).catch((error)=>{

            })
        },
        getAllServiceType(){
            axios.get('/api/get-all-service-type').then((response)=>{
                this.service_types = response.data.service_types;
            }).catch((error)=>{

            })
        },
        reload(){
            this.query = "";
            this.technician_id = "";
            this.service_type_id = "";
            this.getServiceRequestDetailsList();
            this.$toaster.success('Data Successfully Refresh');
        },

    },
}
</script>

<style lang="scss" scoped>

</style>
