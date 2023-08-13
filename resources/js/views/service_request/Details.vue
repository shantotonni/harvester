<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Service Request Details']"/>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane p-3 active" id="home-1" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4 class="page-title" style="font-size: 14px">Service Request Details</h4>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                                    <tbody>
                                                    <tr>
                                                        <th style="width: 50%">Engineer name</th>
                                                        <th style="width: 50%">{{ job_card_detail.engineer }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Technician name</th>
                                                        <th style="width: 50%">{{ job_card_detail.technitian }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service type name</th>
                                                        <th style="width: 50%">{{ job_card_detail.service_type}}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Area </th>
                                                        <th style="width: 50%">{{ job_card_detail.area }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Customer name</th>
                                                        <th style="width: 50%">{{ job_card_detail.customer_name }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Customer Mobile</th>
                                                        <th style="width: 50%">{{ job_card_detail.customer_mobile }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Engineer</th>
                                                        <th style="width: 50%">{{ job_card_detail.engineer }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Creator</th>
                                                        <th style="width: 50%">{{ job_card_detail.creator_name }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Product</th>
                                                        <th style="width: 50%">{{ job_card_detail.product}}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Model</th>
                                                        <th style="width: 50%">{{ job_card_detail.model_name }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Contact person name</th>
                                                        <th style="width: 50%">{{ job_card_detail.contact_person_name }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Call By</th>
                                                        <th style="width: 50%">{{ job_card_detail.call_type }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Type</th>
                                                        <th style="width: 50%">{{ job_card_detail.service_type }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Income</th>
                                                        <th style="width: 50%">{{ job_card_detail.service_income }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service wanted date</th>
                                                        <th style="width: 50%">{{ job_card_detail.service_wanted_at }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Start date</th>
                                                        <th style="width: 50%">{{ job_card_detail.service_start_at }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service End date</th>
                                                        <th style="width: 50%">{{ job_card_detail.service_end_at }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Installation date</th>
                                                        <th style="width: 50%">{{ job_card_detail.installation_date }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Buy Date</th>
                                                        <th style="width: 50%">{{ job_card_detail.buy_date }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Visited Date</th>
                                                        <th style="width: 50%">{{ job_card_detail.visited_date }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Request No</th>
                                                        <th style="width: 50%">{{ job_card_detail.job_card_detail_no }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Request Status</th>
                                                        <th style="width: 50%">{{ job_card_detail.job_card_detail_status }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Hour</th>
                                                        <th style="width: 50%">{{ job_card_detail.hour }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Remarks</th>
                                                        <th style="width: 50%">{{ job_card_detail.remarks }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Income</th>
                                                        <th style="width: 50%">{{ job_card_detail.service_income }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Address</th>
                                                        <th style="width: 50%">{{ job_card_detail.address }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Chassis Number</th>
                                                        <th style="width: 50%">{{ job_card_detail.chassis_number }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Running Hour</th>
                                                        <th style="width: 50%">{{ job_card_detail.running_houre }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Section</th>
                                                        <th style="width: 50%">{{ job_card_detail.section }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Total Receviable Amount</th>
                                                        <th style="width: 50%">{{ job_card_detail.total_receviable }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Discount Amount</th>
                                                        <th style="width: 50%">{{ job_card_detail.discount_amount }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Total Service Cost/th>
                                                        <th style="width: 50%">{{ job_card_detail.total_service_cost }}</th>
                                                    </tr> <tr>
                                                        <th style="width: 50%">Invoice Number</th>
                                                        <th style="width: 50%">{{ job_card_detail.invoice_number }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Spare Parts</th>
                                                        <th style="width: 50%">{{ job_card_detail.spare_parts_sale }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">App Time</th>
                                                        <th style="width: 50%">{{ job_card_detail_detail.time_app }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Date</th>
                                                        <th style="width: 50%">{{ job_card_detail_detail.service_date }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Created At</th>
                                                        <th style="width: 50%">{{ job_card_detail_detail.created_at }}</th>
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
    </div>
</template>

<script>

export default {
    name: "List",
    data() {
        return {
            job_card_detail_detail_details: [],
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
                area_id: '',
                customer_id: '',
                engineer_id: '',
                product_id: '',
                model_id: '',
                call_type_id: '',
                section_id: '',
                service_type_id: '',
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
            }),
        }
    },
    mounted() {
        document.title = 'Service Request Details | Harvester';
        this.getServiceRequestDetailsList();
        this.getAllTechnician();
        this.getAllServiceType();
    },

    methods: {
        getServiceRequestDetailsList(){
            this.isLoading = true;
            axios.get(`/api/service-request-details/${this.$route.params.id}`).then((response)=>{
                console.log(response.data);
                this.job_card_detail = response.data.job_card_detail_detail
                this.isLoading = false;
            }).catch((error)=>{

            })
        },

        getAllTechnician(){
            axios.get('/api/get-all-technician').then((response)=>{
                this.technicians = response.data.data;
            }).catch((error)=>{

            })
        },
        getAllServiceType(){
            axios.get('/api/get-all-service-type').then((response)=>{
                this.service_type = response.data.data;
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
