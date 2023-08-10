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
                                                        <th style="width: 50%">{{ service_request.engineer }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Technician name</th>
                                                        <th style="width: 50%">{{ service_request.technitian }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service type name</th>
                                                        <th style="width: 50%">{{ service_request.service_type}}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Area </th>
                                                        <th style="width: 50%">{{ service_request.area }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Customer name</th>
                                                        <th style="width: 50%">{{ service_request.customer_name }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Customer Mobile</th>
                                                        <th style="width: 50%">{{ service_request.customer_mobile }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Engineer</th>
                                                        <th style="width: 50%">{{ service_request.engineer }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Creator</th>
                                                        <th style="width: 50%">{{ service_request.creator_name }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Product</th>
                                                        <th style="width: 50%">{{ service_request.product}}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Model</th>
                                                        <th style="width: 50%">{{ service_request.model_name }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Contact person name</th>
                                                        <th style="width: 50%">{{ service_request.contact_person_name }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Call By</th>
                                                        <th style="width: 50%">{{ service_request.call_type }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Type</th>
                                                        <th style="width: 50%">{{ service_request.service_type }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Income</th>
                                                        <th style="width: 50%">{{ service_request.service_income }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service wanted date</th>
                                                        <th style="width: 50%">{{ service_request.service_wanted_at }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Start date</th>
                                                        <th style="width: 50%">{{ service_request.service_start_at }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service End date</th>
                                                        <th style="width: 50%">{{ service_request.service_end_at }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Installation date</th>
                                                        <th style="width: 50%">{{ service_request.installation_date }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Buy Date</th>
                                                        <th style="width: 50%">{{ service_request.buy_date }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Visited Date</th>
                                                        <th style="width: 50%">{{ service_request.visited_date }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Request No</th>
                                                        <th style="width: 50%">{{ service_request.service_request_no }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Request Status</th>
                                                        <th style="width: 50%">{{ service_request.service_request_status }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Hour</th>
                                                        <th style="width: 50%">{{ service_request.hour }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Remarks</th>
                                                        <th style="width: 50%">{{ service_request.remarks }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Income</th>
                                                        <th style="width: 50%">{{ service_request.service_income }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Address</th>
                                                        <th style="width: 50%">{{ service_request.address }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Chassis Number</th>
                                                        <th style="width: 50%">{{ service_request.chassis_number }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Running Hour</th>
                                                        <th style="width: 50%">{{ service_request.running_houre }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Section</th>
                                                        <th style="width: 50%">{{ service_request.section }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Total Receviable Amount</th>
                                                        <th style="width: 50%">{{ service_request.total_receviable }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Discount Amount</th>
                                                        <th style="width: 50%">{{ service_request.discount_amount }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Total Service Cost/th>
                                                        <th style="width: 50%">{{ service_request.total_service_cost }}</th>
                                                    </tr> <tr>
                                                        <th style="width: 50%">Invoice Number</th>
                                                        <th style="width: 50%">{{ service_request.invoice_number }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Spare Parts</th>
                                                        <th style="width: 50%">{{ service_request.spare_parts_sale }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">App Time</th>
                                                        <th style="width: 50%">{{ service_request.time_app }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Service Date</th>
                                                        <th style="width: 50%">{{ service_request.service_date }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Created At</th>
                                                        <th style="width: 50%">{{ service_request.created_at }}</th>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane p-3" id="profile-1" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4 class="page-title" style="font-size: 14px">Generator Info Details</h4>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                                    <tbody v-if="generator_info_details">
                                                    <tr>
                                                        <th class="text-center" style="width: 50%">Brand</th>
                                                        <th v-if="generator_info_details.brand" class="text-center" style="width: 50%">{{ generator_info_details.brand.name }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width: 50%">Generator Name</th>
                                                        <th class="text-center" style="width: 50%">{{ generator_info_details.generator_name }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width: 50%">Unique generator code</th>
                                                        <th class="text-center" style="width: 50%">{{ generator_info_details.unique_generator_code }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width: 50%">Generator model</th>
                                                        <th class="text-center" style="width: 50%">{{ generator_info_details.generator_model }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width: 50%">Rating</th>
                                                        <th class="text-center" style="width: 50%">{{ generator_info_details.rating }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width: 50%">Generator serial</th>
                                                        <th class="text-center" style="width: 50%">{{ generator_info_details.generator_serial }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width: 50%">Engine brand</th>
                                                        <th class="text-center" style="width: 50%">{{ generator_info_details.engine_brand }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width: 50%">Engine serial</th>
                                                        <th class="text-center" style="width: 50%">{{ generator_info_details.engine_serial }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width: 50%">Alternator brand</th>
                                                        <th class="text-center" style="width: 50%">{{ generator_info_details.alternator_brand }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width: 50%">Alternator_serial</th>
                                                        <th class="text-center" style="width: 50%">{{ generator_info_details.alternator_serial }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width: 50%">Delivery status</th>
                                                        <th class="text-center" style="width: 50%">{{ generator_info_details.delivery_status }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width: 50%">Generator Image</th>
                                                        <th class="text-center" style="width: 50%">
                                                            <div class="" v-for="(generator_image, i) in generator_info_details.generator_info_image" :key="generator_image.id" v-if="generator_info_details.generator_info_image.length">
                                                                <img class="rounded mr-2 mo-mb-2" alt="200x200" width="150" height="200" :src="generatorShowImage(generator_image.generator_image)" data-holder-rendered="true">
                                                            </div>
                                                        </th>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h4 class="page-title" style="font-size: 14px">Generator Additional Info</h4>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                                    <tbody v-if="generator_additional_info">
                                                    <tr>
                                                        <th style="width: 50%">Ratings</th>
                                                        <th style="width: 50%">{{ generator_additional_info.ratings }} KVA</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Volts</th>
                                                        <th style="width: 50%">{{ generator_additional_info.volts }} V</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Total running hours</th>
                                                        <th style="width: 50%">{{ generator_additional_info.total_running_hours }} HRS</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Frequency</th>
                                                        <th style="width: 50%">{{ generator_additional_info.frequency }} HZ</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Phase</th>
                                                        <th style="width: 50%">{{ generator_additional_info.phase }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Battery charging voltage</th>
                                                        <th style="width: 50%">{{ generator_additional_info.battery_charging_voltage }}</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Engine coolant temp</th>
                                                        <th style="width: 50%">{{ generator_additional_info.engine_coolant_temp }} C</th>
                                                    </tr>
                                                    <tr>
                                                        <th style="width: 50%">Engine oil pressure</th>
                                                        <th style="width: 50%">{{ generator_additional_info.engine_oil_pressure }} BAR/PSI</th>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h4 class="page-title" style="font-size: 14px">Generator Check Input Value</h4>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                                    <tbody>
                                                    <tr v-for="(check_value, i) in check_input" :key="check_value.id" v-if="check_input.length">
                                                        <th style="width: 50%">{{ check_value.name }}</th>
                                                        <th style="width: 50%">{{ check_value.value }}</th>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h4 class="page-title" style="font-size: 14px">Generator Service Period</h4>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                                    <tbody>
                                                    <tr v-for="(category, i) in service_period_category" :key="category.id" v-if="service_period_category.length">
                                                        <th style="width: 20%">{{ category.name }}</th>
                                                        <th style="width: 80%">
                                                            <span>Current Phase 1 : {{ category.current_phase_one }}</span><br>
                                                            <span>Current Phase 2 : {{ category.current_phase_two }}</span><br>
                                                            <span>Current Phase 3 : {{ category.current_phase_three }}</span><br>
                                                            <span>RPM : {{ category.rpm }}</span><br>
                                                            <span>Frequency : {{ category.frequency }}</span><br>
                                                            <span>Oil pressure : {{ category.oil_pressure }}</span><br>
                                                            <span>Cooling water temp : {{ category.cooling_water_temp }}</span>
                                                        </th>
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
    },
    created() {
        this.getServiceRequestDetails();
    },
    methods: {
        getServiceRequestDetails(){
            this.isLoading = true;
            axios.get(`/api/service-request-details/${this.$route.params.id}`).then((response)=>{
                console.log(response.data);
                this.service_request = response.data.service_request
                this.generator_info_details = response.data.service_request.generatorInfoDetails
                this.generator_additional_info = response.data.service_request.additionalGeneratorInfo
                this.check_input = response.data.checking_input.data
                this.service_period_category = response.data.service_period_category.data
                this.isLoading = false;
            }).catch((error)=>{

            })
        },

    },
}
</script>

<style lang="scss" scoped>

</style>
