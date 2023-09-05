<template>
    <div class="content">
        <div class="container-fluid">
            <breadcrumb :options="['Smart Assist List']">
                <div class="col-sm-6">
                    <div class="float-right d-none d-md-block">
                        <div class="card-tools">
                            <button type="button" class="btn btn-success btn-sm" @click="CreateSmartAssist">
                                <i class="fas fa-plus"></i>
                                Add Smart Assist
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
                                    <div class="card-tools">
                                        <input v-model="query" type="text" class="form-control" placeholder="Search by address">
                                    </div>

                                </div>
                                <div class="table-responsive">
                                    <table
                                        class="table table-bordered table-striped dt-responsive nowrap dataTable no-footer dtr-inline table-sm small">
                                        <thead>
                                        <tr>
                                            <th class="text-left">SN</th>
                                            <th class="text-left">Chassis Number</th>
                                            <th class="text-left">SARID</th>
                                            <th class="text-left">Password</th>
                                            <th class="text-left">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(smart_assist, i) in smart_assists" :key="smart_assist.chassis_no"
                                            v-if="smart_assists.length">
                                            <th class="text-center" scope="row">{{ ++i }}</th>
                                            <td class="text-left">{{ smart_assist.chassis_no }}</td>
                                            <td class="text-left">{{ smart_assist.sarid }}</td>
                                            <td class="text-left">{{ smart_assist.password }}</td>
                                            <td class="text-center">
                                                <button @click="edit(smart_assist)" class="btn btn-success btn-sm"><i
                                                    class="far fa-edit"></i></button>
<!--                                                <button @click="destroy(smart_assist.id)"-->
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
                                        @paginate="query === '' ? getAllSmartAssist() : searchData()"
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
        <div class="modal fade" id="smartassistModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title mt-0" id="myLargeModalLabel">{{ editMode ? "Edit" : "Add" }} Service
                            Center</h5>
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
                                            <label>Chassis Number</label>
                                            <input type="text" name="chassis"
                                                   v-model="form.chassis_no"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('chassis_no') }">
                                            <div class="error" v-if="form.errors.has('chassis_no')"
                                                 v-html="form.errors.get('chassis_no')"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label>SARID</label>
                                        <input type="text" name="Name" v-model="form.sarid"
                                               class="form-control"
                                               :class="{ 'is-invalid': form.errors.has('sarid') }">
                                        <div class="error" v-if="form.errors.has('sarid')"
                                             v-html="form.errors.get('sarid')"/>
                                    </div>
                                </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="text" name="password" v-model="form.password"
                                                   class="form-control"
                                                   :class="{ 'is-invalid': form.errors.has('password') }">
                                            <div class="error" v-if="form.errors.has('password')"
                                                 v-html="form.errors.get('password')"/>
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
                                {{ editMode ? "Update" : "Create" }} Smart Assist
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
            smart_assists: [],
            pagination: {
                current_page: 1
            },
            query: "",
            editMode: false,
            isLoading: false,
            form: new Form({
                chassis_no: '',
                sarid: '',
                password: '',
            }),
        }
    },
    watch: {
        query: function (newQ, old) {
            if (newQ === "") {
                this.getAllSmartAssist();
            } else {
                this.searchData();
            }
        }
    },
    mounted() {
        document.title = 'Smart Assist List | Harvester';
        this.getAllSmartAssist();
    },
    methods: {
        getAllSmartAssist() {
            this.isLoading = true;
            axios.get('/api/smart-assist-list?page=' + this.pagination.current_page).then((response) => {
                console.log('data', response.data.data)
                this.smart_assists = response.data.data;
                this.pagination = response.data.meta;
                this.isLoading = false;
            }).catch((error) => {

            })
        },
        searchData() {
            axios.get("/api/search/smart-assist-list/" + this.query + "?page=" + this.pagination.current_page).then(response => {
                this.smart_assists = response.data.data;
                this.pagination = response.data.meta;
            }).catch(e => {
                this.isLoading = false;
            });
        },
        reload() {
            this.getAllSmartAssist();
            this.query = "";
            this.$toaster.success('Data Successfully Refresh');
        },
        closeModal() {
            $("#smartassistModal").modal("hide");
        },
        CreateSmartAssist() {
            this.editMode = false;
            this.form.reset();
            this.form.clear();
            $("#smartassistModal").modal("show");


        },
        store() {
            this.form.busy = true;
            this.form.post("/api/smart-assist-list").then(response => {
                $("#smartassistModal").modal("hide");
                this.getAllSmartAssist();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        edit(smart_assist) {
            this.editMode = true;
            this.form.reset();
            this.form.clear();
            this.form.fill(smart_assist);
            $("#smartassistModal").modal("show");
        },
        update() {
            this.form.busy = true;
            this.form.put("/api/smart-assist-list/" + this.form.chassis_no).then(response => {
                $("#smartassistModal").modal("hide");
                this.getAllSmartAssist();
            }).catch(e => {
                this.isLoading = false;
            });
        },
        destroy(chassis_no) {
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
                    axios.delete('api/smart-assist-list/' + chassis_no).then((response) => {
                        this.getAllSmartAssist();
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
