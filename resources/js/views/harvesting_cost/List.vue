<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Harvesting Cost List']">
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
                                        <button type="button" class="btn btn-success btn-sm" @click="createHarvestingCost">
                                            <i class="fas fa-plus"></i>
                                            Add Harvesting Cost
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm" @click="reload">
                                            <i class="fas fa-sync"></i>
                                            Reload
                                        </button>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">Cost Title</th>
                                            <th class="text-left">Cost Measurement</th>
                                            <th class="text-left">Price</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(cost, i) in harvesting_cost" :key="cost.id" v-if="harvesting_cost.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ cost.cost_title }}</td>
                                            <td class="text-left">{{ cost.cost_measurement }}</td>
                                            <td class="text-left">{{ cost.price }}</td>
                                            <td class="text-center">
                                                <button @click="edit(cost)" class="btn btn-success btn-sm"><i
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
                                        @paginate="query === '' ? getAllHarvestingCostList() : searchData()"
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
        <div class="modal fade" id="harvestingCostModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Harvesting Cost</h5>
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
                                            <label>Cost Title</label>
                                            <input type="text" name="cost_title" v-model="form.cost_title" class="form-control" :class="{ 'is-invalid': form.errors.has('cost_title') }">
                                            <div class="error" v-if="form.errors.has('cost_title')" v-html="form.errors.get('cost_title')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Cost Measurement</label>
                                            <input type="text" name="cost_measurement" v-model="form.cost_measurement" class="form-control" :class="{ 'is-invalid': form.errors.has('cost_measurement') }">
                                            <div class="error" v-if="form.errors.has('cost_measurement')" v-html="form.errors.get('cost_measurement')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Price</label>
                                            <input type="number" name="price" v-model="form.price" class="form-control" :class="{ 'is-invalid': form.errors.has('price') }">
                                            <div class="error" v-if="form.errors.has('price')" v-html="form.errors.get('price')"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
                                <button :disabled="form.busy" type="submit" class="btn btn-primary">{{ editMode ? "Update" : "Create" }} Harvesting Cost</button>
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
            harvesting_cost: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                id: '',
                cost_title: '',
                cost_measurement: '',
                price: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllHarvestingCostList();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Harvesting Cost List | Harvester';
        this.getAllHarvestingCostList();
    },
    methods: {
        getAllHarvestingCostList() {
            this.isLoading = true;
            axios.get('/api/harvesting-cost?page=' + this.pagination.current_page).then((response) => {
                this.harvesting_cost = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/harvesting-cost/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.harvesting_cost = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllHarvestingCostList();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#harvestingCostModal").modal("hide");
        },
        createHarvestingCost() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#harvestingCostModal").modal("show");
        },
        store() {
            this.form.busy = true;
            this.form.post("/api/harvesting-cost").then(response => {
                $("#harvestingCostModal").modal("hide");
                this.getAllHarvestingCostList();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(harvesting_cost) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(harvesting_cost);
            $("#harvestingCostModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/harvesting-cost/" + this.form.id).then(response => {
                $("#harvestingCostModal").modal("hide");
                this.getAllHarvestingCostList();
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
                    axios.delete('api/harvesting-cost/' + id).then((response) => {
                        this.getAllHarvestingCostList();
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
